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
 * @package Validators
 */

namespace r8\Validator;

/**
 * Validates an uploaded file based on the field name
 */
class FileUpload extends \r8\Validator
{

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
        if ( !($field instanceof \r8\Input\File) )
            throw new \r8\Exception\Argument( 0, "Upload File", 'Must be an instance of \r8\Input\File' );

        if ( !$field->isValid() )
            return $field->getMessage();
    }

}

?>