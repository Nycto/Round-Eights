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
     * Stores the form after it has been created
     *
     * @var Object A Form object
     */
    private $form;

    /**
     * Returns a new instance of the form for this instance. This is called automatically
     * by the getForm method, so use that instead
     *
     * @return Object Returns a form object
     */
    abstract protected function createForm ();

    /**
     * Returns the form instance for this page. This can be called as many times
     * as needed, but the same form instance will always be returned
     *
     * @return Object Returns a form object
     */
    public function getForm ()
    {
        if ( !isset($this->form) ) {
            $this->form = $this->createForm();
            if ( !($this->form instanceof \cPHP\Form) )
                $this->form = new \cPHP\Form;
        }

        return $this->form;
    }

    /**
     * Returns the array that will be used to fill the form with data.
     *
     * By default, this will return the $_POST array. This method was designed
     * to be overridden if a different data source is desired
     *
     * @return Array
     */
    public function getSource ()
    {
        return $_POST;
    }

    /**
     * Executes the view method and returns it's results
     *
     * @return Object Returns a template object
     */
    public function createContent ()
    {
    }

}

?>