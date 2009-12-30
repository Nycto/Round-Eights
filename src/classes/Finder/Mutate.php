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
 * Replaces a directory structure within the find path with a different value
 */
class Mutate implements \r8\iface\Finder
{

    /**
     * The finder being wrapped
     *
     * @var \r8\iface\Finder
     */
    private $wrapped;

    /**
     * The list of mutations
     *
     * @var Array
     */
    private $mutations = array();

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
     * Returns the list of mutations registered in this instance
     *
     * @return Array
     */
    public function getMutations ()
    {
        return $this->mutations;
    }

    /**
     * Adds a new mutation to this instance
     *
     * @param String $from The structure to replace. This value must
     * @param String $to The replacement structure
     * @return \r8\Finder\Mutation Returns a self reference
     */
    public function addMutation ( $from, $to )
    {
        $from = trim( (string) $from, "/" );
        $from = \r8\FileSys::resolvePath( $from );

        $to = trim( (string) $to, "/" );

        if ( !\r8\IsEmpty($from) && $from != $to )
            $this->mutations[] = array( "from" => $from, "to" => $to );

        return $this;
    }

    /**
     * Attempts to find a file given a relative path
     *
     * @param \r8\Finder\Tracker $tracker $file The tracker to use when determining
     *      if a base/path combination is valid
     * @param String $base The base directory to look for the path in
     * @param String $path The path being looked for
     * @return \r8\Finder\Result|NULL Returns a result, or NULL if the file couldn't be found
     */
    public function find ( \r8\Finder\Tracker $tracker, $base, $path )
    {
        $origPath = $path;

        $path = trim( (string) $path, "/" );
        $path = \r8\FileSys::resolvePath( $path );

        // Iterate over each possible mutation and determine if it should be applied
        foreach ( $this->mutations AS $mutate ) {

            $result = NULL;

            // Check for a partial match
            if ( \r8\str\startsWith( $path, $mutate["from"] ."/" ) ) {
                $result = $this->wrapped->find(
                    $tracker,
                    $base,
                    $mutate["to"] ."/"
                        .ltrim( substr( $path, strlen( $mutate["from"] ) ), "/" )
                );
            }

            // Check for a full match
            else if ( strcasecmp($path, $mutate["from"]) == 0 ) {
                $result = $this->wrapped->find(
                    $tracker,
                    $base,
                    $mutate["to"]
                );
            }

            if ( $result instanceof \r8\Finder\Result )
                return $result;
        }

        return $this->wrapped->find( $tracker, $base, $origPath );
    }

}

?>