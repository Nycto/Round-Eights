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
 * A raw template that simply outputs exactly what is put in
 */
class Raw implements \r8\iface\Template
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
     * @return \r8\Template\Raw Returns a self reference
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
     * @return \r8\Template\Blank Returns a self reference
     */
    public function clearContent ()
    {
        $this->content = null;
        return $this;
    }

    /**
     * Displays the content of this template
     *
     * @return \r8\Template\Blank Returns a self reference
     */
    public function display ()
    {
        echo $this->render();
        return $this;
    }

    /**
     * Returns the content of this template as a string
     *
     * @return Mixed
     */
    public function render ()
    {
        // This allows a toString method to return a non-string without throwing an error
        if ( is_object($this->content) && \r8\respondTo($this->content, "__toString") )
            return (string) $this->content->__toString();
        else
            return (string) $this->content;
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