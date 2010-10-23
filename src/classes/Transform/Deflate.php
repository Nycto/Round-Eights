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

namespace r8\Transform;

/**
 * Compresses a string using the gzDeflate algorithm
 */
class Deflate implements \r8\iface\Transform\Compress
{

    /**
     * The compression level
     *
     * @var Integer
     */
    private $level;

    /**
     * Constructor...
     *
     * @param Integer $level Integer The compression level. 0 is no compression,
     *      9 is full compression
     */
    public function __construct ( $level = 7 )
    {
        $this->level = max( min( (int) $level, 9), 0 );
    }

    /**
     * Compresses a string
     *
     * @param mixed $value The value to compress
     * @return mixed The result of the compression process
     */
    public function to ( $string )
    {
        return gzDeflate( (string) $string, $this->level );
    }

    /**
     * Decodes an compressed string
     *
     * @param mixed $value The value to compress
     * @return mixed The original, uncompressed value
     */
    public function from ( $string )
    {
        return gzInflate( (string) $string );
    }

}

