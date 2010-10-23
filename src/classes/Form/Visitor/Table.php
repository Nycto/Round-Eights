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
 * Renders the form as an HTML Table and returns the template
 */
class Table implements \r8\iface\Form\Visitor
{

    /**
     * Whether to display the errors when rendering the form
     *
     * @var Boolean
     */
    private $showErrors;

    /**
     * The title to put at the top of the form
     *
     * @var String
     */
    private $title;

    /**
     * The verbiage to put in the submit button
     *
     * @var String
     */
    private $submit;

    /**
     * As the form is visited, this is the formatted of string of fields
     *
     * @var String
     */
    private $fields;

    /**
     * Returns a Formatter set up to display the form as an HTML Table
     *
     * @param Boolean $showErrors Whether to display the errors when rendering
     *      the form
     * @param String $title The title to put at the top of the form
     * @param String $submit The text to put in the submit button
     */
    public function __construct ( $showErrors, $title = null, $submit = "Submit" )
    {
        $this->showErrors = (bool) $showErrors;
        $this->title = (string) $title ?: NULL;
        $this->submit = (string) $submit ?: "Submit";
    }

    /**
     * Returns a template for displaying errors filled with the proper values
     *
     * @param \r8\Validator\Result $result The validation result to render
     * @return \r8\iface\Template
     */
    private function getErrors ( \r8\Validator\Result $result )
    {
        if ( $result->isValid() )
            return "";

        return "<ul class='form-errors'>"
            ."<li>". implode("</li><li>", $result->getErrors()) ."</li>"
            ."</ul>";
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
        if ( $field->isHidden() )
            return;

        if ( $this->showErrors )
            $errors = $this->getErrors( $field->validate() );
        else
            $errors = "";

        $this->fields .=
            "        <tr class='form-field form-". $type ."'>\n"
            ."            <th>". $field->getLabelTag() ."</th>\n"
            ."            <td>". $errors . $field ."</td>\n"
            ."        </tr>\n";
    }

    /**
     * Invoked at the beginning of a visit
     *
     * @param \r8\Form $form The form being visited
     * @return NULL
     */
    public function begin ( \r8\Form $form )
    {
        $this->fields = "";
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
        $hidden = $form->getHiddenHTML();

        $result =
            "$form\n"
            .( !empty($hidden) ? "    $hidden\n" : "" )
            ."    <table class='form-table'>\n";

        if ( !empty($this->title) ) {
            $result .=
                "        <tr class='form-title'>\n"
                ."            <th colspan='2'>". htmlspecialchars($this->title) ."</th>\n"
                ."        </tr>\n";
        }

        if ( $this->showErrors && !$form->isFormValid() ) {
            $errors = $this->getErrors( $form->validateForm() );
            $result .=
                "        <tr class='form-errors'>\n"
                ."            <td colspan='2'>$errors</th>\n"
                ."        </tr>\n";
        }

        $result .=
            $this->fields
            ."        <tr class='form-submit'>\n"
            ."            <td colspan='2'>"
                ."<input type='submit' value='". htmlspecialchars($this->submit) ."' />"
                ."</td>\n"
            ."        </tr>\n"
            ."    </table>\n"
            ."</form>\n";

        return new \r8\Template\Raw( $result );
    }

}

