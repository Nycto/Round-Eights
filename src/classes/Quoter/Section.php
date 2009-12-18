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
 * @package Quoter
 */

namespace r8\Quoter;

/**
 * Representation of each section of the parsed string
 */
abstract class Section
{

    /**
     * The content of this section
     */
    private $content;

    /**
     * Constructor...
     *
     * @param String $content The string content of this section
     */
    public function __construct( $content )
    {
        $this->setContent( $content );
    }

    /**
     * Returns whether the current section is quoted
     *
     * @return Boolean
     */
    abstract public function isQuoted ();

    /**
     * Returns the content in this section
     *
     * @return String
     */
    public function getContent ()
    {
        return $this->content;
    }

    /**
     * Sets the content in this section
     *
     * @param String $content The content for this section
     * @return Object Returns a self reference
     */
    public function setContent ( $content )
    {
        $this->content = is_null($content) ? null : \r8\strval( $content );
        return $this;
    }

    /**
     * Unsets the content from this section
     *
     * @return Object Returns a self reference
     */
    public function clearContent ()
    {
        $this->content = null;
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
     * Returns whether the content in this instance could be considered empty
     *
     * @param Integer $flags Any boolean flags to set. See \r8\isEmpty
     * @return Boolean
     */
    public function isEmpty ( $flags = 0 )
    {
        return \r8\isEmpty( $this->content, $flags );
    }

    /**
     * To be overwriten, converts this value in to a string
     *
     * @return String
     */
    abstract public function __toString();
}

?>