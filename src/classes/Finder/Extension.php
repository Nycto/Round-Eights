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
 * Iterators over a list of file extensions, appends them to the path, and passes
 * the result off to another Finder to determine if it exists
 */
class Extension implements \r8\iface\Finder
{

    /**
     * The finder being wrapped
     *
     * @var \r8\iface\Finder
     */
    private $wrapped;

    /**
     * The list of sub-directories to test
     *
     * @var Array
     */
    private $exts = array();

    /**
     * Constructor...
     *
     * @param \r8\iface\Finder $wrapped The finder being wrapped
     * @param String $exts... Extensions to append during processing
     */
    public function __construct ( \r8\iface\Finder $wrapped )
    {
        $this->wrapped = $wrapped;

        if ( func_num_args() > 1 ) {
            $args = func_get_args();
            array_shift( $args );
            array_map( array($this, "addExt"), $args );
        }
    }

    /**
     * Returns the list of extensions registered in this instance
     *
     * @return Array An array of strings
     */
    public function getExts ()
    {
        return $this->exts;
    }

    /**
     * Adds a new extension to the list that will be appended
     *
     * @param String $ext
     * @return \r8\Finder\Extension Returns a self reference
     */
    public function addExt ( $ext )
    {
        $ext = ltrim( (string) $ext, "." );

        if ( !\r8\IsEmpty($ext) )
            $this->exts[] = $ext;

        return $this;
    }

    /**
     * Attempts to find a file given a relative path
     *
     * @param \r8\Finder\Tracker $tracker $file The tracker to use when determining
     *      if a base/path combination is valid
     * @param String $base The base directory to look for the path in
     * @param String $path The path being looked for
     * @return \r8\Finder\Result|NULL Returns a result, or NULL if the file
     *      couldn't be found
     */
    public function find ( \r8\Finder\Tracker $tracker, $base, $path )
    {
        if ( count($this->exts) == 0 )
            return $this->wrapped->find( $tracker, $base, $path );

        $path = rtrim( (string) $path, "." );

        foreach ( $this->exts AS $ext ) {
            $result = $this->wrapped->find( $tracker, $base, $path .".". $ext );
            if ( $result instanceof \r8\Finder\Result )
                return $result;
        }

        return NULL;
    }

}

