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
        
        $this->setOpenQuote( $openQuote )
            ->setCloseQuote( $closeQuote );
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
    
    /**
     * Returns the open quote string
     *
     * @return String|null Returns null if there is no open quote set
     */
    public function getOpenQuote ()
    {
        return $this->openQuote;
    }
    
    /**
     * Sets the open quote
     *
     * @param String $quote The new open quote
     * @return Object Returns a self reference
     */
    public function setOpenQuote ( $quote )
    {
        $this->openQuote = is_null( $quote ) ? null : ::cPHP::strval( $quote );
        return $this;
    }
    
    /**
     * Unsets the open quote from this instance
     *
     * @return Object Returns a self reference
     */
    public function clearOpenQuote ()
    {
        $this->openQuote = null;
        return $this;
    }
    
    /**
     * Returns whether this instance has an open quote
     *
     * @return Boolean
     */
    public function openQuoteExists ()
    {
        return isset($this->openQuote);
    }
    
    /**
     * Returns the close quote string
     *
     * @return String|null Returns null if there is no close quote set
     */
    public function getCloseQuote ()
    {
        return $this->closeQuote;
    }
    
    /**
     * Sets the close quote
     *
     * @param String $quote The new close quote
     * @return Object Returns a self reference
     */
    public function setCloseQuote ( $quote )
    {
        $this->closeQuote = is_null( $quote ) ? null : ::cPHP::strval( $quote );
        return $this;
    }
    
    /**
     * Unsets the close quote from this instance
     *
     * @return Object Returns a self reference
     */
    public function clearCloseQuote ()
    {
        $this->closeQuote = null;
        return $this;
    }
    
    /**
     * Returns whether this instance has an close quote
     *
     * @return Boolean
     */
    public function closeQuoteExists ()
    {
        return isset($this->closeQuote);
    }
    
    /**
     * Returns the value of this instance
     *
     * @return String
     */
    public function __toString ()
    {
        return $this->getOpenQuote() . $this->getContent() . $this->getCloseQuote();
    }
}

?>