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
     * A description of the current environment
     *
     * @var cPHP\Env
     */
    private $env;

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
     * Renders the contained page and returns the results
     *
     * @return cPHP\iface\Template Returns the rendered template
     */
    public function getTemplate ()
    {

    }

    /**
     * Helper function that invokes getTemplate and outputs the result to the client
     *
     * @return cPHP\Page Returns a self reference
     */
    public function display ()
    {

    }

}

?>