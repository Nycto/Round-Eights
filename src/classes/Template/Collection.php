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
 * @author James Frasca <James@RaindropPHP.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Template
 */

namespace h2o\Template;

/**
 * A collection of templates that can be treated as a single template
 */
class Collection implements \h2o\iface\Template
{

    /**
     * The list of templates
     *
     * @var Array An array of \h2o\iface\Template objects
     */
    protected $list = array();

    /**
     * Returns the list of templates registered in this instance
     *
     * @return Array Returns an array of \h2o\iface\Template objects
     */
    public function getTemplates ()
    {
        return $this->list;
    }

    /**
     * Adds a template to the end of this list
     *
     * @param \h2o\iface\Template $template The template to add
     * @return \h2o\Template\Collection Returns a self reference for chaining
     */
    public function add ( \h2o\iface\Template $template )
    {
        $this->list[] = $template;
        return $this;
    }

    /**
     * Displays all the templates contained in this instance
     *
     * @return \h2o\Template\Collection Returns a self reference
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