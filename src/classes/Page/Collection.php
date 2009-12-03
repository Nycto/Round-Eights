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
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Page
 */

namespace r8\Page;

/**
 * Collects a list of pages and displays them all
 */
class Collection implements \r8\iface\Page
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
     * @param \r8\iface\Page $page The page to add
     * @return \r8\Page\Collection Returns a self reference
     */
    public function addPage ( \r8\iface\Page $page )
    {
        $this->pages[] = $page;
        return $this;
    }

    /**
     * Resets the list of pages in this instance
     *
     * @return \r8\Page\Collection Returns a self reference
     */
    public function clearPages ()
    {
        $this->pages = array();
        return $this;
    }

    /**
     * Returns the core content this page will display
     *
     * @param \r8\Page\Context $context A context object which is used by this
     *      page to communicate with the root page
     * @return \r8\Template\Collection Returns a template collection
     */
    public function getContent ( \r8\Page\Context $context )
    {
        $tpl = new \r8\Template\Collection;

        foreach ( $this->pages AS $page ) {

            $content = $page->getContent( $context );

            if ( !($content instanceof \r8\iface\Template) )
                $content = new \r8\Template\Raw( $content );

            $tpl->add( $content );

        }

        return $tpl;
    }

}

?>