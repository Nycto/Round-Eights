<?php
/**
 * Quote parsing result class
 *
 * @package Quoter
 */

namespace cPHP::Quoter::Section;

/**
 * Representation of a quoted section of the parsed string
 */
class Quoted extends ::cPHP::Quoter::Section
{
    
    /**
     * The string quote that opened 
     */
    protected $openQuote;
    
    /**
     * The string that closed the section
     */
    protected $closeQuote;
    
    /**
     * Constructor...
     *
     * @param Integer $offset The offset of the content in the scope of the original string
     * @param String $content The string content of this section
     * @param String $openQuote The open quote
     * @param String $closeQuote The quote that closed this section
     */
    public function __construct( $offset, $content, $openQuote, $closeQuote )
    {
        parent::__construct( $offset, $content );
    }
    
    /**
     * Returns whether the current section is quoted
     *
     * @return Boolean
     */
    public function isQuoted ()
    {
        return true;
    }
}

?>