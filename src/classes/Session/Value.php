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
 * @package Random
 */

namespace r8\Session;

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
     * @var \r8\iface\Session
     */
    private $session;

    /**
     * Constructor...
     *
     * @param String $key The key to reference within the session
     * @param \r8\iface\Session $session The session to pull and push values to
     */
    public function __construct ( $key, \r8\iface\Session $session )
    {
        $key = \r8\indexVal( $key );

        if ( \r8\IsEmpty($key) )
            throw new \r8\Exception\Argument( 0, "key", "Must be a valid key" );

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
        return $this->session->get( $this->key );
    }

    /**
     * Sets a value in the session
     *
     * @param Mixed $value The value to save
     * @return \r8\Session\Value Returns a self reference
     */
    public function set ( $value )
    {
        $this->session->set( $this->key, $value );
        return $this;
    }

    /**
     * Returns whether this value has been set in the session
     *
     * @return Boolean
     */
    public function exists ()
    {
        return $this->session->exists( $this->key );
    }

    /**
     * Removes this specific value from the session
     *
     * @return \r8\Session\Value Returns a self reference
     */
    public function clear ()
    {
        $this->session->clear( $this->key );
        return $this;
    }

    /**
     * Treats the session value as an array and pushes a new value onto the end of it
     *
     * @param Mixed $value The value to push
     * @return \r8\Session\Value Returns a self reference
     */
    public function push ( $value )
    {
        $this->session->push( $this->key, $value );
        return $this;
    }

    /**
     * Treats the session value as an array and pops value from the end of it
     *
     * @return Mixed Returns the popped value
     */
    public function pop ()
    {
        return $this->session->pop( $this->key );
    }

}

?>