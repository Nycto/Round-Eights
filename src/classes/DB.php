<?php
/**
 * Database Registry
 *
 * @license Artistic License 2.0
 *
 * This file is part of commonPHP.
 *
 * commonPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * commonPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with commonPHP. If not, see <http://www.commonphp.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@commonphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Database
 */

namespace cPHP;

/**
 * Database Registry class
 *
 * This provides an interface for registering database Links in a global
 * repository and later retrieving them in a different scope.
 */
class DB
{

    /**
     * This list of registered connections indexed by a shortcut
     */
    static protected $links = array();

    /**
     * The default database connection
     */
    static protected $default;

    /**
     * Returns the full list of database connections
     *
     * @return Array
     */
    static public function getLinks ()
    {
        return self::$links;
    }

    /**
     * Returns the default connection
     *
     * @return Object The default connection
     */
    static public function getDefault ()
    {
        return self::$default;
    }

    /**
     * Registers a new link
     *
     * If no default link has been set, the link passed to this instance
     * will be set as the default
     *
     * @param String $label The reference string used to index the connection
     * @param Object $link The actual database connection
     * @return Null
     */
    static public function setLink( $label, \cPHP\iface\DB\Link $link )
    {
        $label = \cPHP\strval( $label );

        if ( \cPHP\isEmpty($label) )
            throw new \cPHP\Exception\Argument( 0, "Connection Label", "Must not be empty" );

        self::$links[ $label ] = $link;

        if ( !isset(self::$default) )
            self::setDefault( $label );
    }

    /**
     * Returns a registered link by it's label
     *
     * @param String $label The connection to return
     *      If no label is given, the default connection will be returned
     * @return Object The default connection
     */
    static public function get ( $label = NULL )
    {
        if ( !is_string($label) && \cPHP\isVague($label) )
            return self::getDefault();

        $label = \cPHP\strval( $label );

        if ( \cPHP\isEmpty($label) )
            throw new \cPHP\Exception\Argument( 0, "Connection Label", "Must not be empty" );

        if ( !array_key_exists($label, self::$links) )
            throw new \cPHP\Exception\Index("Connection Label", $label, "Connection does not exist");

        return self::$links[$label];
    }

    /**
     * Sets the default connection based on an already registered label
     *
     * @param String $label The name of the connection to make the default
     * @return NULL
     */
    static public function setDefault ( $label )
    {
        $label = \cPHP\strval( $label );

        if ( \cPHP\isEmpty($label) )
            throw new \cPHP\Exception\Argument( 0, "Connection Label", "Must not be empty" );

        self::$default = self::get( $label );
    }

}

?>