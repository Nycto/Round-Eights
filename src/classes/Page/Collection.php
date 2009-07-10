<?php
/**
 * @license Artistic License 2.0
 *
 * This file is part of RaindropPHP.
 *
 * RaindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * RaindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with RaindropPHP. If not, see <http://www.RaindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@Raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Page
 */

namespace h2o\Page;

/**
 * Collects a list of pages and displays them all
 */
class Collection implements \h2o\iface\Page
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
     * @param \h2o\iface\Page $page The page to add
     * @return \h2o\Page\Collection Returns a self reference
     */
    public function addPage ( \h2o\iface\Page $page )
    {
        $this->pages[] = $page;
        return $this;
    }

    /**
     * Resets the list of pages in this instance
     *
     * @return \h2o\Page\Collection Returns a self reference
     */
    public function clearPages ()
    {
        $this->pages = array();
        return $this;
    }

    /**
     * Returns the core content this page will display
     *
     * @param \h2o\Page\Context $context A context object which is used by this
     *      page to communicate with the root page
     * @return \h2o\Template\Collection Returns a template collection
     */
    public function getContent ( \h2o\Page\Context $context )
    {
        $tpl = new \h2o\Template\Collection;

        foreach ( $this->pages AS $page ) {

            $content = $page->getContent( $context );

            if ( !($content instanceof \h2o\iface\Template) )
                $content = new \h2o\Template\Raw( $content );

            $tpl->add( $content );

        }

        return $tpl;
    }

}

?>