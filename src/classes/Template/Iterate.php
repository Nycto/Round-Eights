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
 * @copyright Copyright 2009, James Frasca, All Rights Reserved
 * @package Template
 */

namespace r8\Template;

/**
 * Imports the results of an iterator into a prototype template and displays
 * it once for each entry
 */
class Iterate implements \r8\iface\Template
{

    /**
     * The prototype template to clone
     *
     * @var \r8\iface\Template\Access
     */
    private $prototype;

    /**
     * The iterator to pull data from
     *
     * @var \Traversable
     */
    private $iterator;

    /**
     * Constructor...
     *
     * @param \r8\iface\Template\Access $prototype The prototype template to clone
     * @param \Traversable $iterator The iterator to pull data from
     */
    public function __construct (
        \r8\iface\Template\Access $prototype,
        \Traversable $iterator
    ) {
        $this->prototype = $prototype;
        $this->iterator = $iterator;
    }

    /**
     * Displays all the templates contained in this instance
     *
     * @return \r8\Template\Collection Returns a self reference
     */
    public function display ()
    {
        echo $this->render();
        return $this;
    }

    /**
     * Renders this template and returns it as a string
     *
     * @return String Returns the rendered template as a string
     */
    public function render ()
    {
        $accum = "";

        foreach ( $this->iterator AS $key => $value )
        {
            $tpl = clone $this->prototype;
            $tpl->import( $value );
            $tpl->add( "key", $key );
            $accum .= $tpl->render();
        }

        return $accum;
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