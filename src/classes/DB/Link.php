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

namespace r8\DB;

/**
 * A Database Connection
 */
class Link implements \r8\iface\DB\Link
{

    /**
     * The database adapter to interface with
     *
     * @var \r8\iface\DB\Adapter\Link
     */
    private $adapter;

    /**
     * Returns whether a query is a SELECT query
     *
     * @param String $query The query being tested
     * @return Boolean
     */
    static public function isSelect ( $query )
    {
        $query = (string) $query;
        $query = \r8\str\stripQuoted($query, array("'", '"', "`"));
        $query = trim($query);

        return preg_match("/^\s*[\(?\s*]*(?:EXPLAIN\s+)?SELECT/i", $query) ? TRUE : FALSE;
    }

    /**
     * Returns whether a query is an INSERT query
     *
     * @param String $query The query being tested
     * @return Boolean
     */
    static public function isInsert ( $query )
    {
        $query = (string) $query;
        $query = \r8\str\stripQuoted($query, array("'", '"', "`"));
        $query = trim($query);

        return preg_match("/^\s*INSERT\b/i", $query) ? TRUE : FALSE;
    }

    /**
     * Prepares a value for a SQL statement
     *
     * @param mixed $value The value to prepare
     * @param Boolean $allowNull Whether NULL is an acceptable value
     * @param Callback $onString The function to invoke if the value
     *      is a string that needs to be escaped
     * @return String Returns the cleansed value
     */
    static public function cleanseValue ( $value, $allowNull, $onString )
    {
        if ( !is_callable($onString) )
            throw new \r8\Exception\Argument(0, "onString Callback", "Must be Callable");

        if ( is_array($value) ) {
            $result = array();
            foreach ( $value AS $key => $toCleanse ) {
                $result[ $key ] = self::cleanseValue(
                    $toCleanse,
                    $allowNull,
                    $onString
                );
            }
            return $result;
        }

        $value = \r8\reduce( $value );

        switch ( gettype($value) ) {

            case "boolean":
                return $value ? "1" : "0";

            case "integer":
            case "double":
                return (string) $value;

            case "NULL":
                return $allowNull ? "NULL" : call_user_func( $onString, "" );

            default:
            case "string":
                if ( is_numeric($value) && !preg_match('/[^0-9\.]/', $value) )
                    return $value;

                return call_user_func( $onString, $value );
        }
    }

    /**
     * Constructor...
     *
     * @param \r8\iface\DB\Adapter\Link $adapter The database adapter to interface with
     */
    public function __construct ( \r8\iface\DB\Adapter\Link $adapter )
    {
        $extension = $adapter->getExtension();

        // Ensure that the required extension is loaded
        if ( !empty($extension) && !extension_loaded( $extension ) ) {
            throw new \r8\Exception\Extension(
                $extension,
                "Extension is not loaded"
            );
        }

        $this->adapter = $adapter;
    }

    /**
     * Destructor...
     *
     * Automatically closes the database Link when the object is cleaned up
     */
    public function __destruct ()
    {
        $this->disconnect();
    }

    /**
     * Returns whether this instance is currently connected
     *
     * @return Boolean
     */
    public function isConnected ()
    {
        return $this->adapter->isConnected();
    }

    /**
     * Runs a query and returns the result
     *
     * @param String $query The query to run
     * @param Integer $flags Any boolean flags to set
     * @return \r8\iface\DB\Result Returns a result object
     */
    public function query ( $query, $flags = 0 )
    {
        $query = (string) $query;

        if ( \r8\isEmpty($query) )
            throw new \r8\Exception\Argument(0, "Query", "Must not be empty");

        if ( !$this->adapter->isConnected() )
            $this->adapter->connect();

        try {
            $result = $this->adapter->query( $query );
        }
        catch (\r8\Exception\DB\Query $err) {
            $err->shiftFault();
            throw $err;
        }

        if ( !( $result instanceof \r8\iface\DB\Result ) ) {
            throw new \r8\Exception\DB\Query(
                $query,
                'Query did not return a \r8\iface\DB\Result object',
                0,
                $this->adapter
            );
        }

        return $result;
    }

    /**
     * If there is currently a cunnection, this will break it
     *
     * @return Object Returns a self reference
     */
    public function disconnect ()
    {
        if ( $this->adapter->isConnected() )
            $this->adapter->disconnect();

        return $this;
    }

    /**
     * Quotes a variable to be used in a query
     *
     * When given a string, it escapes the string and puts quotes around it. When
     * given a number, it returns the number as is. When given a boolean value,
     * it returns 0 or 1. When given a NULL value, it returns the word NULL as a string.
     *
     * If this function is given an array, it will apply itself to every value
     * in the array and return the array.
     *
     * @param mixed $value The value to quote
     * @param Boolean $allowNull Whether to allow
     * @return String|Array Returns the escaped string, or an array
     */
    public function quote ( $value, $allowNull = TRUE )
    {
        $adapter = $this->adapter;
        return self::cleanseValue(
            $value,
            $allowNull,
            function ($value) use ( $adapter ) {
                return "'". $adapter->escape($value) ."'";
            }
        );
    }

    /**
     * Escapes a variable to be used in a query
     *
     * This function works almost exactly like cDB::quote except that it does
     * not add quotation marks to strings. It just escapes each value.
     *
     * If this function is given an array, it will apply itself to every value
     * in the array and return that array.
     *
     * @param mixed $value The value to quote
     * @param Boolean $allowNull Whether to allow
     * @return String|array Returns the escaped string, or an array
     */
    public function escape ( $value, $allowNull = TRUE )
    {
        return self::cleanseValue(
            $value,
            $allowNull,
            array( $this->adapter, "escape" )
        );
    }

    /**
     * Quotes the named identifier. This could be the name of a field, table,
     * or database
     *
     * @param String $name The named identifier to quote
     * @return String
     */
    public function quoteName ( $name )
    {
        return $this->adapter->quoteName( $name );
    }

    /**
     * Returns a brief string that can be used to describe this connection
     *
     * @return String Returns a URI that loosely identifies this connection
     */
    public function getIdentifier ()
    {
        return $this->adapter->getIdentifier();
    }

}

?>