<?php
/**
 * Database Query Result
 *
 * PHP version 5.3
 *
 * This source file is subject to version 2.0 of the Artistic License. A copy
 * of the license should have been bundled with this source file inside a file
 * named LICENSE.txt. It is also available through the world-wide-web at one
 * of the following URIs:
 * http://www.commonphp.com/license.php
 * http://www.opensource.org/licenses/artistic-license-2.0.php
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @author James Frasca <james@commonphp.com>
 * @license Artistic License 2.0 http://www.commonphp.com/license.php
 * @package Database
 */

namespace cPHP::DB::Result;

/**
 * Database Read Query Results
 */
abstract class Read extends ::cPHP::DB::Result implements Countable, SeekableIterator
{

    /**
     * The database result resource
     */
    private $result;

    /**
     * The number of rows returned by this query
     */
    private $count;

    /**
     * The list of fields in the result set
     */
    private $fields;

    /**
     * The current offset of the result set
     */
    private $pointer;

    /**
     * The value of the current row
     */
    private $row;

    /**
     * Constructor...
     *
     * @param Resource|Object $result The database result resource or object
     * @param String $query The query that produced this result
     */
    public function __construct ( $result, $query )
    {
        if (is_resource($result) || is_object($result))
            $this->result = $result;

        parent::__construct($query);
    }

    /**
     * Destructor...
     *
     * Ensures that the resource is freed
     */
    public function __destruct()
    {
        $this->free();
    }

    /**
     * Returns whether this instance currently holds a valid resource
     *
     * @return Boolean
     */
    public function hasResult ()
    {
        return isset( $this->result )
            && ( is_resource( $this->result ) || is_object( $this->result ) );
    }

    /**
     * Returns the result resource this instance encases
     *
     * @return mixed Returns NULL if there is no resource set
     */
    protected function getResult ()
    {
        if ( $this->hasResult() )
            return $this->result;
        else
            return NULL;
    }

    /**
     * Internal method that returns the number of rows found
     *
     * @return Integer
     */
    abstract protected function rawCount ();

    /**
     * Returns the number of rows affected by a query
     *
     * This also provides the functionality to access the number of rows in this
     * result set via the "count" method
     *
     * @return Integer
     */
    public function count ()
    {

        if ( !isset($this->count) ) {

            $this->count = $this->rawCount();

            if ( !is_int($this->count) )
                $this->count = 0;
        }

        return $this->count;
    }

    /**
     * Internal method to get a list of field names returned
     *
     * @return Integer
     */
    abstract protected function rawFields ();

    /**
     * Returns a list of field names returned by the query
     *
     * @return Object Returns a cPHP::Ary Object
     */
    public function getFields ()
    {
        if ( !isset($this->fields) ) {

            $this->fields = $this->rawFields();

            if ( !is_array($this->fields) )
                $this->fields = array();
        }

        return new ::cPHP::Ary( $this->fields );
    }

    /**
     * Returns whether a field exists in the results
     *
     * @param String $field The case-sensitive field name
     * @return Boolean
     */
    public function isField ( $field )
    {
        return $this->getFields()->contains( ::cPHP::strval($field) );
    }

    /**
     * Returns the number of fields in the result set
     *
     * @return Integer
     */
    public function fieldCount ()
    {
        return $this->getFields()->count();
    }

    /**
     * Internal method to fetch the next row in a result set
     *
     * @return Array Returns the field values
     */
    abstract protected function rawFetch ();

    /**
     * Internal method to seek to a specific row in a result resource
     *
     * @param Integer $offset The offset to seek to
     * @return Array Returns the field values
     */
    abstract protected function rawSeek ($offset);

    /**
     * Returns the value of the current row
     *
     * Iterator interface function
     *
     * @return mixed Returns the current Row, or FALSE if the iteration has
     *      reached the end of the row list
     */
    public function current ()
    {
        // If the pointer has not yet been initialize, grab the first row
        if ( !isset($this->pointer) )
            $this->next();

        return $this->row;
    }

    /**
     * Returns the whether the current row is valid
     *
     * Iterator interface function
     *
     * @return Boolean
     */
    public function valid ()
    {
        $count = $this->count();

        if ( $count == 0 )
            return FALSE;

        return $this->pointer < $count ? TRUE : FALSE;
    }

    /**
     * Increments to the next result row
     *
     * Iterator interface function
     *
     * @return Object Returns a self reference
     */
    public function next ()
    {

        // If the pointer isn't set yet, start it at 0
        if ( !isset($this->pointer) )
            $this->pointer = 0;

        // Don't increment beyond the count
        else if ( $this->pointer < $this->count() )
            $this->pointer++;

        // If there are still rows to fetch, grab the next one
        if ( $this->pointer < $this->count() )
            $this->row = $this->rawFetch();
        else
            $this->row = FALSE;

        return $this;
    }

    /**
     * Returns the offset of the current result row
     *
     * Iterator interface function
     *
     * @return Integer
     */
    public function key ()
    {
        // If the pointer has not yet been initialize, grab the first row
        if ( !isset($this->pointer) )
            $this->next();

        // The key is simply the internal row pointer
        return $this->pointer;
    }

    /**
     * Resets the result iterator to the beginning
     *
     * Iterator interface function
     *
     * @return Object Returns a self reference
     */
    public function rewind ()
    {
        // If the pointer hasn't been initialized at all, then we just need to fetch the first row
        if ( !isset($this->pointer) )
            $this->next();

        // If the pointer is already at zero, we don't need to do anything
        else if ( $this->pointer > 0 )
            $this->seek(0);

        return $this;
    }

    /**
     * Sets the internal result pointer to a given offset
     *
     * SeekableIterator interface function
     *
     * @param Integer $offset The offset to seek to
     * @param Integer $wrapFlag How to handle offsets that fall outside of the length of the list.
     * @return Object Returns a self reference
     */
    public function seek ( $offset, $wrapFlag = ::cPHP::num::OFFSET_RESTRICT )
    {
        $offset = ::cPHP::num::offsetWrap(
                $this->count(),
                $offset,
                $wrapFlag
            );

        if ( $offset !== FALSE && $this->pointer !== $offset ) {
            $this->pointer = $offset;
            $this->row = $this->rawSeek( $offset );
        }

        return $this;
    }

    /**
     * Internal method to free the result resource
     *
     * @return null
     */
    abstract protected function rawFree ();

    /**
     * Frees the resource in this instance
     *
     * @return Object Returns a self reference
     */
    public function free ()
    {
        if ( $this->hasResult() ) {
            $this->rawFree();
            $this->result = null;
        }
        return $this;
    }

}

?>