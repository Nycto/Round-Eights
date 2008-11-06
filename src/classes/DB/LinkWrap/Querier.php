<?php
/**
 * Advanced querying linkwrap
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

namespace cPHP::DB::LinkWrap;

/**
 * Link wrapper to provide advanced
 */
class Querier extends ::cPHP::DB::LinkWrap
{

    /**
     * Query Flags
     */
    const SILENT = 1;
    const INSERT_IGNORE = 2;

    /**
     * Runs a query and returns the result
     *
     * @param String $query The query to run
     * @param Integer $flags Any boolean flags to set
     * @result Object Returns a result object
     */
    public function query ( $query, $flags = 0 )
    {
        $flags = intval( $flags );
        $query = ::cPHP::strval($query);

        try {
            return $this->getLink()->query( $query, $flags );
        }
        catch (::cPHP::Exception::DB::Query $err) {

            if ( !( $flags & self::SILENT) ) {
                $err->shiftFault();
                throw $err;
            }

            return FALSE;

        }

    }

    /**
     * Marks the start of a transaction
     *
     * @return Object Returns a self reference
     */
    public function begin ()
    {
        $this->getLink()->query( "BEGIN" );
        return $this;
    }

    /**
     * Commits a transaction
     *
     * @return Object Returns a self reference
     */
    public function commit ()
    {
        $this->getLink()->query( "COMMIT" );
        return $this;
    }

    /**
     * Rolls back the current transaction
     *
     * @return Object Returns a self reference
     */
    public function rollBack ()
    {
        $this->getLink()->query( "ROLLBACK" );
        return $this;
    }

    /**
     * Takes an array of fields and constructs a field list for a query
     *
     * @param array|object $fields The fields to iterate over where the key
     *      is the field name and the value is the field value
     * @return String Returns a SQL field list
     */
    public function getFieldList ($fields)
    {
        if ( !::cPHP::Ary::is($fields) )
            throw new ::cPHP::Exception::Argument(0, "Field List", "Must be an array or traversable");

        $fields = ::cPHP::Ary::create($fields)->flatten();

        if (count($fields) <= 0)
            throw new ::cPHP::Exception::Argument(0, "Field List", "Must not be empty");

        foreach ($fields AS $name => $value) {
            $fields[$name] = "`". $name ."` = ". $this->quote($value);
        }

        return $fields->implode(", ");
    }

    /**
     * Inserts a row in to a table
     *
     * Field values are set by passing an array to the $fields argument. Array
     * keys are used as field names and array values are used as field values.
     * Values are automatically escaped.
     *
     * @param String $table The table to insert into
     * @param Array|Object $fields The associative array of fields to insert
     * @param Integer $flags Any query flags to use
     * @return Integer Returns the ID of the inserted row
     */
    public function insert ( $table, $fields, $flags = 0 )
    {
        $table = ::cPHP::strval($table);

        if ( ::cPHP::isEmpty($table) )
            throw new ::cPHP::Exception::Argument(0, "Table Name", "Must not be empty");

        $query = "INSERT INTO ". $table ." SET ". $this->getFieldList($fields);

        $result = $this->query($query, $flags);

        if ( $result === FALSE )
            return FALSE;

        return $result->getInsertID();
    }

    /**
     * Updates a table with the given values
     *
     * Like the insert method, the fields parameter should be an associative
     * array or equivilent object. The keys represent field names and the
     * array values are the new field values.
     *
     * @param String $table The table to update
     * @param String $where Any WHERE clause restrictions to place on the query
     * @param Array|Object $fields The associative array of fields update
     * @param Integer $flags Any query flags to use
     * @return Object Returns a Result object
     */
    public function update ($table, $where, $fields, $flags = 0)
    {
        $table = ::cPHP::strval($table);

        if ( ::cPHP::isEmpty($table) )
            throw new ::cPHP::Exception::Argument(0, "Table Name", "Must not be empty");

        $query = "UPDATE ". $table ." SET ". $this->getFieldList($fields);

        $where = trim( ::cPHP::strval($where) );

        if ( !::cPHP::isEmpty($where) )
            $query .= " WHERE ". $where;

        return $this->query($query, $flags);
    }

    /**
     * Runs a query and returns a specific row
     *
     * If no row is specified, the first one will be returned
     *
     * @param String $query The query to execute
     * @param Integer $row The row of a multi-result set to pull the field from
     * @param Integer $flags Any query flags to use
     * @return mixed Returns the row of the result, or returns FALSE if no results were returned
     */
    public function getRow ($query, $row = 0, $flags = 0)
    {
        $result = $this->query($query, $flags);

        if ( !($result instanceof ::cPHP::DB::Result::Read) ) {
            $err = new ::cPHP::Exception::Interaction("Query did not a valid Read result object");
            $err->addData("Query", $query);
            $err->addData("Returned Result", ::cPHP::getDump($result));
            throw $err;
        }

        if ($result->count() <= 0)
            return FALSE;

        $value = $result->seek( $row )->current();

        $result->free();

        return $value;
    }

    /**
     * Runs a query and returns a specific field from a row
     *
     * If no row is specified, the first row will be used
     *
     * @param String $field The field to return
     * @param String $query The query to execute
     * @param Integer $row The row of a multi-result set to pull the field from
     * @return mixed Returns the value of the field, or FALSE if no results were returned
     */
    public function getField ($field, $query, $row = 0, $flags = 0)
    {
        $field = ::cPHP::strval( $field );

        if ( ::cPHP::isEmpty($field) )
            throw new ::cPHP::Exception::Argument( 0, "Field", "Must not be empty" );

        $result = $this->getRow( $query, $row, $flags );

        if ( !is_array($result) && !($result instanceof ArrayAccess) ) {
            $err = new ::cPHP::Exception::Interaction("Row was not an array or accessable as an array");
            $err->addData("Query", $query);
            $err->addData("Returned Row", ::cPHP::getDump($result));
            throw $err;
        }

        if ( !isset($result[ $field ]) ) {
            $err = new ::cPHP::Exception::Argument( 0, "Field", "Field does not exist in row" );
            $err->addData("Query", $query);
            $err->addData("Returned Row", ::cPHP::getDump($result));
            throw $err;
        }

        return $result[ $field ];
    }

    /**
     * Counts the number of rows in a table
     *
     * To restrict which rows are affected, pass a WHERE clause through the $where
     * argument.
     *
     * @param String $table The table to update
     * @param String $where Any WHERE clause restrictions to place on the query
     * @param Integer $flags Any query flags to use
     * @return Integer Returns the number of counted rows
     */
    public function count ($table, $where = FALSE, $flags = 0)
    {
        $table = ::cPHP::strval($table);

        if ( ::cPHP::isEmpty($table) )
            throw new ::cPHP::Exception::Argument(0, "Table Name", "Must not be empty");

        $query = "SELECT COUNT(*) AS cnt FROM ". $table;

        $where = trim( ::cPHP::strval($where) );

        if ( !::cPHP::isEmpty($where) )
            $query .= " WHERE ". $where;

        return intval( $this->getField("cnt", $query, 0, $flags) );
    }

}

?>