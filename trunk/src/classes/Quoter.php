<?php
/**
 * String parsing class
 *
 * @package QuoteParser
 */

namespace cPHP;

/**
 * Parses a string and splits it in to a list of quoted and unquoted sections
 */
class Quoter
{
    
    /**
     * The escape character in the string
     */
    protected $escape = "\\";

    /**
     * An array of strings that represent quotes
     *
     * This is a multidimensional array. The key of the first dimension
     * is the opening quote character. The second dimension is a list of characters
     * that are allowed to close the opening quote
     */
    protected $quotes = array(
            "'" => array( "'" ),
            '"' => array( '"' )
        );
    
    /**
     * Returns the list of quotes registered in this instance
     *
     * This returns a multidimensional array. The key of the first dimension
     * is the opening quote character. The second dimension is a list of characters
     * that are allowed to close the opening quote
     *
     * @return Object Returns a cPHP::Ary object
     */
    public function getQuotes ()
    {
        return new ::cPHP::Ary( $this->quotes );
    }
    
    /**
     * Clears the list of quotes in this instance
     *
     * @return object Returns a self reference
     */
    public function clearQuotes ()
    {
        $this->quotes = array();
        return $this;
    }
    
    /**
     * Registers a set of quotes
     *
     * If the opening quote has already been registered, the closing quotes will
     * be replaced with the new set
     *
     * @param String $open The opening quote
     * @param Null|String|Array If left empty, this will assume the closing quote
     *      is the same as the opening quote. If an array is given, it will be
     *      flattened and compacted.
     * @return object Returns a self reference
     */
    public function setQuote ( $open, $close = FALSE )
    {
        $open = ::cPHP::strval( $open );
        
        if ( ::cPHP::is_empty($open, ALLOW_SPACES) )
            throw new ::cPHP::Exception::Argument( 0, "Open Quote", "Must not be empty" );
        
        if ( ::cPHP::is_vague( $close, ALLOW_SPACES ) ) {
            $close = array( $open );
        }
        else {
            
            $close = new ::cPHP::Ary( $close );
            $close->flatten()->collect("::cPHP::strval")->compact( ALLOW_SPACES )->unique();
            
            var_dump( $close->get() );
        }
        
        $this->quotes[ $open ] = $close;
        
        return $this;
    }
    
    /**
     * Returns a flat list of all the open and close quotes registered in this instance
     *
     * @return array
     */
    public function getAllQuotes ()
    {
        
    }
    
}

?>