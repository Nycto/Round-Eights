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
 * Core Database Connection
 *
 * The base class for database connections. Provides an interface for
 * setting up the link, performing actions against the resource and
 * automatically disconnecting
 */
abstract class Link implements \r8\iface\DB\Link
{

    /**
     * To be overridden, this is the PHP extension required for this interface to work
     *
     * @var String
     */
    const PHP_EXTENSION = FALSE;

    /**
     * The configuration details for this connection
     *
     * @var \r8\DB\Config
     */
    private $config;

    /**
     * Once connected, this is the Link resource
     *
     * @var Resource
     */
    private $resource;

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
     * 		is a string that needs to be escaped
     * @return String Returns the cleansed value
     */
    static public function cleanseValue ( $value, $allowNull, $onString )
    {
        if ( !is_callable($onString) )
            throw new \r8\Exception\Argument(0, "onString Callback", "Must be Callable");

        if ( is_array($value) ) {
            $result = array();
            foreach ( $value AS $key => $toCleanse ) {
                $result[ $key ] = self::cleanseValue($toCleanse, $allowNull, $onString);
            }
            return $result;
        }

        $value = \r8\reduce($value);

        if (is_bool($value))
            return $value ? "1" : "0";

        else if ( is_int($value) || is_float($value) )
            return (string) $value;

        else if ( is_null($value) )
            return $allowNull ? "NULL" : call_user_func( $onString, "" );

        else if ( is_numeric($value) && !preg_match('/[^0-9\.]/', $value) )
            return $value;

        else
            return call_user_func( $onString, $value );
    }

    /**
     * Constructor...
     *
     * @param \r8\DB\Config $config The configuration details for this connection
     */
    public function __construct ( \r8\DB\Config $config )
    {
        // Ensure that the required extension is loaded
        if ( static::PHP_EXTENSION != false && !extension_loaded( static::PHP_EXTENSION ) ) {
            throw new \r8\Exception\Extension(
                    static::PHP_EXTENSION,
                    "Extension is not loaded"
                );
        }
        
        $this->config = $config;
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
     * Connect to the server
     *
     * @return Resource Returns a database connection resource
     */
    abstract protected function rawConnect ();

    /**
     * Execute a query and return a result object
     *
     * @param String $query The query to execute
     * @return Object Returns a \r8\DB\Result object
     */
    abstract protected function rawQuery ( $query );

    /**
     * Disconnect from the server
     *
     * @return null
     */
    abstract protected function rawDisconnect ();

    /**
     * Returns whether a given resource is still connected
     *
     * @param Resource|Object $connection The connection being tested
     * @return Boolean
     */
    abstract protected function rawIsConnected ( $connection );

    /**
     * Returns whether this instance is currently connected
     *
     * @return Boolean
     */
    public function isConnected ()
    {
        $result =
            isset($this->resource)
            && ( is_resource($this->resource) || is_object($this->resource) )
            && $this->rawIsConnected( $this->resource );

        if ( !$result )
            $this->resource = null;

        return $result ? TRUE : FALSE;
    }

    /**
     * Returns the connection resource
     *
     * If this instance is not already connected, this will attempt to make the connection
     *
     * @return Resource Returns a database connection resource
     */
    public function getLink ()
    {
        if ( !$this->isConnected() ){

            $this->validateCredentials();

            $result = $this->rawConnect();

            if ( !is_resource($result) && !is_object($result) ) {
                throw new \r8\Exception\DB\Link(
                        "Database connector did not return a resource or an object",
                        0,
                        $this
                    );
            }

            $this->resource = $result;
        }

        return $this->resource;
    }

    /**
     * Returns a brief string that can be used to describe this connection
     *
     * @return String Returns a URN
     */
    public function getIdentifier ()
    {
        if ( preg_match('/^r8\\\\DB\\\\([a-z0-9]+)\\\\Link$/i', get_class($this), $matches ) )
            $ident = $matches[1];
        else
            $ident = "db";

        $ident .= "://";

        if ( !$this->hostExists() )
            return $ident ."hash:". spl_object_hash($this);

        if ( $this->userNameExists() )
            $ident .= $this->getUserName() ."@";

        $ident .= $this->getHostWithPort();

        return $ident;
    }

    /**
     * Runs a query and returns the result
     *
     * @param String $query The query to run
     * @param Integer $flags Any boolean flags to set
     * @return \r8\DB\Result Returns a result object
     */
    public function query ( $query, $flags = 0 )
    {
        $query = (string) $query;

        if ( \r8\isEmpty($query) )
            throw new \r8\Exception\Argument(0, "Query", "Must not be empty");

        try {
            $result = $this->rawQuery( $query );
        }
        catch (\r8\Exception\DB\Query $err) {
            $err->shiftFault();
            throw $err;
        }

        if ( !( $result instanceof \r8\DB\Result ) ) {
            throw new \r8\Exception\DB\Query(
                    $query,
                    "Query did not return a \r8\DB\Result object",
                    0,
                    $this
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
        if ( $this->isConnected() )
            $this->rawDisconnect();
        $this->link = null;
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
        $self = $this;
        return self::cleanseValue(
                $value,
                $allowNull,
                function ($value) use ( $self ) {
                    return "'". $self->escapeString($value) ."'";
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
                array( $this, "escapeString" )
            );
    }

}

?>