<?php
/**
 * @license Artistic License 2.0
 *
 * This file is part of raindropPHP.
 *
 * raindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * raindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with raindropPHP. If not, see <http://www.raindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Random
 */

namespace h2o\Random;

/**
 * Class for generating a seed value
 */
class Seed
{

    /**
     * The maximum integer value available
     *
     * This is equal to (2^31) - 1
     *
     * @var Integer
     */
    const MAX_INT = 2147483647;

    /**
     * The source value to generate the seed from
     *
     * @param String
     */
    private $source;

    /**
     * Returns a new, random seed
     *
     * @return \h2o\Random\Seed
     */
    static public function random ()
    {
        return new self( mt_rand() .":". microtime(TRUE) .":". uniqid() );
    }

    /**
     * Constructor...
     *
     * @param mixed $source The source value to generate the seed from. This will
     *      be converted to a string before it is used.
     */
    public function __construct ( $source )
    {
        $this->setSource( $source );
    }

    /**
     * Returns the source value
     *
     * @return String
     */
    public function getSource ()
    {
        return $this->source;
    }

    /**
     * Sets the source value to generate the seed from
     *
     * @param mixed $source The source value to generate the seed from. This will
     *      be converted to a string before it is used.
     * @return \h2o\Encrypt\Seed Returns a self reference
     */
    public function setSource( $source )
    {
        if ( !\h2o\isBasic($source) )
            $source = serialize($source);

        $this->source = strval($source);

        return $this;
    }

    /**
     * Returns an alpha-numeric representation of this seed
     *
     * @return String Returns a 40 character string containing digits and lower
     *      case letters.
     */
    public function getString ()
    {
        return sha1($this->source);
    }

    /**
     * Returns an integer representation of this seed
     *
     * Note that to be able to use this method, you must have the BC Math extension
     * installed. An exception will be thrown if it is not.
     *
     * @return Integer Returns an integer between zero and the value of the
     *      self::MAX_INT constant
     */
    public function getInteger ()
    {
        if ( !extension_loaded("bcmath") )
            throw new \h2o\Exception\Extension("BC Math", "BC Math extension is not loaded");

        $source = strtolower( $this->getString() );

        // Convert any non-digits to digits
        $source = strtr(
                $source,
                "abcdefghijklmnopqrstuvwxyz",
                "12345678901234567890123456"
            );

        // Integers can only be so big, so fit the source value into
        // the constraints of PHP
        return \intval( \bcmod($source, self::MAX_INT) );
    }

    /**
     * Returns a float representation of this seed between and including 0 and 1.
     *
     * Note that to be able to use this method, you must have the BC Math extension
     * installed. An exception will be thrown if it is not.
     *
     * @return Float A float value >= 0 and <= 1. This will have precision of up
     *      to 14 decimal places.
     */
    public function getFloat ()
    {
        return round( $this->getInteger() / self::MAX_INT, 14 );
    }

}

?>