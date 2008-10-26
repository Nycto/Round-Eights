<?php
/**
 * Database Read result
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

namespace cPHP::DB::MySQLi;

/**
 * MySQLi Database read result
 */
class Read extends ::cPHP::DB::Result::Read
{

    /**
     * Internal method that returns the number of rows found
     *
     * @return Integer
     */
    protected function rawCount ()
    {
        return $this->getResult()->num_rows;
    }

    /**
     * Internal method to fetch the next row in a result set
     *
     * @return Array Returns the field values
     */
    protected function rawFetch ()
    {
        return $this->getResult()->fetch_assoc();
    }

    /**
     * Internal method to seek to a specific row in a result resource
     *
     * @param Integer $offset The offset to seek to
     * @return Array Returns the field values
     */
    protected function rawSeek ($offset)
    {
        $this->getResult()->data_seek($offset);
        return $this->rawFetch();
    }

    /**
     * Internal method to get a list of field names returned
     *
     * @return Array
     */
    protected function rawFields ()
    {
        $fields = $this->getResult()->fetch_fields();
        
        foreach ( $fields AS $key => $field ) {
            $fields[ $key ] = $field->name;
        }
        
        return $fields;
    }
    
    /**
     * Internal method to free the result resource
     *
     * @return null
     */
    protected function rawFree ()
    {
        $result = $this->getResult();
        $result->free();
    }

}

?>