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

namespace r8\iface\DB\Adapter;

/**
 * Database Connection Adapter
 */
interface Link extends \r8\iface\DB\Identified
{

    /**
     * Opens a new connection to the server
     *
     * @return NULL
     */
    public function connect ();

    /**
     * Given a string, escapes it for use in a query
     *
     * @param String $string The value to escape
     * @return String Returns the escaped string
     */
    public function escape ( $string );

    /**
     * Quotes the named identifier. This could be the name of a field, table,
     * or database
     *
     * @param String $name The named identifier to quote
     * @return String
     */
    public function quoteName ( $name );

    /**
     * Execute a query and return a result object
     *
     * @param String $query The query to execute
     * @return \r8\iface\DB\Adapter\Result
     */
    public function query ( $query );

    /**
     * Disconnect from the server
     *
     * @return NULL
     */
    public function disconnect ();

    /**
     * Returns whether this connection is active
     *
     * @return Boolean
     */
    public function isConnected ();

    /**
     * Returns the name of the extension required to utilize this link
     *
     * @return String|NULL Returns NULL if no specific extension is required
     */
    public function getExtension ();

}

