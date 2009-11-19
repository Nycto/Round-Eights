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
 * @package FileFinder
 */

namespace r8\FileFinder;

/**
 * Base class for locating a file within a director
 */
abstract class DirList extends \r8\FileFinder
{

    /**
     * Returns a list of directories to be searched
     *
     * @return array Returns a list of directories
     */
    abstract public function getDirs ();

    /**
     * Internal method that actual searches this instance for the file
     *
     * @param String $file The file being looked for
     * @return String|False This should return FALSE if the file couldn't be
     *      found, or the path if it was.
     */
    protected function internalFind ( $file )
    {
        foreach ( $this->getDirs() AS $scan ) {

            if ( !($scan instanceof \r8\FileSys\Dir) )
                $scan = new \r8\FileSys\Dir( $scan );

            $scan = $scan->getSubPath( $file );

            if ( $scan->exists() )
                return $scan;

        }

        return FALSE;
    }

}

?>