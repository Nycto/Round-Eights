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
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Autoload
 */

namespace r8;

/**
 * A simple interface for associating a class prefix and where to
 * look for the class files
 */
class Autoload
{

    /**
     * The global instance of this object
     *
     * @var \r8\Autoload
     */
    static private $instance;

    /**
     * The list of prefixes and the directory to look in
     *
     * @var Array
     */
    private $map = array();

    /**
     * Returns the global instance of this object
     *
     * @return \r8\Autoload
     */
    static public function getInstance ()
    {
        if ( !isset(self::$instance) )
            self::$instance = new self;
        return self::$instance;
    }

    /**
     * Returns the Registered Prefix to Location Map
     *
     * @return Array
     */
    public function getRegistered ()
    {
        return $this->map;
    }

    /**
     * Adds a new prefix and where to look for it
     *
     * @param String $prefix The class Prefix to look for
     * @param String $location Where to look for this file
     * @return \r8\Autoload Returns a self reference
     */
    public function register ( $prefix, $location )
    {
        $prefix = trim( (string) $prefix, " /" );

        if ( \r8\isEmpty($prefix) )
            throw new \r8\Exception\Argument( 0, "Class Prefix", "Must not be empty" );

        $location = (string) $location;

        if ( \r8\isEmpty($location) )
            throw new \r8\Exception\Argument( 1, "File Location", "Must not be empty" );

        $this->map[ '/'. $prefix .'/' ] = $location;
        return $this;
    }

}

?>