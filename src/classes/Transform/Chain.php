<?php
/**
 * Encodes a string using base 64
 *
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
 * @package Encoding
 */

namespace r8\Transform;

/**
 * Combines multiple Transform objects into a single point
 */
class Chain implements \r8\iface\Transform
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
     * @param \r8\iface\Transform $args... Any transforms to add to the chain
     */
    public function __construct ()
    {
        if ( func_num_args() <= 0 )
        return;

        foreach( func_get_args() AS $arg ) {
            if ( $arg instanceof \r8\iface\Transform )
            $this->addTransform( $arg );
        }
    }

    /**
     * Returns the Transforms in this instance
     *
     * @return array An array of \r8\iface\Transform Objects
     */
    public function getTransforms ()
    {
        return $this->transforms;
    }

    /**
     * Adds a transform to this instance
     *
     * @return \r8\Transform\Chain Returns a self reference
     */
    public function addTransform ( \r8\iface\Transform $transform )
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