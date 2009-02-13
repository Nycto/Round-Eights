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
 * Collects a list of pages and selects which to display
 */
class Manager extends \cPHP\Page\Delegator
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
    public function getSubPages ()
    {
        return new \cPHP\Ary( $this->pages );
    }

    /**
     * Adds a page to this list of pages to render
     *
     * @param String $index The reference describing this page
     * @param Object $page The page to add
     * @return Object Returns a self reference
     */
    public function setSubPage ( $index, \cPHP\iface\Page $page )
    {
        $index = \cPHP\indexVal( $index );

        if ( \cPHP\isEmpty($index) )
            throw new \cPHP\Exception\Argument(0, "Page Index", "Must not be empty");

        $this->pages[ $index ] = $page;

        return $this;
    }

    /**
     * Returns a specific sub-page based on it's index
     *
     * @param String $index The reference of the page to retreive
     * @return Object|Null Returns a cPHP\iface\Page object, or Null if the
     *      specified page doesn't exist
     */
    public function getSubPage ( $index )
    {
        $index = \cPHP\indexVal( $index );

        if ( array_key_exists($index, $this->pages) )
            return $this->pages[ $index ];

        return null;
    }

    /**
     * Returns whether a page has been set
     *
     * @param String $index The page index
     * @return Boolean
     */
    public function subPageExists ( $index )
    {
        return array_key_exists(
                \cPHP\indexVal( $index ),
                $this->pages
            );
    }

    /**
     * Removes a page from the list of pages to render
     *
     * @param String $index The reference of the page to remove
     * @return Object Returns a self reference
     */
    public function removeSubPage ( $index )
    {
        $index = \cPHP\indexVal( $index );
        if ( array_key_exists($index, $this->pages) )
            unset( $this->pages[ $index ] );
        return $this;
    }

    /**
     * Removes all the pages from the list
     *
     * @return Object Returns a self reference
     */
    public function clearSubPages ()
    {
        $this->pages = array();
        return $this;
    }

    /**
     * Returns whether the current view is defined
     *
     * @return Boolean Returns whether the current view is defined
     */
    public function viewExists ()
    {
        return $this->subPageExists( $this->getView() );
    }

    /**
     * Executes the appropriate template and returns it's results
     *
     * @return Object Returns a template object
     */
    public function createContent ()
    {
        if ( !$this->viewExists() )
            return $this->getErrorView();

        $view = $this->pages[ $this->getView() ];

        if ( $view instanceof \cPHP\Page )
            $content = $view->getPage();
        else
            $content = $view->render();

        if ( !($content instanceof \cPHP\iface\Template) )
            $content = new \cPHP\Template\Raw($content);

        return $content;
    }

}

?>