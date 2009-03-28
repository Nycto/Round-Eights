<?php
/**
 * Encryption seed class
 *
 * @license Artistic License 2.0
 *
 * This file is part of commonPHP.
 *
 * commonPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * commonPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with commonPHP. If not, see <http://www.commonphp.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@commonphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Encrypt
 */

namespace cPHP\Encrypt;

/**
 * Class for generating a seed value
 */
class Seed
{

    /**
     * The source value to generate the seed from
     *
     * @param String
     */
    private $source;

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
     * @return cPHP\Encrypt\Seed Returns a self reference
     */
    public function setSource( $source )
    {
        if ( !\cPHP\isBasic($source) )
            $source = serialize($source);

        $this->source = strval($source);

        return $this;
    }

    /**
     * Returns an alpha-numeric representation of this seed
     *
     * @return String
     */
    public function getString ()
    {
        return sha1($this->source);
    }

    /**
     * Returns an integer representation of this seed
     *
     * @return Integer
     */
    public function getInteger ()
    {
        $source = strtolower( $this->getString() );

        // Convert any non-digits to digits
        $source = strtr(
                $source,
                "abcdefghijklmnopqrstuvwxyz",
                "12345678901234567890123456"
            );

        // Integers can only be so big, so fit the source value into
        // the constraints of PHP
        return intval( bcmod($source, PHP_INT_MAX) );
    }

}