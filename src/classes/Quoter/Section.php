<?php
/**
 * Quote parsing result class
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
 * @package Quoter
 */

namespace cPHP\Quoter;

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
        $this->content = is_null($content) ? null : \cPHP\strval( $content );
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
     * @param Integer $flags Any boolean flags to set. See \cPHP\isEmpty
     * @return Boolean
     */
    public function isEmpty ( $flags = 0 )
    {
        return \cPHP\isEmpty( $this->content, $flags );
    }

    /**
     * To be overwriten, converts this value in to a string
     *
     * @return String
     */
    abstract public function __toString();
}

?>