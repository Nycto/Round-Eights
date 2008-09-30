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
class Unquoted
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
    
}

?>