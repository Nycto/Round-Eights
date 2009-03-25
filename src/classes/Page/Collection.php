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
 * @package Page
 */

namespace cPHP\Page;

/**
 * Collects a list of pages and displays them all
 */
class Collection implements \cPHP\iface\Page
{

    /**
     * The list of pages to render
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
        return $this->pages;
    }

    /**
     * Adds a page to this list of pages to render
     *
     * @param cPHP\iface\Page $page The page to add
     * @return cPHP\Page\Collection Returns a self reference
     */
    public function addPage ( \cPHP\iface\Page $page )
    {
        $this->pages[] = $page;
        return $this;
    }

    /**
     * Resets the list of pages in this instance
     *
     * @return cPHP\Page\Collection Returns a self reference
     */
    public function clearPages ()
    {
        $this->pages = array();
        return $this;
    }

    /**
     * Returns the core content this page will display
     *
     * @param cPHP\Page\Context $context A context object which is used by this
     *      page to communicate with the root page
     * @return \cPHP\Template\Collection Returns a template collection
     */
    public function getContent ( \cPHP\Page\Context $context )
    {
        $tpl = new \cPHP\Template\Collection;

        foreach ( $this->pages AS $page ) {

            $content = $page->getContent( $context );

            if ( !($content instanceof \cPHP\iface\Template) )
                $content = new \cPHP\Template\Raw( $content );

            $tpl->add( $content );

        }

        return $tpl;
    }

}

?>