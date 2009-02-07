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
 * Collects a list of pages and selects which to display
 */
class Manager extends basePage
{

    /**
     * The list of pages to render, where the key is their index and the value
     * is the page object
     *
     * @var array
     */
    private $pages = array();

    /**
     * Returns the list of pages that will be rendered
     *
     * @return Array Returns an array of page objects
     */
    public function getPages ()
    {

    }

    /**
     * Adds a page to this list of pages to render
     *
     * @param String $index The reference describing this page
     * @param Object $page The page to add
     * @return Object Returns a self reference
     */
    public function addPage ( $index, Page $page )
    {

    }

    /**
     * Removes a page from the list of pages to render
     *
     * @param String $index The reference of the page to remove
     * @return Object Returns a self reference
     */
    public function removePage ( $index )
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