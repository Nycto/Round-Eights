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
 * Allows a DOMDocument object to be used as a template
 */
class DOMDoc implements \r8\iface\Template
{

    /**
     * The XML document being rendered
     *
     * @var \DOMDocument
     */
    private $doc;

    /**
     * Constructor...
     *
     * @param \DOMDocument $doc The XML document being rendered
     */
    public function __construct ( \DOMDocument $doc )
    {
        $this->doc = $doc;
    }

    /**
     * Displays the content of this template
     *
     * In this specific template, this method will not display anything
     *
     * @return \r8\Template\DOMDoc Returns a self reference
     */
    public function display ()
    {
        echo $this->render();
        return $this;
    }

    /**
     * Returns the content of this template as a string
     *
     * @return String Returns a blank string
     */
    public function render ()
    {
        return $this->doc->saveXML();
    }

    /**
     * Wrapper for the render function
     *
     * @return String Returns a blank string
     */
    public function __toString ()
    {
        return $this->render();
    }

}

