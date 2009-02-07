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
 * Passes a template through as a page
 */
class Template extends basePage
{

    /**
     * The template this page will display
     *
     * @var Object A template
     */
    private $template;

    /**
     * Constructor...
     *
     * @param mixed $template The content this page will display
     */
    public function __construct ( templatable $template = null )
    {
    }

    /**
     * Sets the template this page will display
     *
     * @param mixed $template The template this page will display
     * @return Object Returns a self reference
     */
    public function setTemplate ( templatable $template )
    {

    }

    /**
     * Returns the content this page will display
     *
     * @return Object Returns a template object
     */
    public function getContent ()
    {

    }

    /**
     * Returns the template this page will display
     *
     * @return Object Returns a Template object
     */
    public function getCoreTemplate ()
    {

    }

}

?>