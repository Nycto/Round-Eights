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
 * Provides a foundation for most Page classes
 */
abstract class Page implements \cPHP\iface\Page
{

    /**
     * Returns the template this page will display
     *
     * @return Object Returns a Template object
     */
    abstract public function getCoreTemplate ();

    /**
     * Returns the template that will be used to render the entire page.
     *
     * @return Object Returns a Template object
     */
    public function getPageTemplate ()
    {

    }

    /**
     * Outputs this page to the user
     *
     * @return Object Returns a self reference
     */
    public function display()
    {
    }

    /**
     * Returns the content of this page as a string
     *
     * @return String
     */
    public function render()
    {
    }

}

?>