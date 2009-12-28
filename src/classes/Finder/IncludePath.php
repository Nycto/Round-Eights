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
 * Uses the include paths configured in the php.ini file as the base dirs
 */
class IncludePath implements \r8\iface\Finder
{

    /**
     * The finder being wrapped
     *
     * @var \r8\iface\Finder
     */
    private $wrapped;

    /**
     * Constructor...
     *
     * @param \r8\iface\Finder $wrapped The finder being wrapped
     */
    public function __construct ( \r8\iface\Finder $wrapped )
    {
        $this->wrapped = $wrapped;
    }

    /**
     * Attempts to find a file given a relative path
     *
     * @param \r8\Finder\Tracker $tracker $file The tracker to use when determining
     *      if a base/path combination is valid
     * @param String $base The base directory to look for the path in
     * @param String $path The path being looked for
     * @return Boolean Returns whether the path was found
     */
    public function find ( \r8\Finder\Tracker $tracker, $base, $path )
    {
        $includePaths = explode(\PATH_SEPARATOR, ini_get("include_path"));

        foreach ( $includePaths AS $inc ) {
            if ( $this->wrapped->find($tracker, $inc, $path) )
                return TRUE;
        }

        return FALSE;
    }

}

?>