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
 * Accepts a Template and a list of pages. When this instance is rendered, it
 * will render all the contained pages and inject them as values into the given
 * template
 */
class Injector implements \r8\iface\Page
{

    /**
     * The template that will have pages injected into it.
     *
     * @var \r8\Template
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
     * @param \r8\Template $template The template that will have pages injected
     *      into it.
     */
    public function __construct ( \r8\Template $template )
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
     * @param \r8\iface\Page $page The page to add
     * @return \r8\Page\Collection Returns a self reference
     */
    public function addPage ( $index, \r8\iface\Page $page )
    {
        $index = \r8\Template::normalizeLabel( $index );
        $this->pages[ $index ] = $page;
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
     * @return \r8\Template Returns the input template
     */
    public function getContent ( \r8\Page\Context $context )
    {
        foreach ( $this->pages AS $index => $page ) {

            $content = $page->getContent( $context );

            if ( !($content instanceof \r8\iface\Template) )
                $content = new \r8\Template\Raw( $content );

            $this->template->set( $index, $content );

        }

        return $this->template;
    }

}

?>