<?php
/**
 * Encodes a string using base 64
 *
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
 * @package Encoding
 */

namespace h2o\Transform;

/**
 * Combines multiple Transform objects into a single point
 */
class Chain implements \h2o\iface\Transform
{

    /**
     * The list of transform objects to apply
     *
     * @var Array
     */
    private $transforms = array();

    /**
     * Constructor...
     *
     * @param \h2o\iface\Transform $args... Any transforms to add to the chain
     */
    public function __construct ()
    {
        if ( func_num_args() <= 0 )
        return;

        foreach( func_get_args() AS $arg ) {
            if ( $arg instanceof \h2o\iface\Transform )
            $this->addTransform( $arg );
        }
    }

    /**
     * Returns the Transforms in this instance
     *
     * @return array An array of \h2o\iface\Transform Objects
     */
    public function getTransforms ()
    {
        return $this->transforms;
    }

    /**
     * Adds a transform to this instance
     *
     * @return \h2o\Transform\Chain Returns a self reference
     */
    public function addTransform ( \h2o\iface\Transform $transform )
    {
        $this->transforms[] = $transform;
        return $this;
    }

    /**
     * Encodes a string
     *
     * @param mixed $value The value to encode
     * @return mixed The result of the encoding process
     */
    public function to ( $string )
    {
        $string = (string) $string;

        foreach ( $this->transforms AS $trans ) {
            $string = $trans->to( $string );
        }

        return $string;
    }

    /**
     * Decodes an encoded string
     *
     * @param mixed $value The value to decode
     * @return mixed The original, unencoded value
     */
    public function from ( $string )
    {
        $string = (string) $string;

        // Walk the chain of transforms backwards
        end( $this->transforms );
        while ( $trans = current($this->transforms) ) {
            $string = $trans->from( $string );
            prev( $this->transforms );
        }

        return $string;
    }

}

?>