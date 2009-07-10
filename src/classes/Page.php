<?php
/**
 * Page encapsulation class
 *
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
 * @package Filters
 */

namespace h2o;

/**
 * The root page generates the templates of the pages it contains, reacting to
 * any global decisions they make via the context object
 */
class Page
{

    /**
     * The page being rendered
     *
     * @var \h2o\iface\Page
     */
    private $page;

    /**
     * The response object to be sent back to the client
     *
     * @var \h2o\iface\Env\Response
     */
    private $response;

    /**
     * The context object that will be used to coordinate multiple pages
     *
     * @var \h2o\Page\Context
     */
    private $context;

    /**
     * Constructor... Accepts the page that will be rendered when getTemplate is
     * called.
     *
     * @param \h2o\iface\Page $page The page being displayed
     */
    public function __construct ( \h2o\iface\Page $page )
    {
        $this->page = $page;
        $this->context = new \h2o\Page\Context;
    }

    /**
     * Returns the page that will be rendered
     *
     * @return \h2o\iface\Page
     */
    public function getPage ()
    {
        return $this->page;
    }

    /**
     * Returns the context object that will be passed in to sub-pages
     *
     * @return \h2o\Page\Context
     */
    public function getContext ()
    {
        return $this->context;
    }

    /**
     * Sets the context object to pass in to sub-pages
     *
     * @param \h2o\Page\Context $context The context object to pass in to sub-pages
     * @return \h2o\Page Returns a self reference
     */
    public function setContext ( \h2o\Page\Context $context )
    {
        $this->context = $context;
        return $this;
    }

    /**
     * Returns the response object that will be sent to the client
     *
     * @return \h2o\iface\Env\Response
     */
    public function getResponse ()
    {
        if ( isset($this->response) )
            return $this->response;
        else
            return \h2o\Env::Response();
    }

    /**
     * Sets the response object this instance should use
     *
     * @param \h2o\iface\Env\Response $response
     * @return \h2o\Page Returns a self reference
     */
    public function setResponse ( \h2o\iface\Env\Response $response )
    {
        $this->response = $response;
        return $this;
    }

    /**
     * Renders the contained page and returns the results
     *
     * @return \h2o\iface\Template Returns the rendered template
     */
    public function getTemplate ()
    {
        $context = $this->getContext();

        try {
            $template = $this->getPage()->getContent( $context );
        }
        catch ( \h2o\Exception\Interrupt\Page $err ) {

            // If an interrupt is thrown, suppress the page load
            $context->suppress();
        }

        // Pull the redirect URL
        $redirect = $context->getRedirect();

        // If the redirect URL isn't empty, send the appropriate header
        if ( !\h2o\isEmpty( $redirect ) )
            $this->getResponse()->setHeader( "Location: ". $redirect );

        // If the context denotes a suppressed page, return a blank template
        if ( $context->isSuppressed() )
            return new \h2o\Template\Blank;

        return $template;
    }

    /**
     * Helper function that invokes getTemplate and outputs the result to the client
     *
     * @return \h2o\Page Returns a self reference
     */
    public function display ()
    {
        $this->getTemplate()->display();
        return $this;
    }

}

?>