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

}

?>