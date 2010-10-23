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
 * @package HTML
 */

namespace r8\HTML;

/**
 * Represents an HTMl node
 *
 * This is not meant as a replacement for the DOMXML extension. It is meant
 * as a supplement. Sometimes, DOMXML is overkill. The goal for this class
 * is to contain a single tag.
 */
abstract class Node implements \r8\iface\HTML\Node
{

    /**
     * The content of this tag
     *
     * @var Mixed
     */
    private $content;

    /**
     * Constructor...
     *
     * @param String $content Any content for this instance
     */
    public function __construct ( $content = null )
    {
        $this->setContent( $content );
    }

    /**
     * Returns the content of this tag
     *
     * @return String
     */
    public function getContent ()
    {
        return $this->content;
    }

    /**
     * Sets the content of this instance
     *
     * @param string $content
     * @return \r8\HTML\Tag Returns a self reference
     */
    public function setContent ( $content )
    {
        $content = (string) $content;
        $this->content = empty($content) && $content !== "0" ? null : $content;
        return $this;
    }

    /**
     * Adds content to the end of the existing content
     *
     * @param string $content
     * @return \r8\HTML\Tag Returns a self reference
     */
    public function appendContent ( $content )
    {
        $content = (string) $content;
        if ( !empty($content) || $content === "0" )
            $this->content .= $content;
        return $this;
    }

    /**
     * Returns whether the current instance has any content
     *
     * @return Boolean
     */
    public function hasContent ()
    {
        return isset($this->content);
    }

    /**
     * Unsets any content in this instance
     *
     * @return \r8\HTML\Tag Returns a self reference
     */
    public function clearContent ()
    {
        $this->content = null;
        return $this;
    }

    /**
     * Returns the HTML string represented by this instance
     *
     * @return String Returns a string of HTML
     */
    public function __toString ()
    {
        return $this->render();
    }

}

