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
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package HTML
 */

namespace r8\HTML;

/**
 * Represents an HTML Head tag
 */
class Head
{

    /**
     * The title of the page
     *
     * @var String
     */
    private $title;

    /**
     * The collection of MetaTags in this Head
     *
     * @var Array An array of \r8\HTML\MetaTag objects
     */
    private $metatags = array();

    /**
     * Returns the title of the page
     *
     * @return String
     */
    public function getTitle ()
    {
        return $this->title;
    }

    /**
     * Sets the Title of the page
     *
     * @param String $title
     * @return \r8\HTML\Head Returns a self reference
     */
    public function setTitle ( $title )
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Appends a string to the Title of the page
     *
     * @param String $title
     * @return \r8\HTML\Head Returns a self reference
     */
    public function appendTitle ( $title )
    {
        $this->title .= $title;
        return $this;
    }

    /**
     * Returns the MetaTags in this header
     *
     * @return Array An array of \r8\HTML\MetaTag objects
     */
    public function getMetaTags ()
    {
        return $this->metatags;
    }

    /**
     * Adds a new MetaTag to this header
     *
     * @param \r8\HTML\MetaTag $metatag
     * @return \r8\HTML\Head Returns a self reference
     */
    public function addMetaTag ( \r8\HTML\MetaTag $metatag )
    {
        $this->metatags[] = $metatag;
        return $this;
    }

    /**
     * Clears all the MetaTags from this instance
     *
     * @return \r8\HTML\Head Returns a self reference
     */
    public function clearMetaTags ()
    {
        $this->metatags = array();
        return $this;
    }

}

?>