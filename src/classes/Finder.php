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

namespace r8;

/**
 * The primary interface class for finding a file
 */
class Finder
{

    /**
     * The base directory to look in
     *
     * @var String
     */
    private $base;

    /**
     * The file finder to use for locating files
     *
     * @var \r8\iface\Finder
     */
    private $finder;

    /**
     * Constructor...
     *
     * @param String $base The initial base directory to use
     * @param \r8\iface\Finder $finder The modifier to use
     */
    public function __construct ( $base, \r8\iface\Finder $finder )
    {
        $base = rtrim( (string) $base, "/" );
        if ( empty($base) )
            throw new \r8\Exception\Argument( 0, "Base Directory", "Must not be empty" );
        $this->base = $base;

        $this->finder = $finder;
    }

    /**
     * Attempts to find the absolute path of a file given a relative path
     *
     * @param String $path The relative path of the file being looked for
     * @param Boolean $volatile If true, an exception will be thrown when a
     *      file is not found
     * @return \r8\Finder\Result|NULL Returns the found file. Returns NULL if the
     *      file could not be found
     */
    public function find ( $path, $volatile = FALSE )
    {
        $tracker = new \r8\Finder\Tracker;
        $result = $this->finder->find( $tracker, $this->base, $path );

        if ( !($result instanceof \r8\Finder\Result) ) {
            if ( $volatile )
                throw new \r8\Exception\Finder\Missing( $path, $tracker );
            return NULL;
        }

        return $result;
    }

    /**
     * Finds a file given a relative path
     *
     * This differs from the find method in that it skips over the result object
     * and directly returns the \r8\FileSys object
     *
     * @param String $path The relative path of the file being looked for
     * @param Boolean $volatile If true, an exception will be thrown when a
     *      file is not found
     * @return \r8\FileSys|NULL Returns the found file. Returns NULL if the
     *      file could not be found
     */
    public function findFile ( $path, $volatile = FALSE )
    {
        $result = $this->find( $path, $volatile );
        return $result ? $result->getFile() : NULL;
    }

}

?>