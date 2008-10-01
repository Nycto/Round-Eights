<?php
/**
 * Quote parsing result class
 *
 * @package Quoter
 */

namespace cPHP::Quoter::Section;

/**
 * Representation of an unquoted section of a string
 */
class Unquoted extends cPHP::Quoter::Section
{
    
    /**
     * Returns whether the current section is quoted
     *
     * @return Boolean
     */
    public function isQuoted ()
    {
        return false;
    }
    
    /**
     * Returns the string value of this instance
     *
     * @return String
     */
    public function __toString()
    {
        return $this->getContent();
    }
    
}

?>