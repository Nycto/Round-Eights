<?php
/**
 * @license Artistic License 2.0
 *
 * This file is part of RaindropPHP.
 *
 * RaindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * RaindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with RaindropPHP. If not, see <http://www.RaindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <James@RaindropPHP.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Random
 */

namespace h2o\Session;

/**
 * Provides blind access to a single session value without the consumer needing to
 * know the key or the destination session
 */
class Value
{

    /**
     * The key to reference within the session
     *
     * @var String
     */
    private $key;

    /**
     * The session to pull and push values to
     *
     * @var \h2o\iface\Session
     */
    private $session;

    /**
     * Constructor...
     *
     * @param String $key The key to reference within the session
     * @param \h2o\iface\Session $session The session to pull and push values to
     */
    public function __construct ( $key, \h2o\iface\Session $session )
    {
        $key = \h2o\indexVal( $key );

        if ( \h2o\IsEmpty($key) )
            throw new \h2o\Exception\Argument( 0, "key", "Must be a valid key" );

        $this->key = $key;
        $this->session = $session;
    }

    /**
     * Returns a value from the session
     *
     * @return Mixed
     */
    public function get ()
    {
    }

    /**
     * Sets a value in the session
     *
     * @param Mixed $value The value to save
     * @return \h2o\Session\Value Returns a self reference
     */
    public function set ( $value )
    {
        return $this;
    }

    /**
     * Returns whether this value has been set in the session
     *
     * @return Boolean
     */
    public function exists ()
    {
    }

    /**
     * Removes this specific value from the session
     *
     * @return \h2o\Session\Value Returns a self reference
     */
    public function clear ()
    {
        return $this;
    }

}

?>