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
 * Passes a template through as a page
 */
class Template extends \cPHP\Page
{

    /**
     * The content this page will display
     *
     * @var Object a Template object
     */
    private $template;

    /**
     * Constructor...
     *
     * @param mixed $template The template this page will display
     */
    public function __construct( \cPHP\iface\Template $template = NULL )
    {
        if ( $template instanceof \cPHP\iface\Template )
            $this->setTemplate( $template );
    }

    /**
     * Returns the template in this instance
     *
     * @return Object Returns a template object
     */
    public function getTemplate ()
    {
        return $this->template;
    }

    /**
     * Sets the template for this instance
     *
     * @param Object $template The template being set
     * @return Object Returns a self reference
     */
    public function setTemplate ( \cPHP\iface\Template $template )
    {
        $this->template = $template;
        return $this;
    }

    /**
     * Clears the template out of this instance
     *
     * @return Boolean Returns whether this instance has any template
     */
    public function templateExists ()
    {
        return isset( $this->template );
    }

    /**
     * Clears the template out of this instance
     *
     * @return Object Returns a self reference
     */
    public function clearTemplate ()
    {
        $this->template = null;
        return $this;
    }

    /**
     * Returns the core content this page will display
     *
     * @return mixed Returns the central content for the page
     */
    protected function createContent ()
    {
        if ( $this->templateExists() )
            return $this->template;
        else
            return new \cPHP\Template\Raw;
    }

}

?>