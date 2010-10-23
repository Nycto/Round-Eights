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

namespace r8\DB\BlackHole;

/**
 * A Database connection that throws away any input it is given
 *
 * This has the ability to load Results into a queue, which will then be returned
 * as Select statements come in. Once the queue runs out, empty results will
 * be returned.
 */
class Link implements \r8\iface\DB\Adapter\Link
{

    /**
     * The queue of result objects to return when a select query is executed
     *
     * @var Array An array of \r8\iface\DB\Adapter\Result objects
     */
    private $queue = array();

    /**
     * An internal counter to keep track of the dished out insert ids
     *
     * @var Integer
     */
    private $insertID = 0;

    /**
     * Constructor...
     *
     * @param \r8\iface\DB\Adapter\Result $result... Any results to import into
     *      this link
     */
    public function __construct ()
    {
        if ( func_num_args() > 0 ) {
            $args = func_get_args();
            foreach ( $args AS $arg ) {
                if ( $arg instanceof \r8\iface\DB\Adapter\Result )
                    $this->addResult( $arg );
            }
        }
    }

    /**
     * Returns the Queue of results currently loaded in this instance
     *
     * @return Array An array of \r8\iface\DB\Adapter\Result objects
     */
    public function getQueue ()
    {
       return $this->queue;
    }

    /**
     * Adds a new result to be returned at the end of the queue
     *
     * The results will be returned in a first-in/first-out manner when a select
     * query is run against this link
     *
     * @param \r8\iface\DB\Adapter\Result $result The result to return
     * @return \r8\DB\BlackHole\Link Returns a self reference
     */
    public function addResult ( \r8\iface\DB\Adapter\Result $result )
    {
        $this->queue[] = $result;
        return $this;
    }

    /**
     * Opens a new connection to the server
     *
     * @return NULL
     */
    public function connect ()
    {
        return NULL;
    }

    /**
     * Given a string, escapes it for use in a query
     *
     * @param String $string The value to escape
     * @return String Returns the escaped string
     */
    public function escape ( $string )
    {
        return addslashes( $string );
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
        return "`". ( (string) $name ) ."`";
    }

    /**
     * Runs a query and returns the result
     *
     * @param String $query The query to run
     * @param Integer $flags Any boolean flags to set
     * @returns \r8\iface\DB\Result Returns a result object
     */
    public function query ( $query, $flags = 0 )
    {
        $query = (string) $query;

        if ( \r8\DB\Link::isSelect($query) ) {

            if ( empty($this->queue) )
                $result = new \r8\DB\BlackHole\Result;
            else
                $result = array_shift( $this->queue );

            return new \r8\DB\Result\Read( $result, $query );
        }

        else if ( \r8\DB\Link::isInsert($query) ) {
            return new \r8\DB\Result\Write( 1, ++$this->insertID, $query );
        }

        else {
            return new \r8\DB\Result\Write( 0, null, $query );
        }
    }

    /**
     * Disconnect from the server
     *
     * @return null
     */
    public function disconnect ()
    {
        return NULL;
    }

    /**
     * Returns whether this connection is active
     *
     * @return Boolean
     */
    public function isConnected ()
    {
        return TRUE;
    }

    /**
     * Returns the name of the extension required to utilize this link
     *
     * @return String|NULL Returns NULL if no specific extension is required
     */
    public function getExtension ()
    {
        return NULL;
    }

    /**
     * Returns a brief string that can be used to describe this connection
     *
     * @return String Returns a URI that loosely identifies this connection
     */
    public function getIdentifier ()
    {
        return "blackhole";
    }

}

