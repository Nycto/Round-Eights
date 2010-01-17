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
 * An uploaded file
 */
class File
{

    /**
     * The original name of the file on the client machine
     *
     * @var String
     */
    private $name;

    /**
     * The temporary file the upload was saved to
     *
     * @var \r8\FileSys\File
     */
    private $file;

    /**
     * The error code associated with this file
     *
     * @var Integer
     */
    private $code;

    /**
     * The mime type of this file
     *
     * @var String
     */
    private $mime;

    /**
     * The size of this file
     *
     * @var Integer
     */
    private $size;

    /**
     * Builds a new instance of this object from an array
     *
     * @param array $input The source data
     * @return \r8\Input\File|Array This could return an array of \r8\Input\File
     *      objects if the input array is multi-dimensional.
     */
    static public function fromArray ( array $input )
    {
        $fields = array( 'name', 'tmp_name', 'error' );

        // Narrow the array to only what is needed
        $input = \r8\ary\hone( $input, $fields );

        // Check for missing fields
        $missing = array_diff( $fields, array_keys($input) );
        if ( count($missing) > 0 )
            throw new \r8\Exception\Argument(0, "Input Array", "The following indexes are required and missing: ". implode(", ", $missing));

        // If they only gave us a single file,
        if ( !is_array($input['name']) ) {
            return new self(
                $input['name'],
                $input['error'],
                new \r8\FileSys\File( $input['tmp_name'] )
            );
        }

        // Handle multiple files passed under one file name
        $result = array();
        foreach ( $input['name'] AS $key => $value ) {
            if ( isset($input['error'][$key]) && isset($input['tmp_name'][$key]) ) {
                $result[$key] = new self(
                    $input['name'][$key],
                    $input['error'][$key],
                    new \r8\FileSys\File( $input['tmp_name'][$key] )
                );
            }
        }
        return $result;
    }

    /**
     * Constructor...
     *
     * @param String $name The original name of the file on the client machine
     * @param Integer $code The error code associated with this file
     * @param \r8\FileSys\File $file $file The temporary file the upload was saved to
     */
    public function __construct ( $name, $code, \r8\FileSys\File $file )
    {
        $file->requirePath();
        $this->file = $file;

        $name = trim( (string) $name );
        if ( \r8\IsEmpty($name) )
            throw new \r8\Exception\Argument( 0, "File Name", "Must not be empty" );

        $this->name = $name;
        $this->code = max( (int) $code, 0 );
    }

    /**
     * Returns the original name of the file on the client machine
     *
     * @return String
     */
    public function getName ()
    {
        return $this->name;
    }

    /**
     * Returns the error code associated with this file upload
     *
     * @return Integer
     */
    public function getCode ()
    {
        return $this->code;
    }

    /**
     * Returns the temporary file the upload was saved to
     *
     * @return \r8\FileSys\File
     */
    public function getFile ()
    {
        return $this->file;
    }

    /**
     * Returns the size of this file
     *
     * @return Integer
     */
    public function getSize ()
    {
        if ( !isset($this->size) )
            $this->size = $this->file->getSize();

        return $this->size;
    }

    /**
     * Returns the mime type of this file
     *
     * @return String
     */
    public function getMimeType ()
    {
        if ( !isset($this->mime) )
            $this->mime = $this->file->getMimeType();

        return $this->mime;
    }

    /**
     * Returns whether this file is actually an uploaded file
     *
     * @return Boolean
     */
    public function isUploadedFile ()
    {
        return \is_uploaded_file( $this->file->getPath() );
    }

    /**
     * Returns whether this file is readable
     *
     * @return Boolean
     */
    public function isReadable ()
    {
        return $this->file->isReadable();
    }

    /**
     * Returns a whether this is a valid file upload
     *
     * @return Boolean
     */
    public function isValid ()
    {
        return $this->code == UPLOAD_ERR_OK
            && $this->isUploadedFile()
            && $this->isReadable()
            && $this->getSize() > 0;
    }

    /**
     * If this uploaded file isn't valid, this will return the error message
     *
     * @return String Returns NULL if no error was encountered
     */
    public function getMessage ()
    {
        // Handle any explicit errors that PHP gives us
        if ( $this->code != UPLOAD_ERR_OK ) {
            $map = array(
                UPLOAD_ERR_INI_SIZE => "File exceeds the server's maximum allowed size",
                UPLOAD_ERR_FORM_SIZE => "File exceeds the maximum allowed size",
                UPLOAD_ERR_PARTIAL => "File was only partially uploaded",
                UPLOAD_ERR_NO_FILE => "No file was uploaded",
                UPLOAD_ERR_NO_TMP_DIR => "No temporary directory was defined on the server",
                UPLOAD_ERR_CANT_WRITE => "Unable to write the uploaded file to the server",
                UPLOAD_ERR_EXTENSION => "A PHP extension has restricted this upload"
            );

            if ( isset($map[$this->code]) )
                return $map[$this->code];
            else
                return "An unknown error occurred";
        }

        if ( !$this->isUploadedFile() )
            return "Upload validation failed";

        if ( !$this->isReadable() )
            return "Uploaded file is not readable";

        if ( $this->getSize() == 0 )
            return "Uploaded file is empty";

        return NULL;
    }

}

?>