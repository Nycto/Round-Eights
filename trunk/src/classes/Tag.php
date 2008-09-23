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
     * Sets the value of the tag in this instace
     *
     * @param String
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
    
}

?>