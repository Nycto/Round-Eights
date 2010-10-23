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
 * @package Forms
 */

namespace r8\Form;

/**
 * A form field used to upload files
 */
class File extends \r8\Form\Field
{

    /**
     * The validator to use
     *      for checking the uploaded file.
     *
     * @var \r8\Validator\FileUpload
     */
    private $validator;

    /**
     * The list of uploaded files to pull this fields value from
     *
     * @var \r8\Input\files
     */
    private $files;

    /**
     * Constructor...
     *
     * @param String $name The name of this form field
     * @param String|NULL $label The label that describes this input field
     * @param \r8\Validator\FileUpload $validator The validator to use
     *      for checking the uploaded file. If left empty, a default
     *      instance will be created
     * @param \r8\Input\Files $files The list of uploaded files to pull
     *      this fields value from. If left empty, the File list from
     *      the global Request will be used
     */
    public function __construct(
        $name,
        $label = null,
        \r8\Validator\FileUpload $validator = null,
        \r8\Input\Files $files = null
    ) {
        parent::__construct( $name, $label );
        $this->validator = $validator ?: new \r8\Validator\FileUpload;
        $this->files = $files ?: \r8\Env::request()->getFiles();
    }

    /**
     * Returns the details of the uploaded file
     *
     * @return \r8\Input\File Returns NULL if no file was uploaded
     */
    public function getRawValue ()
    {
        return $this->files->getFile( $this->getName() );
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
        $result = $this->validator->validate( $this->getRawValue() );

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

    /**
     * Provides an interface for visiting this field
     *
     * @param \r8\iface\Form\Visitor $visitor The visitor object to call
     * @return NULL
     */
    public function visit ( \r8\iface\Form\Visitor $visitor )
    {
        $visitor->file( $this );
    }

}

