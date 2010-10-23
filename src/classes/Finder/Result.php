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
 * Represents the successful result of a Finder query
 */
class Result
{

    /**
     * The base of the path where the file was found
     *
     * @var String
     */
    private $base;

    /**
     * The location of the file relative to the base
     *
     * @var String
     */
    private $path;

    /**
     * The combined location of the file
     *
     * @var \r8\FileSys
     */
    private $file;

    /**
     * Constructor...
     *
     * @param String $base The base of the path where the file was found
     * @param String $path The location of the file relative to the base
     */
    public function __construct ( $base, $path )
    {
        $this->base = rtrim( (string) $base, "/" );
        if ( empty($this->base) )
            throw new \r8\Exception\Argument(0, "Base Path", "Must not be empty");

        $this->path = trim( (string) $path, "/" );
        if ( empty($this->path) )
            throw new \r8\Exception\Argument(0, "Sub-Path", "Must not be empty");
    }

    /**
     * Returns the Path of the found file relative to the base directory
     *
     * @return String
     */
    public function getPath ()
    {
        return $this->path;
    }

    /**
     * Returns the Base directory the file is located in
     *
     * @return String
     */
    public function getBase ()
    {
        return $this->base;
    }

    /**
     * Returns the file represented by this instance
     *
     * @return \r8\FileSys
     */
    public function getFile ()
    {
        if ( !isset($this->file) ) {
            $this->file = \r8\FileSys::create(
                $this->base ."/". $this->path
            );
            $this->file->resolve();
        }

        return clone $this->file;
    }

    /**
     * Returns the absolute path of the file represented by this instance
     *
     * @return String
     */
    public function getAbsolute ()
    {
        return $this->getFile()->getPath();
    }

}

