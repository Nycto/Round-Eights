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
 * Applies a string transformation to values being saved in a session
 */
class Transform extends \h2o\Session\Decorator
{

    /**
     * The transformation to apply to values as the enter and leave this session
     *
     * @var \h2o\iface\Transform
     */
    private $transform;

    /**
     * Constructor...
     *
     * @param \h2o\iface\Transform $transform The transformation to apply to
     * 		values as the enter and leave this session
     * @param \h2o\iface\Session $decorated The object being decorated
     */
    public function __construct (
        \h2o\iface\Transform $transform,
        \h2o\iface\Session $decorated
    ) {
        parent::__construct( $decorated );
        $this->transform = $transform;
    }

    /**
     * Untransforms a value as it comes out of the decorated session
     *
     * @param String $input The input value to decode
     * @return Mixed
     */
    private function untransform ( $input )
    {
        if ( !is_string($input) )
            return NULL;

        $input = $this->transform->from( $input );

        if ( $input === "b:0;" )
            return FALSE;

        $input = @unserialize( $input );
        return $input === FALSE ? NULL : $input;
    }

    /**
     * Returns a value from the session
     *
     * @param String $key The key of the value to return
     * @return Mixed
     */
    public function get ( $key )
    {
        return $this->untransform( parent::get( $key ) );
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
        parent::set(
            $key,
            $this->transform->to(
                serialize( $value )
            )
        );
        return $this;
    }

    /**
     * Treats the key as an array and pushes a new value onto the end of it
     *
     * @param String $key The key to push on to
     * @param Mixed $value The value to push
     * @return \h2o\iface\Session Returns a self reference
     */
    public function push ( $key, $value )
    {
        if ( !$this->exists( $key ) )
            $current = array();
        else
            $current = $this->get( $key );

        if ( !is_array($current) )
            $current = array($current);

        $current[] = $value;

        $this->set( $key, $current );

        return $this;
    }

    /**
     * Treats the key as an array and pops value from the end of it
     *
     * @param String $key The key to pop a value off of
     * @return Mixed Returns the popped value
     */
    public function pop ( $key )
    {
        if ( !$this->exists($key) )
            return NULL;

        $current = $this->get( $key );

        if ( !is_array($current) ) {
            $result = $current;
            $this->clear( $key );
        }
        else {
            $result = array_pop( $current );

            if ( empty($current) )
                $this->clear( $key );
            else
                $this->set( $key, $current );
        }

        return $result;
    }

    /**
     * Returns all the values in the session
     *
     * @return Array
     */
    public function getAll ()
    {
        $result = parent::getAll();
        foreach ( $result AS $key => $value )
        {
            $result[ $key ] = $this->untransform( $value );
        }
        return $result;
    }

}

?>