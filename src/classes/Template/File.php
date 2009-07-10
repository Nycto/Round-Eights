<?php
/**
 * @license Artistic License 2.0
 *
 * This file is part of RaindropPHP.
 *
 * RaindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * RaindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with RaindropPHP. If not, see <http://www.RaindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@Raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Template
 */

namespace h2o\Template;

/**
 * The base class for templates that load a file to display the template
 */
abstract class File extends \h2o\Template
{

    /**
     * The global file finder class
     *
     * This will be used by file templates if no specific template is set for
     * the instance.
     *
     * @var \h2o\FileFinder
     */
    static protected $globalFinder;

    /**
     * The file finder to use for this instance
     *
     * If this is not set, the global finder will be used.
     *
     * Also note that if this file finder does not locate the file, the global
     * instance will NOT be called.
     *
     * @var \h2o\FileFinder
     */
    private $finder;

    /**
     * The file this template will render
     *
     * @var String
     */
    private $file;

    /**
     * Returns the global file finder
     *
     * @return \h2o\FileFinder|Null Returns the global \h2o\FileFinder object.
     *      Returns NULL if there is no global file finder.
     */
    static public function getGlobalFinder ()
    {
        return self::$globalFinder;
    }

    /**
     * Sets the global file finder
     *
     * @param \h2o\FileFinder $finder The new global file finder
     * @return NULL
     */
    static public function setGlobalFinder ( \h2o\FileFinder $finder )
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
     * Constructor allows you to immediately set the file, if you so desire
     *
     * @param mixed $file The file this tempalte should load
     */
    public function __construct ( $file = null )
    {
        if ( !\h2o\isVague($file) )
            $this->setFile( $file );
    }

    /**
     * Returns the file finder for this instance
     *
     * This does not look at the global instance
     *
     * @return \h2o\FileFinder|Null Returns a \h2o\FileFinder object. Returns
     *      NULL if there is no instance specific file finder.
     */
    public function getFinder ()
    {
        return $this->finder;
    }

    /**
     * Sets the file finder for this instance
     *
     * @param \h2o\FileFinder $finder The file finder to use
     * @return \h2o\Template\File Returns a self reference
     */
    public function setFinder ( \h2o\FileFinder $finder )
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
     * @return \h2o\Template\File Returns a self reference
     */
    public function clearFinder ()
    {
        $this->finder = null;
        return $this;
    }

    /**
     * Returns the file finder this instance will use
     *
     * If no finder is set for this instance, the global instance will be returned.
     * Then, if there is no global instance, an exception will be thrown
     *
     * @return \h2o\FileFinder Returns a \h2o\FileFinder object
     */
    public function selectFinder ()
    {
        if (isset($this->finder))
            return $this->finder;

        else if ( isset(self::$globalFinder) )
            return self::$globalFinder;

        throw new \h2o\Exception\Variable(
                "FileFinder",
                "No global or instance level FileFinder has been set"
            );
    }

    /**
     * Sets the file this template will load
     *
     * @param mixed $file The file to load
     * @return \h2o\Template\File Returns a self reference
     */
    public function setFile ( $file )
    {
        if ( $file instanceof \h2o\FileSys\File)
            $this->file = $file;
        else
            $this->file = new \h2o\FileSys\File( $file );

        return $this;
    }

    /**
     * Returns the file this template will load
     *
     * @return \h2o\FileSys\File|Null Returns a \h2o\FileSys\File object, or NULL
     *      if no file has been set.
     */
    public function getFile ()
    {
        return $this->file;
    }

    /**
     * Returns whether a file has been set in this instance
     *
     * @return Boolean
     */
    public function fileExists ()
    {
        return isset( $this->file );
    }

    /**
     * Clears the file from this instance
     *
     * @return \h2o\Template\File Returns a self reference
     */
    public function clearFile ()
    {
        $this->file = null;
        return $this;
    }

    /**
     * Finds the template file to load
     *
     * @return \h2o\FileSys\File Returns a \h2o\FileSys\File object
     */
    public function findFile ()
    {
        if ( !$this->fileExists() )
            throw new \h2o\Exception\Variable("File", "No file has been set in template");

        return $this->selectFinder()->find( $this->getFile() );
    }

}

?>