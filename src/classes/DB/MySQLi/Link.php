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

namespace r8\DB\MySQLi;

/**
 * MySQL Database Link
 */
class Link implements \r8\iface\DB\Adapter\Link
{

    /**
     * The configuration options for this connection
     *
     * @var \r8\DB\Config
     */
    private $config;

    /**
     * The database connection this link represents
     *
     * @var MySQLi
     */
    private $link;

    /**
     * Constructor...
     *
     * @param \r8\DB\Config $config The credentials to use for opening the connection
     */
    public function __construct ( \r8\DB\Config $config )
    {
        $this->config = $config;
    }

    /**
     * Returns whether this connection is active
     *
     * @return Boolean
     */
    public function isConnected ()
    {
        if ( !($this->link instanceof \MySQLi) )
            return FALSE;

        return TRUE;
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

        $link = @mysqli_connect(
            $this->config->getHost(),
            $this->config->getUserName(),
            $this->config->getPassword(),
            $this->config->getDatabase(),
            $this->config->getPort()
        );

        if ( !$link ) {
            throw new \r8\Exception\DB\Link(
                mysqli_connect_error(),
                mysqli_connect_errno(),
                $this
            );
        }

        $this->link = $link;
    }

    /**
     * Given a string, escapes it for use in a query
     *
     * @param String $value The string to escape. If an array is given, all the
     *      values in it will be escaped
     * @return String Returns the escaped string
     */
    public function escape ( $value )
    {
        if ( is_array( $value ) )
            return array_map( array($this, "escape"), $value );

        $value = (string) $value;

        if ( !isset($this->link) )
            $this->connect();

        return $this->link->real_escape_string( $value );
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

        $result = $this->link->query( $query );

        if ( $result === FALSE ) {
            throw new \r8\Exception\DB\Query(
                $query,
                $this->link->error,
                $this->link->errno
            );
        }

        if ( \r8\DB\Link::isSelect($query) ) {
            return new \r8\DB\Result\Read(
                new \r8\DB\MySQLi\Result( $result ),
                $query
            );
        }
        else {
            return new \r8\DB\Result\Write(
                $this->link->affected_rows,
                $this->link->insert_id,
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
        $this->link->close();
        $this->link = null;
    }

    /**
     * Returns the name of the extension required to utilize this link
     *
     * @return String|NULL Returns NULL if no specific extension is required
     */
    public function getExtension ()
    {
        return "mysqli";
    }

    /**
     * Returns a brief string that can be used to describe this connection
     *
     * @return String Returns a URI that loosely identifies this connection
     */
    public function getIdentifier ()
    {
        return $this->config->getIdentifier("MySQLi");
    }

}

?>