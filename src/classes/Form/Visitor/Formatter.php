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

namespace r8\Form\Visitor;

/**
 * A Form visitor that generates a template of the form
 */
class Formatter implements \r8\iface\Form\Visitor
{

    /**
     * Whether to display the errors when rendering the form
     *
     * @var Boolean
     */
    private $showErrors;

    /**
     * The template for rendering each individual field
     *
     * @var \r8\iface\Template\Access
     */
    private $fieldTpl;

    /**
     * The template for redering the list of errors for each field
     *
     * @var \r8\iface\Template\Access
     */
    private $errorsTpl;

    /**
     * The template to use for rendering the overall form
     *
     * @var \r8\iface\Template\Access
     */
    private $formTpl;

    /**
     * As the form is visited, this is the list of field templates
     *
     * @var \r8\Template\Collection
     */
    private $fields;

    /**
     * Constructor...
     *
     * @param Boolean $showErrors Whether to display the errors when rendering
     *      the form
     * @param \r8\iface\Template\Access $fieldTpl The template for rendering each
     *      individual field
     * @param \r8\iface\Template\Access $errorsTpl The template for redering the
     *      list of errors for each field
     * @param \r8\iface\Template\Access $formTpl The template to use for rendering
     *      the overall form
     */
    public function __construct (
        $showErrors,
        \r8\iface\Template\Access $fieldTpl,
        \r8\iface\Template\Access $errorsTpl,
        \r8\iface\Template\Access $formTpl
    ) {
        $this->showErrors = (bool) $showErrors;
        $this->fieldTpl = $fieldTpl;
        $this->errorsTpl = $errorsTpl;
        $this->formTpl = $formTpl;
    }

    /**
     * Returns a template for displaying errors filled with the proper values
     *
     * @return \r8\iface\Template
     */
    private function getErrorTpl ( \r8\Validator\Result $result )
    {
        if ( !$this->showErrors )
            return new \r8\Template\Blank;

        $errors = clone $this->errorsTpl;
        $errors->set( "errors", $result->getErrors() );
        $errors->set( "showErrors", $this->showErrors );
        return $errors;
    }

    /**
     * An internal method for creating a template from a form field
     *
     * @param String $type A string describing this field type
     * @param \r8\iface\Form\Field $field
     * @return NULL
     */
    private function addField ( $type, \r8\iface\Form\Field $field )
    {
        if ( !$field->isHidden() ) {
            $tpl = clone $this->fieldTpl;
            $tpl->set( "type", $type );
            $tpl->set( "field", $field->__toString() );
            $tpl->set( "label", $field->getLabelTag()->__toString() );
            $tpl->set( "errors", $this->getErrorTpl( $field->validate() ) );
            $tpl->set( "showErrors", $this->showErrors );
            $this->fields->add( $tpl );
        }
    }

    /**
     * Invoked at the beginning of a visit
     *
     * @param \r8\Form $form The form being visited
     * @return NULL
     */
    public function begin ( \r8\Form $form )
    {
        $this->fields = new \r8\Template\Collection;
    }

    /**
     * Visitor callback for a checkbox field
     *
     * @param \r8\Form\Checkbox $field
     * @return NULL
     */
    public function checkbox ( \r8\Form\Checkbox $field )
    {
        $this->addField( "checkbox", $field );
    }

    /**
     * Visitor callback for a file field
     *
     * @param \r8\Form\File $field
     * @return NULL
     */
    public function file ( \r8\Form\File $field )
    {
        $this->addField( "file", $field );
    }

    /**
     * Visitor callback for a hidden field
     *
     * @param \r8\Form\Hidden $field
     * @return NULL
     */
    public function hidden ( \r8\Form\Hidden $field )
    {
        $this->addField( "hidden", $field );
    }

    /**
     * Visitor callback for a password field
     *
     * @param \r8\Form\Password $field
     * @return NULL
     */
    public function password ( \r8\Form\Password $field )
    {
        $this->addField( "password", $field );
    }

    /**
     * Visitor callback for a radio field
     *
     * @param \r8\Form\Radio $field
     * @return NULL
     */
    public function radio ( \r8\Form\Radio $field )
    {
        $this->addField( "radio", $field );
    }

    /**
     * Visitor callback for a select field
     *
     * @param \r8\Form\Select $field
     * @return NULL
     */
    public function select ( \r8\Form\Select $field )
    {
        $this->addField( "select", $field );
    }

    /**
     * Visitor callback for a text field
     *
     * @param \r8\Form\Text $field
     * @return NULL
     */
    public function text ( \r8\Form\Text $field )
    {
        $this->addField( "text", $field );
    }

    /**
     * Visitor callback for a text area field
     *
     * @param \r8\Form\TextArea $field
     * @return NULL
     */
    public function textArea ( \r8\Form\TextArea $field )
    {
        $this->addField( "textarea", $field );
    }

    /**
     * Invoked at the end of a visit
     *
     * @param \r8\Form $form The form being visited
     * @return Mixed Returns whatever value should be the result of the visit
     */
    public function end ( \r8\Form $form )
    {
        $formTpl = clone $this->formTpl;

        $formTpl->set( "form", $form->__toString() );
        $formTpl->set( "hidden", $form->getHiddenHTML() );
        $formTpl->set( "fields", $this->fields );
        $formTpl->set( "isValid", $form->isValid() );
        $formTpl->set( "isFormValid", $form->isFormValid() );
        $formTpl->set( "showErrors", $this->showErrors );
        $formTpl->set( "errors", $this->getErrorTpl( $form->validateForm() ) );

        return $formTpl;
    }

}

?>