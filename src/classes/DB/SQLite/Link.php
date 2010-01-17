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

namespace r8\DB\SQLite;

/**
 * SQLite Database Link
 */
class Link implements \r8\iface\DB\Adapter\Link
{

    /**
     * The SQLite database file to use
     *
     * @var String
     */
    private $file;

    /**
     * The configuration options for this connection
     *
     * @var \r8\DB\Config
     */
    private $config;

    /**
     * The database connection this link represents
     *
     * @var Resource
     */
    private $link;

    /**
     * Constructor...
     *
     * @param String $file The SQLite database file to use
     * @param \r8\DB\Config $config The credentials to use for opening the connection
     */
    public function __construct ( $file, \r8\DB\Config $config )
    {
        $file = trim( (string) $file );
        if ( empty($file) )
            throw new \r8\Exception\Argument(0, "Database File", "Must not be empty");

        $this->file = $file;
        $this->config = $config;
    }

    /**
     * Handles connection serialization
     *
     * @return Array
     */
    public function __sleep ()
    {
        return array("file", "config");
    }

    /**
     * Returns whether this connection is active
     *
     * @return Boolean
     */
    public function isConnected ()
    {
        return isset($this->link) ? TRUE : FALSE;
    }

    /**
     * Connect to the server
     *
     * @return NULL
     */
    public function connect ()
    {
        if ( isset($this->link) )
            return NULL;

        $error = null;

        $link = @sqlite_open( $this->file, 0666, $error );

        if ( !$link ) {
            throw new \r8\Exception\DB\Link( $error, NULL, $this );
        }

        $this->link = $link;
    }

    /**
     * Given a string, escapes it for use in a query
     *
     * @param String $value The string to escape
     * @return String Returns the escaped string
     */
    public function escape ( $value )
    {
        return sqlite_escape_string( (string) $value );
    }

    /**
     * Execute a query and return a result object
     *
     * @param String $query The query to execute
     * @return \r8\iface\DB\Adapter\Result
     */
    public function query ( $query )
    {
        if ( !isset($this->link) )
            $this->connect();

        $error = null;
        $result = @sqlite_query( $this->link, $query, SQLITE_ASSOC, $error );

        if ( $result === FALSE ) {
            $code = sqlite_last_error( $this->link );
            throw new \r8\Exception\DB\Query(
                $query,
                sqlite_error_string($code) .": ". $error,
                $code
            );
        }

        if ( \r8\DB\Link::isSelect($query) ) {
            return new \r8\DB\Result\Read(
                new \r8\DB\SQLite\Result( $result ),
                $query
            );
        }
        else {
            return new \r8\DB\Result\Write(
                sqlite_changes( $this->link ),
                sqlite_last_insert_rowid( $this->link ),
                $query
            );
        }
    }

    /**
     * Disconnect from the server
     *
     * @return null
     */
    public function disconnect ()
    {
        sqlite_close( $this->link );
        $this->link = null;
    }

    /**
     * Returns the name of the extension required to utilize this link
     *
     * @return String|NULL Returns NULL if no specific extension is required
     */
    public function getExtension ()
    {
        return "sqlite";
    }

    /**
     * Returns a brief string that can be used to describe this connection
     *
     * @return String Returns a URI that loosely identifies this connection
     */
    public function getIdentifier ()
    {
        return "sqlite://". $this->file;
    }

}

?>