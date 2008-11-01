<?php
/**
 * HTML Tag Creator
 *
 * PHP version 5.3
 *
 * This source file is subject to version 2.0 of the Artistic License. A copy
 * of the license should have been bundled with this source file inside a file
 * named LICENSE.txt. It is also available through the world-wide-web at one
 * of the following URIs:
 * http://www.commonphp.com/license.php
 * http://www.opensource.org/licenses/artistic-license-2.0.php
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @author James Frasca <james@commonphp.com>
 * @license Artistic License 2.0 http://www.commonphp.com/license.php
 * @package Tag
 */

namespace cPHP;

/**
 * Allows for the creation, manipulation and display of an HTML tag
 *
 * This is not meant as a replacement for the DOMXML extension. It is meant
 * as a supplement. Sometimes, DOMXML is overkill. The goal for this class
 * is to contain a single tag.
 */
class Tag implements ArrayAccess
{

    /**
     * The tag that this instance represents
     */
    protected $tag;

    /**
     * Whether or not this tag is empty.
     *
     * This is actually an override. The class will try to determine if a tag
     * should be empty by looking at whether it has content and, then the type
     * of tag.
     */
    protected $empty;

    /**
     * The attributes for this tag
     */
    protected $attrs = array();

    /**
     * The content of this tag
     */
    protected $content;

    /**
     * Allows a tag instance to be created by calling a method with the tag name.
     *
     * @param string $func The function called statically, which will be used as the tag name
     * @param array $args Any args passed to the function call.
     *      Offset 0 will be used as the content, offset 1 as the attributes
     * @return Object Returns a new cPHP::Tag object
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
     * @throws cPHP::Exception::Argument Thrown when the attribute name is empty
     * @param String $attr The name of the attribute
     * @return String The normalized version of the attribute name
     */
    static public function normalizeAttrName ( $attr )
    {
        $attr = strtolower( ::cPHP::stripW($attr) );

        if ( empty($attr) )
            throw new ::cPHP::Exception::Argument( 0, "Attribute Name", "Must not be empty" );

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
        return '"'. htmlspecialchars( ::cPHP::strval( $string ) ) .'"';
    }

    /**
     * Constructor...
     *
     * @param String $tag The tag this instance represents
     * @param String $content Any content for this instance
     * @param Array $attrs Any attributes to load in
     * @return null
     */
    public function __construct ( $tag, $content = null, $attrs = array() )
    {
        $this->setTag( $tag );
        $this->setContent( $content );
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
     * @return object Returns a self reference
     */
    public function setTag ( $tag )
    {
        $tag = strtolower( ::cPHP::stripW($tag) );

        if ( ::cPHP::is_empty($tag) )
            throw new ::cPHP::Exception::Argument(0, "Tag", "Must not be empty");

        $this->tag = $tag;

        return $this;
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
     * @return object Returns a self reference
     */
    public function setContent ( $content )
    {
        $content = ::cPHP::strval($content);
        $this->content = empty($content) && $content !== "0" ? null : $content;
        return $this;
    }

    /**
     * Adds content to the end of the existing content
     *
     * @param string $content
     * @return object Returns a self reference
     */
    public function appendContent ( $content )
    {
        $content = ::cPHP::strval($content);
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
     * @return object Returns a self reference
     */
    public function clearContent ()
    {
        $this->content = null;
        return $this;
    }

    /**
     * Sets whether this instance should be rendered as empty
     *
     * @param Boolean $setting
     * @return object Returns a self reference
     */
    public function setEmpty ( $setting )
    {
        $this->empty = $setting ? TRUE : FALSE;
        return $this;
    }

    /**
     * Clears the empty override and reverts to automatic detection
     *
     * @return object Returns a self reference
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
        return new ::cPHP::Ary( $this->attrs );
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
     * @param object Returns a self reference
     */
    public function setAttr ( $attr, $value = TRUE )
    {
        $this->attrs[ self::normalizeAttrName( $attr ) ] = ::cPHP::reduce($value);
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
     * @param object Returns a self reference
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
     * @param mixed $attrs The list of attributes to import
     * @return object Returns a self reference
     */
    public function importAttrs ( $attrs )
    {
        if ( is_array($attrs) )
            $attrs = ::cPHP::Ary::create( $attrs );
        else if ( !( $attrs instanceof Traversable ) )
            throw new ::cPHP::Exception::Argument( 0, "Attribute List", "Must be an array or a traversable object" );

        foreach ( $attrs AS $key => $value ) {
            $this->setAttr( $key, $value );
        }
        return $this;
    }

    /**
     * Removes all attributes from this instance
     *
     * @return object Returns a self reference
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
    public function __toString ()
    {
        if ( $this->isEmpty() )
            return $this->getEmptyTag();
        else
            return $this->getOpenTag() . $this->getContent() . $this->getCloseTag();
    }
}

?>