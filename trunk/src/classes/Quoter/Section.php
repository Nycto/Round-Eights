<?php
/**
 * Quote parsing result class
 *
 * @package Quoter
 */

namespace cPHP::Quoter;

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
     * In the grand scheme of the original string, this is the offset
     * of the content
     */
    private $offset;
    
    /**
     * Constructor...
     *
     * @param Integer $offset The offset of the content in the scope of the original string
     * @param String $content The string content of this section
     */
    public function __construct( $offset, $content )
    {
        $offset = intval($offset);
        if ( $offset < 0 )
            throw new ::cPHP::Exception::Argument( 0, "Offset", "Must not be less than zero");
        $this->offset = $offset;
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
        $this->content = is_null($content) ? null : ::cPHP::strval( $content );
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
     * @param Integer $flags Any boolean flags to set. See cPHP::is_empty
     * @return Boolean
     */
    public function isEmpty ( $flags = 0 )
    {
        return ::cPHP::is_empty( $this->content, $flags );
    }
    
    /**
     * Returns the offset of the content in this string
     *
     * @return Integer
     */
    public function getOffset ()
    {
        return $this->offset;
    }

    /**
     * To be overwriten, converts this value in to a string
     *
     * @return String
     */
    abstract public function __toString();
}

?>