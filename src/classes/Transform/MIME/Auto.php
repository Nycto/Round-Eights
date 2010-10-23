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
 * @package Transform
 */

namespace r8\Transform\MIME;

/**
 * Attempts to determine the best MIME encoding format to use for a value
 * and applies it
 */
class Auto extends \r8\Transform\MIME
{

    /**
     * Internal method for applying the settings from this MIME encoder to another
     * MIME encoder
     *
     * @param \r8\Transform\MIME $dest The encoder to apply the settings to
     * @return \r8\Transform\MIME Returns the input object
     */
    public function apply ( \r8\Transform\MIME $dest )
    {
        $dest->setLineLength( $this->getLineLength() );
        $dest->setHeader( $this->getHeader() );
        $dest->setInputEncoding( $this->getInputEncoding() );
        $dest->setOutputEncoding( $this->getOutputEncoding() );
        $dest->setEOL( $this->getEOL() );
        return $dest;
    }

    /**
     * Encodes a string
     *
     * @param mixed $value The value to encode
     * @return mixed The result of the encoding process
     */
    public function to ( $string )
    {
        // If we can raw encode, always select that option
        if ( \r8\Transform\MIME\Raw::canEncode($string) )
        {
            $encode = $this->apply( new \r8\Transform\MIME\Raw );
            return $encode->to( $string );
        }

        $bEncode = $this->apply( new \r8\Transform\MIME\B );
        $qEncode = $this->apply( new \r8\Transform\MIME\Q );

        $bEncoded = $bEncode->to( $string );
        $qEncoded = $qEncode->to( $string );

        if ( strlen($bEncoded) <= strlen($qEncoded) )
            return $bEncoded;
        else
            return $qEncoded;
    }

}

