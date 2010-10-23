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
 * Allows for the creation, manipulation and display of an HTML tag
 *
 * This is not meant as a replacement for the DOMXML extension. It is meant
 * as a supplement. Sometimes, DOMXML is overkill. The goal for this class
 * is to contain a single tag.
 */
class Tag extends \r8\HTML\Node implements \ArrayAccess
{

    /**
     * The tag that this instance represents
     *
     * @var String
     */
    private $tag;

    /**
     * Whether or not this tag is empty.
     *
     * This is actually an override. The class will try to determine if a tag
     * should be empty by looking at whether it has content and, then the type
     * of tag.
     *
     * @var Boolean
     */
    private $empty;

    /**
     * The attributes for this tag
     *
     * @var Array
     */
    private $attrs = array();

    /**
     * Allows a tag instance to be created by calling a method with the tag name.
     *
     * @param string $func The function called statically, which will be used as the tag name
     * @param array $args Any args passed to the function call.
     *      Offset 0 will be used as the content, offset 1 as the attributes
     * @return \r8\HTML\Tag
     */
    static public function __callStatic ( $func, $args )
    {
        $tag = new static( $func );

        if ( count($args) > 0 )
            $tag->setContent( array_shift($args) );

        if ( count($args) > 0 )
            $tag->importAttrs( array_shift($args) );

        return $tag;
    }

    /**
     * Takes an HTML attribute and strips it down
     *
     * @throws \r8\Exception\Argument Thrown when the attribute name is empty
     * @param String $attr The name of the attribute
     * @return String The normalized version of the attribute name
     */
    static public function normalizeAttrName ( $attr )
    {
        $attr = strtolower( \r8\str\stripW($attr) );

        if ( empty($attr) )
            throw new \r8\Exception\Argument( 0, "Attribute Name", "Must not be empty" );

        return $attr;
    }

    /**
     * Puts quotes around a string and prepares it to be used as the value of an HTML attribute
     *
     * @param String $string The value being quoted
     * @return String Returns the quoted value
     */
    static public function quoteAttr ($string)
    {
        return '"'. htmlspecialchars( (string) $string ) .'"';
    }

    /**
     * Constructor...
     *
     * @param String $tag The tag this instance represents
     * @param String $content Any content for this instance
     * @param Array $attrs Any attributes to load in
     */
    public function __construct ( $tag, $content = null, $attrs = array() )
    {
        parent::__construct( $content );
        $this->setTag( $tag );
        $this->importAttrs( $attrs );
    }

    /**
     * Returns the tag held in this instance
     *
     * @return string
     */
    public function getTag ()
    {
        return $this->tag;
    }

    /**
     * Sets the value of the tag in this instance
     *
     * @param String $tag The tag this instance represents
     * @return \r8\HTML\Tag Returns a self reference
     */
    public function setTag ( $tag )
    {
        $tag = strtolower( \r8\str\stripW($tag) );

        if ( \r8\isEmpty($tag) )
            throw new \r8\Exception\Argument(0, "Tag", "Must not be empty");

        $this->tag = $tag;

        return $this;
    }

    /**
     * Sets whether this instance should be rendered as empty
     *
     * @param Boolean $setting
     * @return \r8\HTML\Tag Returns a self reference
     */
    public function setEmpty ( $setting )
    {
        $this->empty = $setting ? TRUE : FALSE;
        return $this;
    }

    /**
     * Clears the empty override and reverts to automatic detection
     *
     * @return \r8\HTML\Tag Returns a self reference
     */
    public function clearEmpty ()
    {
        $this->empty = null;
        return $this;
    }

    /**
     * Returns whether the current tag will be rendered as empty
     *
     * @return Boolean
     */
    public function isEmpty ()
    {
        // If the have defined an override, return it
        if ( isset($this->empty) )
            return $this->empty;

        // If there is content, we will never render as empty
        else if ( $this->hasContent() )
            return FALSE;

        // Otherwise, determine it by the type of tag being represented
        return in_array(
                $this->tag,
                array('area', 'base', 'br', 'frame', 'hr', 'img', 'input', 'link', 'meta', 'param')
            );
    }

    /**
     * Returns the list of attributes associated with this instance
     *
     * @return Array
     */
    public function getAttrs ()
    {
        return $this->attrs;
    }

    /**
     * Returns whether there are any attributes set
     *
     * @return Boolean
     */
    public function hasAttrs ()
    {
        return count( $this->attrs ) > 0 ? TRUE : FALSE;
    }

    /**
     * Sets a specific HTML attribute
     *
     * @param String $attr The attribute being set
     * @param mixed $value The value of this attribute
     *      Setting this to boolean true (or not passing it as an argument),
     *      marks this attribute as a boolean value
     * @param \r8\HTML\Tag Returns a self reference
     */
    public function setAttr ( $attr, $value = TRUE )
    {
        $this->attrs[ self::normalizeAttrName( $attr ) ] = \r8\reduce($value);
        return $this;
    }

    /**
     * Returns whether an attribute has been set
     *
     * @param String $attr The attribute to test
     * @return Boolean
     */
    public function attrExists ( $attr )
    {
        return array_key_exists( self::normalizeAttrName( $attr ), $this->attrs );
    }

    /**
     * Clears an attribute from being set
     *
     * @param String $attr The attribute to test
     * @param \r8\HTML\Tag Returns a self reference
     */
    public function unsetAttr ( $attr )
    {
        $attr = self::normalizeAttrName( $attr );
        unset( $this->attrs[ $attr ] );
        return $this;
    }

    /**
     * Sets a specific HTML attribute
     *
     * @param String $attr The attribute to fetch
     * @param mixed Returns the value of the attribute. Returns null if it isn't set
     */
    public function getAttr ( $attr )
    {
        if ( !$this->attrExists($attr) )
            return null;

        return $this->attrs[ self::normalizeAttrName( $attr ) ];
    }

    /**
     * Imports a list of attributes in to this instance
     *
     * @param Array $attrs The list of attributes to import
     * @return \r8\HTML\Tag Returns a self reference
     */
    public function importAttrs ( array $attrs )
    {
        foreach ( $attrs AS $key => $value ) {
            $this->setAttr( $key, $value );
        }
        return $this;
    }

    /**
     * Removes all attributes from this instance
     *
     * @return \r8\HTML\Tag Returns a self reference
     */
    public function clearAttrs ()
    {
        $this->attrs = array();
        return $this;
    }

    /**
     * Array access method... wraps attrExists
     *
     * @param String $attr The attribute to test
     * @return Boolean
     */
    public function offsetExists ( $offset )
    {
        return $this->attrExists( $offset );
    }

    /**
     * Array access method... wraps getAttr
     *
     * @param String $attr The attribute to return
     * @return mixed
     */
    public function offsetGet ( $offset )
    {
        return $this->getAttr($offset);
    }

    /**
     * Array access method... wraps setAttr
     *
     * @param String $attr The attribute to set
     * @param mixed $value The value for the attribute
     * @return null
     */
    public function offsetSet ( $offset, $value )
    {
        $this->setAttr($offset, $value);
    }

    /**
     * Array access method... wraps unsetAttr
     *
     * @param String $attr The attribute to clear
     * @return null
     */
    public function offsetUnset ( $offset )
    {
        return $this->unsetAttr($offset);
    }

    /**
     * Returns a string of the attributes in this instance
     *
     * @return String
     */
    public function getAttrString ()
    {
        $attrs = array();
        $flags = array();

        foreach ( $this->attrs AS $label => $data) {

            // If it equals TRUE, that means it's a flag
            if ( $data === TRUE )
                $flags[] = $label;
            else
                $attrs[] = $label ."=". self::quoteAttr($data);

        }

        return implode(" ", array_merge($attrs, $flags));
    }

    /**
     * Returns a string representation of the open part of the tag
     *
     * This will return something like:  <a href='#'>
     *
     * @return String An opening HTML tag
     */
    public function getOpenTag ()
    {
        return '<'. $this->tag . ( $this->hasAttrs() ? ' '. $this->getAttrString() : '') .'>';
    }

    /**
     * Returns the html to close this tag
     *
     * This will return something like: </a>
     *
     * @return String A closing HTML tag
     */
    public function getCloseTag ()
    {
        return '</'. $this->tag .'>';
    }

    /**
     * Returns an empty HTML tag
     *
     * This will return something like: <input name='firstName' />
     *
     * @return String Returns an empty HTML tag
     */
    public function getEmptyTag ()
    {
        return '<'. $this->tag . ( $this->hasAttrs() ? ' '. $this->getAttrString() : '') .' />';
    }

    /**
     * Returns the HTML string represented by this instance
     *
     * @return String Returns a string of HTML
     */
    public function render ()
    {
        if ( $this->isEmpty() )
            return $this->getEmptyTag();
        else
            return $this->getOpenTag() . $this->getContent() . $this->getCloseTag();
    }

}

