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
 * @package Template
 */

namespace r8\Template;

/**
 * The base class for templates that load a file to display the template
 */
abstract class File extends \r8\Template
{

    /**
     * The file finder to use for this instance
     *
     * @var \r8\FileFinder
     */
    private $finder;

    /**
     * The file this template will render
     *
     * @var String
     */
    private $file;

    /**
     * Constructor allows you to immediately set the file, if you so desire
     *
     * @param \r8\FileFinder $finder The file finder to use to find this file
     * @param mixed $file The file this tempalte should load
     */
    public function __construct ( \r8\FileFinder $finder, $file )
    {
        $this->finder = $finder;
        $this->setFile( $file );
    }

    /**
     * Returns the file finder for this instance
     *
     * @return \r8\FileFinder|Null
     */
    public function getFinder ()
    {
        return $this->finder;
    }

    /**
     * Sets the file finder for this instance
     *
     * @param \r8\FileFinder $finder The file finder to use
     * @return \r8\Template\File Returns a self reference
     */
    public function setFinder ( \r8\FileFinder $finder )
    {
        $this->finder = $finder;
        return $this;
    }

    /**
     * Sets the file this template will load
     *
     * @param mixed $file The file to load
     * @return \r8\Template\File Returns a self reference
     */
    public function setFile ( $file )
    {
        if ( $file instanceof \r8\FileSys\File)
            $this->file = $file;
        else
            $this->file = new \r8\FileSys\File( $file );

        return $this;
    }

    /**
     * Returns the file this template will load
     *
     * @return \r8\FileSys\File|Null Returns a \r8\FileSys\File object, or NULL
     *      if no file has been set.
     */
    public function getFile ()
    {
        return $this->file;
    }

    /**
     * Finds the template file to load
     *
     * @return \r8\FileSys\File Returns a \r8\FileSys\File object
     */
    public function findFile ()
    {
        return $this->finder->find( $this->getFile() );
    }

    /**
     * Renders the template and returns it as a string
     *
     * @return String Returns the rendered template as a string
     */
    public function render ()
    {
        ob_start();
        $this->display();
        return ob_get_clean();
    }

    /**
     * Renders the template and returns it as a string
     *
     * @return String
     */
    public function __toString ()
    {
        return $this->render();
    }

}

?>