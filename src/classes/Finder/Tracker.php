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
 * @package Finder
 */

namespace r8\Finder;

/**
 * Tracks the files that are attempted to be found as the finder tree progresses
 */
class Tracker
{

    /**
     * The list of paths and roots that have been tested
     *
     * @var array
     */
    private $tested = array();

    /**
     * Returns the list of paths that have been tested so far
     *
     * @return Array An array of string
     */
    public function getTested ()
    {
        return $this->tested;
    }

    /**
     * Tests the given base/path pairing to determine if it is valid
     *
     * @param String $base The base directory to test
     * @param String $path The sub path to test
     * @return Boolean Returns whether the path is valid
     */
    public function test ( $base, $path )
    {
        if ( empty($path) )
            return FALSE;

        $full = rtrim( (string) $base, "/") ."/". trim( (string) $path, "/" );
        $full = \r8\FileSys::resolvePath( $full );

        if ( !in_array($full, $this->tested))
            $this->tested[] = $full;

        return is_file( $full );
    }

}

?>