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

namespace cPHP;

/**
 * Shortcut for displaying and submitting a form
 */
abstract class Form extends basePage
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

    }

    /**
     * Executes the view method and returns it's results
     *
     * @return Object Returns a template object
     */
    public function getCoreTemplate ()
    {

    }

}

?>