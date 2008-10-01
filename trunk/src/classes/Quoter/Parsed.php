<?php
/**
 * Quote parsing result class
 *
 * @package Quoter
 */

namespace cPHP::Quoter;

/**
 * Representation a collection of parsed string sections
 */
class Parsed
{
    
    /**
     * The sections represented by this instance
     */
    private $sections = array();
    
    /**
     * Whether the iterator functionality should include the quoted objects
     */
    private $quoted = TRUE;
    
    /**
     * Whether the iterator functionality should include the unquoted objects
     */
    private $unquoted = TRUE;
    
    /**
     * Returns a list of all the sections in this instance
     *
     * @return Object Returns a cPHP::Ary object
     */
    public function getSections ()
    {
        return new ::cPHP::Ary( $this->sections );
    }
    
    /**
     * Adds a new section to the end of this list
     *
     * @param Object $section The section being added
     * @return Object Returns a self reference
     */
    public function addSection( cPHP::Quoter::Section $section )
    {
        $this->sections[] = $section;
        return $this;
    }
    
    /**
     * Converts all the contained sections to strings and concatenates them
     *
     * @return String
     */
    public function __toString ()
    {
        $result = "";
        foreach ( $this->sections AS $section ) {
            $result .= $section->__toString();
        }
        return $result;
    }
    
    /**
     * Returns whether the quoted values will included in the advanced functionality
     *
     * @return Boolean
     */
    public function getIncludeQuoted ()
    {
        return $this->quoted;
    }
    
    /**
     * Sets whether the quoted values should be included in the advanced functionality
     *
     * @return Object returns a self reference
     */
    public function setIncludeQuoted ( $setting )
    {
        $this->quoted = $setting ? TRUE : FALSE;
        return $this;
    }
    
}

?>