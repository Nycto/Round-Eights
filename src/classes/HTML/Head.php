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
 * Represents an HTML Head tag
 */
class Head
{

    /**
     * The DocType of the document
     *
     * @var \r8\HTML\DocType
     */
    private $docType;

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
     * The javascript to load in this Head
     *
     * @var Array An array of \r8\HTML\Javascript objects
     */
    private $javascript = array();

    /**
     * The CSS to load in this head
     *
     * @var Array An array of \r8\HTML\CSS objects
     */
    private $css = array();

    /**
     * Constructor...
     */
    public function __construct ()
    {
        $this->docType = \r8\HTML\DocType::NONE();
    }

    /**
     * Returns the DocType of this document head
     *
     * @return \r8\HTML\DocType
     */
    public function getDocType ()
    {
        return $this->docType;
    }

    /**
     * Sets the DocType of this document head
     *
     * @param \r8\HTML\DocType $docType
     * @return \r8\HTML\Head Returns a self reference
     */
    public function setDocType ( \r8\HTML\DocType $docType )
    {
        $this->docType = $docType;
        return $this;
    }

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

    /**
     * Returns the Javascript in this header
     *
     * @return Array An array of \r8\HTML\Javascript objects
     */
    public function getJavascript ()
    {
        return $this->javascript;
    }

    /**
     * Adds a new Javascript to this header
     *
     * @param \r8\HTML\Javascript $javascript
     * @return \r8\HTML\Head Returns a self reference
     */
    public function addJavascript ( \r8\HTML\Javascript $javascript )
    {
        $this->javascript[] = $javascript;
        return $this;
    }

    /**
     * Clears all the Javascript from this instance
     *
     * @return \r8\HTML\Head Returns a self reference
     */
    public function clearJavascript ()
    {
        $this->javascript = array();
        return $this;
    }

    /**
     * Returns the CSS in this header
     *
     * @return Array An array of \r8\HTML\CSS objects
     */
    public function getCSS ()
    {
        return $this->css;
    }

    /**
     * Adds a new CSS to this header
     *
     * @param \r8\HTML\CSS $css
     * @return \r8\HTML\Head Returns a self reference
     */
    public function addCSS ( \r8\HTML\CSS $css )
    {
        $this->css[] = $css;
        return $this;
    }

    /**
     * Clears all the CSS from this instance
     *
     * @return \r8\HTML\Head Returns a self reference
     */
    public function clearCSS ()
    {
        $this->css = array();
        return $this;
    }

    /**
     * Returns an HTML representation of this Head object
     *
     * Note that this does NOT include the DocType. Only the values within
     * the actual Head tag.
     *
     * @return \r8\HTML\Tag
     */
    public function getTag ()
    {
        $content = array();

        if ( !empty($this->title) )
            $content[] = "<title>". htmlspecialchars( $this->title ) ."</title>";

        $content = array_merge(
            $content,
            \r8\ary\stringize( \r8\ary\invoke( $this->metatags, "getTag" ) ),
            \r8\ary\stringize( \r8\ary\invoke( $this->css, "getTag" ) ),
            \r8\ary\stringize( \r8\ary\invoke( $this->javascript, "getTag" ) )
        );

        return new \r8\HTML\Tag( "head", implode( "\n", $content ) );
    }

}

?>