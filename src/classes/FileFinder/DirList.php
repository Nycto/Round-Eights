<?php
/**
 * Directory File Finder Base Class
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
 * @package FileFinder
 */

namespace cPHP\FileFinder;

/**
 * Base class for locating a file within a director
 */
abstract class DirList extends \cPHP\FileFinder
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

            if ( !($scan instanceof \cPHP\FileSys\Dir) )
                $scan = new \cPHP\FileSys\Dir( $scan );

            $scan = $scan->getSubPath( $file );

            if ( $scan->exists() )
                return $scan;

        }

        return FALSE;
    }

}

?>