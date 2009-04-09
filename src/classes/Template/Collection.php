<?php
/**
 * Core Template Class
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
 * @package FileFinder
 */

namespace cPHP\Template;

/**
 * A collection of templates that can be treated as a single template
 */
class Collection implements \cPHP\iface\Template
{

    /**
     * The list of templates
     */
    protected $list = array();

    /**
     * Returns the list of templates registered in this instance
     *
     * @return Array
     */
    public function getTemplates ()
    {
        return $this->list;
    }

    /**
     * Adds a template to the end of this list
     *
     * @param object $template The template to add. This must be an instance of
     *      cPHP\iface\Template
     * @return Object Returns a self reference for chaining
     */
    public function add ( \cPHP\iface\Template $template )
    {
        $this->list[] = $template;
        return $this;
    }

    /**
     * Displays all the templates contained in this instance
     *
     * @return Object Returns a self reference
     */
    public function display ()
    {
        foreach ( $this->list AS $tpl ) {
            $tpl->display();
        }
        return $this;
    }

    /**
     * Renders this template and returns it as a string
     *
     * @return String Returns the rendered template as a string
     */
    public function render ()
    {
        ob_start();
        $this->display();
        return ob_get_clean();
    }

    /**
     * Renders the template and returns it as a string
     *
     * @return String
     */
    public function __toString ()
    {
        return $this->render();
    }

}

?>