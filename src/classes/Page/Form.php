<?php
/**
 * Page encapsulation class
 *
 * @license Artistic License 2.0
 *
 * This file is part of commonPHP.
 *
 * commonPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * commonPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with commonPHP. If not, see <http://www.commonphp.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@commonphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Filters
 */

namespace cPHP\Page;

/**
 * Shortcut for displaying and submitting a form
 */
abstract class Form extends \cPHP\Page
{

    /**
     * The form this page represents
     *
     * @var \cPHP\Form
     */
    private $form;

    /**
     * The initial values to inject into the form if no values have been submitted
     *
     * @var array
     */
    private $initials = array();

    /**
     * The source array to pull submitted values from
     *
     * @var array
     */
    private $source;

    /**
     * The page to return when displaying the form
     *
     * @var \cPHP\iface\Page
     */
    private $display;

    /**
     * The page to return when the form has been submitted and validates
     *
     * @var \cPHP\iface\Page
     */
    private $success;

    /**
     * Constructor...
     *
     * @param \cPHP\Form $form The form this page represents
     * @param \cPHP\iface\Page $display The page to return when displaying the form
     * @param \cPHP\iface\Page $success The page to return when the form has been
     *      submitted and validates
     */
    public function __construct ( \cPHP\Form $form, \cPHP\iface\Page $display, \cPHP\iface\Page $success )
    {
        $this->form = $form;
        $this->display = $display;
        $this->success = $success;
    }

    /**
     * Returns the form instance for this page
     *
     * @return \cPHP\Form Returns a form object
     */
    public function getForm ()
    {
        return $this->form;
    }

    /**
     * Returns the array that will be used to fill the form with data.
     *
     * By default, this will return the array of posted values
     *
     * @return Array
     */
    public function getSource ()
    {
        return $_POST;
    }

    /**
     * Returns an array that will be used to fill the form with data when it
     * is initially displayed.
     *
     * By default, this returns an empty array
     *
     * @return Array
     */
    public function getInitialValues ()
    {
        return array();
    }

    /**
     * The method invoked when a form is initially displayed
     *
     * @return Object Returns the template for the content
     */
    abstract protected function onDisplay ();

    /**
     * The method invoked when a form is submitted but the values are invalid.
     *
     * By default, this method just calls the onDisplay method
     *
     * @return Object Returns the template for the content
     */
    protected function onInvalid ()
    {
        return $this->onDisplay();
    }

    /**
     * The method invoked when a form is submitted and validates
     *
     * @return Object Returns the template for the content
     */
    abstract protected function onSuccess ();

    /**
     * Executes the view method and returns it's results
     *
     * @return Object Returns a template object
     */
    protected function createContent ()
    {
        $form = $this->getForm();

        $source = $this->getSource();

        // If there was nothing submitted...
        if ( !$form->anyIn($source) ) {
            $form->fill( $this->getInitialValues() );
            return $this->onDisplay();
        }

        // Load the source data into the form and validate
        else if ( $form->fill($source)->isValid() ) {
            return $this->onSuccess();
        }

        // Otherwise, display the error page
        else {
            return $this->onInvalid();
        }
    }

}

?>