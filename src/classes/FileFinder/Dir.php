<?php
/**
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
 * Class used to locate a file within a directory
 */
class Dir extends \cPHP\FileFinder\DirList
{

    /**
     * The array of registered directories
     */
    private $dirs = array();

    /**
     * Returns a list of directories to be searched
     *
     * @return Object Returns a cPHP\Ary object of directories
     */
    public function getDirs ()
    {
        return $this->dirs;
    }

    /**
     * Adds a directory to this instance
     *
     * @param String|Object $dir The directory to add
     * @return Object Returns a self reference
     */
    public function addDir ( $dir )
    {
        if ( !( $dir instanceof \cPHP\FileSys\Dir ) )
            $dir = new \cPHP\FileSys\Dir( $dir );
        $this->dirs[] = $dir;
        return $this;
    }

    /**
     * Clears all the directories from this instance
     *
     * @return Object Returns a self reference
     */
    public function clearDirs ()
    {
        $this->dirs = array();
        return $this;
    }

}

?>