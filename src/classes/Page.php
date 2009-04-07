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
 * The root page generates the templates of the pages it contains, reacting to
 * any global decisions they make via the context object
 */
class Page
{

    /**
     * The page being rendered
     *
     * @var cPHP\iface\Page
     */
    private $page;

    /**
     * The response object to be sent back to the client
     *
     * @var cPHP\iface\Env\Response
     */
    private $response;

    /**
     * The context object that will be used to coordinate multiple pages
     *
     * @var cPHP\Page\Context
     */
    private $context;

    /**
     * Constructor... Accepts the page that will be rendered when getTemplate is
     * called.
     *
     * @param cPHP\iface\Page $page The page being displayed
     */
    public function __construct ( \cPHP\iface\Page $page )
    {
        $this->page = $page;
        $this->context = new \cPHP\Page\Context;
    }

    /**
     * Returns the page that will be rendered
     *
     * @return cPHP\iface\Page
     */
    public function getPage ()
    {
        return $this->page;
    }

    /**
     * Returns the context object that will be passed in to sub-pages
     *
     * @return cPHP\Page\Context
     */
    public function getContext ()
    {
        return $this->context;
    }

    /**
     * Sets the context object to pass in to sub-pages
     *
     * @param \cPHP\Page\Context $context The context object to pass in to sub-pages
     * @return \cPHP\Page Returns a self reference
     */
    public function setContext ( \cPHP\Page\Context $context )
    {
        $this->context = $context;
        return $this;
    }

    /**
     * Returns the response object that will be sent to the client
     *
     * @return cPHP\iface\Env\Response
     */
    public function getResponse ()
    {
        if ( isset($this->response) )
            return $this->response;
        else
            return \cPHP\Env::Response();
    }

    /**
     * Sets the response object this instance should use
     *
     * @param \cPHP\iface\Env\Response $response
     * @return \cPHP\Page Returns a self reference
     */
    public function setResponse ( \cPHP\iface\Env\Response $response )
    {
        $this->response = $response;
        return $this;
    }

    /**
     * Renders the contained page and returns the results
     *
     * @return cPHP\iface\Template Returns the rendered template
     */
    public function getTemplate ()
    {
        $context = $this->getContext();

        try {
            $template = $this->getPage()->getContent( $context );
        }
        catch ( \cPHP\Exception\Interrupt\Page $err ) {

            // If an interrupt is thrown, suppress the page load
            $context->suppress();
        }

        // Pull the redirect URL
        $redirect = $context->getRedirect();

        // If the redirect URL isn't empty, send the appropriate header
        if ( !\cPHP\isEmpty( $redirect ) )
            $this->getResponse()->setHeader( "Location: ". $redirect );

        // If the context denotes a suppressed page, return a blank template
        if ( $context->isSuppressed() )
            return new \cPHP\Template\Blank;

        return $template;
    }

    /**
     * Helper function that invokes getTemplate and outputs the result to the client
     *
     * @return cPHP\Page Returns a self reference
     */
    public function display ()
    {
        $this->getTemplate()->display();
        return $this;
    }

}

?>