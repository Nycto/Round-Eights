<?php
/**
 * HTML Tag Creator
 *
 * @package tag
 */

namespace cPHP;

/**
 * Allows for the creation, manipulation and display of an HTML tag
 *
 * This is not meant as a replacement for the DOMXML extension. It is meant
 * as a supplement. Sometimes, DOMXML is overkill. The goal for this class
 * is to contain a single tag.
 */
class Tag
{

    /**
     * The tag that this instance represents
     */
    protected $tag;

    /**
     * Whether or not this tag is empty
     */
    protected $empty;

    /**
     * The attributes for this tag
     */
    protected $attrs = array();

    /**
     * The contents of this tag
     */
    protected $content;
    
    /**
     * Takes an HTML attribute and strips it down
     *
     * @throws cPHP::Exception::Data::Argument Thrown when the attribute name is empty
     * @param String $attr The name of the attribute
     * @return String The normalized version of the attribute name
     */
    static public function normalizeAttrName ( $attr )
    {
        $attr = strtolower( ::cPHP::stripW($attr) );
        
        if ( empty($attr) )
            throw new ::cPHP::Exception::Data::Argument( 0, "Attribute Name", "Must not be empty" );
        
        return $attr;
    }
    
    /**
     * Constructor...
     *
     * @param String $tag The tag contained in this instance
     */
    public function __construct ( $tag, $content = null )
    {
        $this->setTag( $tag );
        $this->setContent( $content );
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
            throw new ::cPHP::Exception::Data::Argument(0, "Tag", "Must not be empty");

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
        return $this->attrs;
    }
    
    /**
     * Sets a specific HTML attribute
     *
     * @param String $attr The attribute being set
     * @param object Returns a self reference
     */
    public function setAttr ( $attr, $value )
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
    public function isAttrSet ( $attr )
    {
        return array_key_exists( self::normalizeAttrName( $attr ), $this->attrs );
    }
    
    /**
     * Clears an attribute from being set
     *
     * @param String $attr The attribute to test
     * @param object Returns a self reference
     */
    public function clearAttr ( $attr )
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
        if ( !$this->isAttrSet($attr) )
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
        $attrs = ::cPHP::Ary::create( $attrs );
        foreach ( $attrs AS $key => $value ) {
            $this->setAttr( $key, $value );
        }
        return $this;
    }
    
}

?>