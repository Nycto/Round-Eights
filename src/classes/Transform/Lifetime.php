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
 * @package Transform
 */

namespace r8\Transform;

/**
 * Limits the validity of a chunk of encoded data by prepending a timestamp
 * that marks when the data was encoded.
 */
class Lifetime implements \r8\iface\Transform
{

    /**
     * The length of time, in seconds, a chunk of transformed data is valid
     *
     * @var Integer
     */
    private $lifetime;

    /**
     * Constructor...
     *
     * @param Integer $lifetime The length of time, in seconds, a chunk of
     * 		transformed data is valid
     */
    public function __construct ( $lifetime )
    {
        $this->lifetime = max( (int) $lifetime, 1 );
    }

    /**
     * Transforms a string
     *
     * @param mixed $value The value to transform
     * @return mixed The result of the transformation process
     */
    public function to ( $string )
    {
        return base_convert( time(), 10, 36 ) .":". $string;
    }

    /**
     * Reverses the transformation on a string
     *
     * @param mixed $value The value to reverse transform
     * @return mixed The original, untransformed value
     */
    public function from ( $string )
    {
        if ( !\preg_match('/^[0-9a-z]+:/', $string) )
            throw new \r8\Exception\Data( $string, "Transform String", "Data does not contain a timestamp" );

        $pos = \strpos($string, ":");

        $timestamp = substr( $string, 0, $pos );
        $timestamp = (float) base_convert( $timestamp, 36, 10 );

        $delta = time() - $timestamp;

        if ( $delta < 0 )
            throw new \r8\Exception\Data( $string, "Transform String", "Timestamp is in the future" );

        if ( $delta > $this->lifetime )
            throw new \r8\Exception\Data( $string, "Transform String", "Data has expired" );

        return substr( $string, $pos + 1 );
    }

}

?>