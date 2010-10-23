<?php
/**
 * @license Artistic License 2.0
 *
 * This file is part of Round Eights.
 *
 * Round Eights is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * Round Eights is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with Round Eights. If not, see <http://www.RoundEights.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <James@RoundEights.com>
 * @copyright Copyright 2009, James Frasca, All Rights Reserved
 * @package Database
 */

namespace r8\DB\Link;

/**
 * Link decorator that provides an extended interface for executing query
 */
class Querier extends \r8\DB\Link\Decorator
{

    /**
     * Marks the start of a transaction
     *
     * @return \r8\DB\Link\Querier Returns a self reference
     */
    public function begin ()
    {
        $this->getLink()->query( "BEGIN" );
        return $this;
    }

    /**
     * Commits a transaction
     *
     * @return \r8\DB\Link\Querier Returns a self reference
     */
    public function commit ()
    {
        $this->getLink()->query( "COMMIT" );
        return $this;
    }

    /**
     * Rolls back the current transaction
     *
     * @return \r8\DB\Link\Querier Returns a self reference
     */
    public function rollBack ()
    {
        $this->getLink()->query( "ROLLBACK" );
        return $this;
    }

    /**
     * Takes an array of fields and constructs a field list for a query
     *
     * @param array $fields The fields to iterate over where the key
     *      is the field name and the value is the field value
     * @return String Returns a SQL field list
     */
    public function getFieldList ( array $fields )
    {
        $fields = \r8\ary\flatten($fields);

        if (count($fields) <= 0)
            throw new \r8\Exception\Argument(0, "Field List", "Must not be empty");

        foreach ($fields AS $name => $value) {
            $fields[$name] = "`". $name ."` = ". $this->quote($value);
        }

        return implode(", ", $fields);
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
     * @return Integer Returns the ID of the inserted row
     */
    public function insert ( $table, $fields )
    {
        $table = (string) $table;

        if ( \r8\isEmpty($table) )
            throw new \r8\Exception\Argument(0, "Table Name", "Must not be empty");

        $query = "INSERT INTO ". $table ." SET ". $this->getFieldList($fields);

        $result = $this->query($query);

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
     * @return \r8\iface\DB\Result Returns a Result object
     */
    public function update ($table, $where, $fields)
    {
        $table = (string) $table;

        if ( \r8\isEmpty($table) )
            throw new \r8\Exception\Argument(0, "Table Name", "Must not be empty");

        $query = "UPDATE ". $table ." SET ". $this->getFieldList($fields);

        $where = trim( (string) $where );

        if ( !\r8\isEmpty($where) )
            $query .= " WHERE ". $where;

        return $this->query($query);
    }

    /**
     * Runs a query and returns a specific row
     *
     * If no row is specified, the first one will be returned
     *
     * @param String $query The query to execute
     * @param Integer $row The row of a multi-result set to pull the field from
     * @return Mixed Returns the row of the result, or returns NULL if
     *      no results were returned
     */
    public function getRow ($query, $row = 0)
    {
        $result = $this->query($query);

        if ( !($result instanceof \r8\iface\DB\Result\Read) ) {
            $err = new \r8\Exception\Interaction("Query did not a valid Read result object");
            $err->addData("Query", $query);
            $err->addData("Returned Result", \r8\getDump($result));
            throw $err;
        }

        if ($result->count() <= 0)
            return NULL;

        $result->seek( $row );

        $value = $result->current();

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
     * @return Mixed Returns the value of the field, or NULL if no results
     *      were returned
     */
    public function getField ($field, $query, $row = 0)
    {
        $field = (string) $field;

        if ( \r8\isEmpty($field) )
            throw new \r8\Exception\Argument( 0, "Field", "Must not be empty" );

        $result = $this->getRow( $query, $row );

        if ( !is_array($result) && !($result instanceof \ArrayAccess) ) {
            $err = new \r8\Exception\Interaction("Row was not an array or accessable as an array");
            $err->addData("Query", $query);
            $err->addData("Returned Row", \r8\getDump($result));
            throw $err;
        }

        if ( !isset($result[ $field ]) ) {
            $err = new \r8\Exception\Argument( 0, "Field", "Field does not exist in row" );
            $err->addData("Query", $query);
            $err->addData("Returned Row", \r8\getDump($result));
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
     * @return Integer Returns the number of counted rows
     */
    public function count ($table, $where = FALSE)
    {
        $table = (string) $table;

        if ( \r8\isEmpty($table) )
            throw new \r8\Exception\Argument(0, "Table Name", "Must not be empty");

        $query = "SELECT COUNT(*) AS cnt FROM ". $table;

        $where = trim( (string) $where );

        if ( !\r8\isEmpty($where) )
            $query .= " WHERE ". $where;

        return (int) $this->getField("cnt", $query, 0 );
    }

}

