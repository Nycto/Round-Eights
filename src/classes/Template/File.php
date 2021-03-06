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
abstract class File extends \r8\Template\Access implements \r8\iface\Template\Access
{

    /**
     * The file finder to use for this instance
     *
     * @var \r8\Finder
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
     * @param \r8\Finder $finder The file finder to use to find this file
     * @param Mixed $file The file this tempalte should load
     */
    public function __construct ( \r8\Finder $finder, $file )
    {
        $this->finder = $finder;
        $this->setFile( $file );
    }

    /**
     * Returns the file finder for this instance
     *
     * @return \r8\Finder|Null
     */
    public function getFinder ()
    {
        return $this->finder;
    }

    /**
     * Sets the file finder for this instance
     *
     * @param \r8\Finder $finder The file finder to use
     * @return \r8\Template\File Returns a self reference
     */
    public function setFinder ( \r8\Finder $finder )
    {
        $this->finder = $finder;
        return $this;
    }

    /**
     * Sets the file this template will load
     *
     * @param Mixed $file The file to load
     * @return \r8\Template\File Returns a self reference
     */
    public function setFile ( $file )
    {
        if ( $file instanceof \r8\FileSys\File )
            $file = $file->getPath();

        $this->file = (string) $file;

        return $this;
    }

    /**
     * Returns the relative, unresolved path of the file this template will load
     *
     * @return String
     */
    public function getFile ()
    {
        return $this->file;
    }

    /**
     * Finds the absolute path of the template file to load
     *
     * @throws \r8\Exception\Finder\Missing Thrown if the file can't be found
     * @return String Returns the fully resolved path of the template file
     */
    public function findPath ()
    {
        return $this->finder->findPath( $this->file, TRUE );
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

