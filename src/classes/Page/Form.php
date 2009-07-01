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
 * @package Page
 */

namespace h2o\Page;

/**
 * Shortcut for displaying and submitting a form
 */
class Form extends \h2o\Page
{

    /**
     * The form this page represents
     *
     * @var \h2o\Form
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
     * @var \h2o\iface\Page
     */
    private $display;

    /**
     * The page to return when the form has been submitted and validates
     *
     * @var \h2o\iface\Page
     */
    private $success;

    /**
     * Constructor...
     *
     * @param \h2o\Form $form The form this page represents
     * @param \h2o\iface\Page $display The page to return when displaying the form
     * @param \h2o\iface\Page $success The page to return when the form has been
     *      submitted and validates
     */
    public function __construct ( \h2o\Form $form, \h2o\iface\Page $display, \h2o\iface\Page $success )
    {
        $this->form = $form;
        $this->display = $display;
        $this->success = $success;
    }

    /**
     * Returns the form instance for this page
     *
     * @return \h2o\Form Returns a form object
     */
    public function getForm ()
    {
        return $this->form;
    }

    /**
     * Returns the page to use when displaying the form
     *
     * @return \h2o\iface\Page
     */
    public function getDisplay ()
    {
        return $this->display;
    }

    /**
     * Returns the page to use when a form is succesfully submitted
     *
     * @return \h2o\iface\Page
     */
    public function getSuccess ()
    {
        return $this->success;
    }

    /**
     * Returns an array that will be used to fill the form with data when it
     * is initially displayed.
     *
     * @return Array
     */
    public function getInitials ()
    {
        return $this->initials;
    }

    /**
     * Sets the array that will be used to fill the form with data when it
     * is initially displayed.
     *
     * @param array $initials The initial values array
     * @return \h2o\Page\Form Returns a self reference
     */
    public function setInitials ( array $initials )
    {
        $this->initials = $initials;
        return $this;
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
        if ( isset($this->source) )
            return $this->source;

        // @todo Replace this $_POST reference with something more OO
        else
            return $_POST;
    }

    /**
     * Sets the array that will be used to fill the form with data.
     *
     * @param array $source The source to pull the submitted data from
     * @return \h2o\Page\Form Returns a self reference
     */
    public function setSource ( array $source )
    {
        $this->source = $source;
        return $this;
    }

    /**
     * Returns the page object to use for the current form state
     *
     * @return \h2o\iface\Page
     */
    public function getPage ()
    {
        $form = $this->getForm();

        $source = $this->getSource();

        // If there was nothing submitted...
        if ( !$form->anyIn($source) ) {
            $form->fill( $this->getInitials() );
            return $this->getDisplay();
        }

        // Load the submitted data into the form
        $form->fill( $source );

        // If the form validates, display the success page
        if ( $form->isValid() )
            return $this->getSuccess();

        // Otherwise, display the page with errors
        else
            return $this->getDisplay();
    }

    /**
     * Returns the core content this page will display
     *
     * @param \h2o\Page\Context $context A context object which is used by this
     *      page to communicate with the root page
     * @return \h2o\iface\Template Returns a blank template
     */
    public function getContent ( \h2o\Page\Context $context )
    {
        return $this->getPage()->getContent( $context );
    }

}

?>