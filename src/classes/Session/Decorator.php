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
 * A decorator class which provides pass-through functionality
 */
abstract class Decorator implements \h2o\iface\Session
{

    /**
     * The object being decorated
     *
     * @var \h2o\iface\Session
     */
    private $decorated;

    /**
     * Constructor...
     *
     * @param \h2o\iface\Session $decorated The object being decorated
     */
    public function __construct ( \h2o\iface\Session $decorated )
    {
        $this->decorated = $decorated;
    }

    /**
     * Returns a value from the session
     *
     * @param String $key The key of the value to return
     * @return Mixed
     */
    public function get ( $key )
    {
        return $this->decorated->get( $key );
    }

    /**
     * Sets a value in the session
     *
     * @param String $key The key to set
     * @param Mixed $value The value to save
     * @return \h2o\iface\Session Returns a self reference
     */
    public function set ( $key, $value )
    {
        $this->decorated->set( $key, $value );
        return $this;
    }

    /**
     * Returns whether a value has been set in the session
     *
     * @param String $key The key to test
     * @return Boolean
     */
    public function exists ( $key )
    {
        return $this->decorated->exists( $key );
    }

    /**
     * Removes a specific value from the session
     *
     * @param String $key The key to remove
     * @return \h2o\iface\Session Returns a self reference
     */
    public function clear ( $key )
    {
        $this->decorated->clear( $key );
        return $this;
    }

    /**
     * Removes all values from the session
     *
     * @return \h2o\iface\Session Returns a self reference
     */
    public function clearAll ()
    {
        $this->decorated->clearAll();
        return $this;
    }

    /**
     * Returns all the values in the session
     *
     * @return Array
     */
    public function getAll ()
    {
        return $this->decorated->getAll();
    }

}

?>