<?php
/**
 * Core File Template Class
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

namespace cPHP\Template;

/**
 * The base class for templates that load a file to display the template
 */
abstract class File extends \cPHP\Template
{

    /**
     * The global file finder class
     *
     * This will be used by file templates if no specific template is set for
     * the instance.
     */
    static protected $globalFinder;

    /**
     * The file finder to use for this instance
     *
     * If this is not set, the global finder will be used.
     *
     * Also note that if this file finder does not locate the file, the global
     * instance will NOT be called.
     */
    private $finder;

    /**
     * Returns the global file finder
     *
     * @return Object|Null Returns the global cPHP\FileFinder object. Returns
     *      NULL if there is no global file finder.
     */
    static public function getGlobalFinder ()
    {
        return self::$globalFinder;
    }

    /**
     * Sets the global file finder
     *
     * @param Object $finder The new global file finder
     * @return NULL
     */
    static public function setGlobalFinder ( \cPHP\FileFinder $finder )
    {
        self::$globalFinder = $finder;
    }

    /**
     * Clears the global file finder
     *
     * @return null
     */
    static public function clearGlobalFinder ()
    {
        self::$globalFinder = null;
    }

    /**
     * Returns whether a global file finder has been set
     *
     * @return Boolean
     */
    static public function globalFinderExists ()
    {
        return isset( self::$globalFinder );
    }

    /**
     * Returns the file finder for this instance
     *
     * This does not look at the global instance
     *
     * @return Object|Null Returns a cPHP\FileFinder object. Returns NULL if there
     *      is no instance specific file finder.
     */
    public function getFinder ()
    {
        return $this->finder;
    }

    /**
     * Sets the file finder for this instance
     *
     * @param Object $finder The file finder to use
     * @return Object Returns a self reference
     */
    public function setFinder ( \cPHP\FileFinder $finder )
    {
        $this->finder = $finder;
        return $this;
    }

    /**
     * Returns whether this instance has a specific finder set. This does NOT
     * detect whether a global finder has been set.
     *
     * @return Boolean
     */
    public function finderExists ()
    {
        return isset( $this->finder );
    }

    /**
     * Clears the finder from this instance.
     *
     * This will NOT affect the global finder.
     *
     * @return Object Returns a self reference
     */
    public function clearFinder ()
    {
        $this->finder = null;
        return $this;
    }

}

?>