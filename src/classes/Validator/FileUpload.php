<?php
/**
 * @license Artistic License 2.0
 *
 * This file is part of raindropPHP.
 *
 * raindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * raindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with raindropPHP. If not, see <http://www.raindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Validators
 */

namespace h2o\Validator;

/**
 * Validates an uploaded file based on the field name
 */
class FileUpload extends \h2o\Validator
{

    /**
     * Returns the value of the $_FILE variable
     *
     * This has been added to make it easier to unit test. By mocking this class
     * and overwriting this method, you can make the rest of the methods think
     * that a file was uploaded
     *
     * @return Array
     */
    protected function getUploadedFiles ()
    {
        return $_FILES;
    }

    /**
     * Wrapper for the is_uploaded_file method
     *
     * This has been added to make it easier to unit test. By mocking this class
     * and overwriting this method, you can make the rest of the methods think
     * that a file was uploaded
     *
     * @return Array
     */
    protected function isUploadedFile ( $file )
    {
        return is_uploaded_file( $file );
    }

    /**
     * Validates an uploaded file based on the field name
     *
     * @param String $field The name of the file upload field being validated
     *      This is NOT the name of the file. This is the index that appears
     *      in the $_FILES global array
     * @return String|NULL Any errors encountered
     */
    protected function process ( $field )
    {
        $field = \h2o\Filter::Variable()->filter( $field );

        if ( !\h2o\Validator::Variable()->isValid( $field ) )
            throw new \h2o\Exception\Argument( 0, "Field Name", "Must be a valid PHP variable name" );

        $files = $this->getUploadedFiles();

        if ( !isset($files[$field]) )
            return "No file was uploaded";

        // Handle any explicit errors that PHP gives us
        switch ( $files[$field]['error']) {

            case 0:
                break;

            case UPLOAD_ERR_INI_SIZE:
                return "File exceeds the server's maximum allowed size";

            case UPLOAD_ERR_FORM_SIZE:
                return "File exceeds the maximum allowed size";

            case UPLOAD_ERR_PARTIAL:
                return "File was only partially uploaded";

            case UPLOAD_ERR_NO_FILE:
                return "No file was uploaded";

            case UPLOAD_ERR_NO_TMP_DIR:
                return "No temporary directory was defined on the server";

            case UPLOAD_ERR_CANT_WRITE:
                return "Unable to write the uploaded file to the server";

            case UPLOAD_ERR_EXTENSION:
                return "A PHP extension has restricted this upload";

            default:
                return "An unknown error occured";

        }

        if (!$this->isUploadedFile($files[$field]['tmp_name']))
            return "File is restricted";

        if ( @filesize($files[$field]['tmp_name']) == 0 )
            return "Uploaded file is empty";

        if ( !is_readable($files[$field]['tmp_name']) )
            return "Uploaded file is not readable";
    }

}

?>