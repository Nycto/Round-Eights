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
 * @package Cache
 */

namespace r8\iface;

/**
 * An interface for accessing the session
 */
interface Session
{

    /**
     * Returns a value from the session
     *
     * @param String $key The key of the value to return
     * @return Mixed
     */
    public function get ( $key );

    /**
     * Sets a value in the session
     *
     * @param String $key The key to set
     * @param Mixed $value The value to save
     * @return \r8\iface\Session Returns a self reference
     */
    public function set ( $key, $value );

    /**
     * Returns whether a value has been set in the session
     *
     * @param String $key The key to test
     * @return Boolean
     */
    public function exists ( $key );

    /**
     * Removes a specific value from the session
     *
     * @param String $key The key to remove
     * @return \r8\iface\Session Returns a self reference
     */
    public function clear ( $key );

    /**
     * Treats the key as an array and pushes a new value onto the end of it
     *
     * @param String $key The key to push on to
     * @param Mixed $value The value to push
     * @return \r8\iface\Session Returns a self reference
     */
    public function push ( $key, $value );

    /**
     * Treats the key as an array and pops value from the end of it
     *
     * @param String $key The key to pop a value off of
     * @return Mixed Returns the popped value
     */
    public function pop ( $key );

    /**
     * Removes all values from the session
     *
     * @return \r8\iface\Session Returns a self reference
     */
    public function clearAll ();

    /**
     * Returns all the values in the session
     *
     * @return Array
     */
    public function getAll ();

}

