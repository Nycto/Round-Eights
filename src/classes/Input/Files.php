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
 * @package Input
 */

namespace r8\Input;

/**
 * A collection of uploaded files
 */
class Files
{

    /**
     * The list of files registered in this list
     *
     * @var Array
     */
    private $files = array();

    /**
     * Builds a new instance of this object from an array
     *
     * This is directly geared toward interpreting the $_FILES array
     *
     * @param array $input The source data
     * @return \r8\Input\Files
     */
    static public function fromArray ( array $input )
    {
        $result = array();

        foreach ( $input AS $key => $value )
        {
            if ( is_array($value) )
                $result[$key] = \r8\Input\File::fromArray($value);
        }

        return new self( $result );
    }

    /**
     * Constructor...
     *
     * @param Array $fileList The array of files to register, indexed
     * 		by their field name
     */
    public function __construct ( array $fileList = array() )
    {
        foreach ( $fileList AS $key => $file ) {

            if ( $file instanceof \r8\Input\File ) {
                $this->files[$key] = $file;
            }

            else if ( is_array($file) ) {

                $file = array_filter(
                    $file,
                    new \r8\Curry\Call( "is_a", '\r8\Input\File' )
                );

                if ( !empty($file) )
                    $this->files[$key] = array_values( $file );
            }

        }
    }

    /**
     * Returns the list of registered files
     *
     * @return Array
     */
    public function getAllFiles ()
    {
        return $this->files;
    }

    /**
     * Returns a file from the registry
     *
     * Even if an index contains a list of fields, the first file
     * of the array will be returned
     *
     * @param String $index The field index of the file to return
     * @return \r8\Input\File Returns NULL if the index doesn't exist
     */
    public function getFile ( $index )
    {
        if ( !isset($this->files[$index]) )
            return NULL;

        else if ( is_array($this->files[$index]) )
            return reset($this->files[$index]);

        else
            return $this->files[$index];
    }

    /**
     * Returns the list of files registered under the given index
     *
     * Even if a file isn't indexed as a list, it will be cast as
     * a single value array and returned.
     *
     * @param String $index The index of the file list to return
     * @return Array Returns an array of \r8\Input\File objects
     */
    public function getFileList ( $index )
    {
        if ( !isset($this->files[$index]) )
            return array();

        else if ( is_array($this->files[$index]) )
            return $this->files[$index];

        else
            return array( $this->files[$index] );
    }

    /**
     * Returns whether a file exists in this list
     *
     * @param String $index The index of the file list to check
     * @return Boolean
     */
    public function fileExists ( $index )
    {
        return isset($this->files[$index]);
    }

}

?>