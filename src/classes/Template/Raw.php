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
 * @author James Frasca <james@Raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Template
 */

namespace h2o\Template;

/**
 * A raw template that simply outputs exactly what is put in
 */
class Raw implements \h2o\iface\Template
{

    /**
     * The content for this instance
     *
     * @var mixed
     */
    private $content;

    /**
     * Constructor...
     *
     * @param mixed $content The content for this instance
     */
    public function __construct( $content = NULL )
    {
        $this->setContent( $content );
    }

    /**
     * Returns the content in this instance
     *
     * @return mixed
     */
    public function getContent ()
    {
        return $this->content;
    }

    /**
     * Sets the content for this instance
     *
     * @param mixed $content The content being set
     * @return \h2o\Template\Raw Returns a self reference
     */
    public function setContent ( $content )
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Returns whether this instance has any content
     *
     * @return Boolean
     */
    public function contentExists ()
    {
        return isset( $this->content );
    }

    /**
     * Clears the content out of this instance
     *
     * @return \h2o\Template\Blank Returns a self reference
     */
    public function clearContent ()
    {
        $this->content = null;
        return $this;
    }

    /**
     * Displays the content of this template
     *
     * @return \h2o\Template\Blank Returns a self reference
     */
    public function display ()
    {
        echo \h2o\strval( $this->content );
        return $this;
    }

    /**
     * Returns the content of this template as a string
     *
     * @return Mixed
     */
    public function render ()
    {
        return \h2o\strval( $this->content );
    }

    /**
     * Returns the content of this template as a string
     *
     * This is a wrapper for the render function
     *
     * @return String
     */
    public function __toString ()
    {
        return $this->render();
    }

}

?>