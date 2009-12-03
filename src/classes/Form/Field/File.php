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
 * @package Forms
 */

namespace r8\Form\Field;

/**
 * A form field used to upload files
 */
class File extends \r8\Form\Field
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
     * Returns a new FileUpload validator
     *
     * This has been added to make it easier to unit test. By mocking this class
     * and overwriting this method, you can make the rest of the methods think
     * that a file was uploaded
     *
     * @return Object A FileUpload validator
     */
    protected function getFileUploadValidator ()
    {
        return new \r8\Validator\FileUpload;
    }

    /**
     * Returns the temporary filename of the uploaded file
     *
     * @return mixed The raw value of this field
     */
    public function getRawValue ()
    {
        $files = $this->getUploadedFiles();

        if ( isset($files[ $this->getName() ]) )
            return $files[ $this->getName() ]['tmp_name'];

        return null;
    }

    /**
     * Applies the validator to the value in this instance and returns an
     * instance of Validator Results.
     *
     * This will apply the validator to the filtered value
     *
     * @return \r8\Validator\Results
     */
    public function validate ()
    {
        // Apply the FileUpload validator before anything else
        $result = $this->getFileUploadValidator()->validate( $this->getName() );

        // If it fails, don't even give the other validators a chance
        if ( !$result->isValid() )
            return $result;

        return parent::validate();
    }

    /**
     * Returns a \r8\HTML\Tag object that represents this instance
     *
     * @return \r8\HTML\Tag
     */
    public function getTag()
    {
        return parent::getTag()
            ->unsetAttr('value')
            ->setAttr("type", "file");
    }

}

?>