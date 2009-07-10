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
 * Accepts a Template and a list of pages. When this instance is rendered, it
 * will render all the contained pages and inject them as values into the given
 * template
 */
class Injector implements \h2o\iface\Page
{

    /**
     * The template that will have pages injected into it.
     *
     * @var \h2o\Template
     */
    private $template;

    /**
     * The list of pages to render and inject
     *
     * @var array
     */
    private $pages = array();

    /**
     * Constructor...
     *
     * @param \h2o\Template $template The template that will have pages injected
     *      into it.
     */
    public function __construct ( \h2o\Template $template )
    {
        $this->template = $template;
    }

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
     * @param String The variable name to inject this page as
     * @param \h2o\iface\Page $page The page to add
     * @return \h2o\Page\Collection Returns a self reference
     */
    public function addPage ( $index, \h2o\iface\Page $page )
    {
        $index = \h2o\Template::normalizeLabel( $index );
        $this->pages[ $index ] = $page;
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
     * @return \h2o\Template Returns the input template
     */
    public function getContent ( \h2o\Page\Context $context )
    {
        foreach ( $this->pages AS $index => $page ) {

            $content = $page->getContent( $context );

            if ( !($content instanceof \h2o\iface\Template) )
                $content = new \h2o\Template\Raw( $content );

            $this->template->set( $index, $content );

        }

        return $this->template;
    }

}

?>