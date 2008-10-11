<?php
/**
 * Exception Class
 *
 * @package Exception
 */

namespace cPHP::Exception;

/**
 * Exception class to handle bad arguments
 */
class Argument extends ::cPHP::Exception
{
    
    /**
     * The title of this exception
     */
    const TITLE = "Argument Error";
    
    /**
     * A brief description of this error type
     */
    const DESCRIPTION = "Errors caused by faulty arguments";

    /**
     * The offset of the argument at fault
     */
    protected $arg;

    /**
     * Constructor
     *
     * @param Integer $arg The argument offset (from 0) that caused the error
     * @param String $label The name of the argument
     * @param String $message The error message
     * @param Integer $code The error code
     * @param Integer $fault The backtrace offset that caused the error
     */
    public function __construct($arg, $label = NULL, $message = NULL, $code = 0, $fault = 0)
    {
        $this->setFault($fault);
        
        if ( !::cPHP::is_vague($label) )
            $this->addData("Arg Label", $label);
            
        if ( !::cPHP::is_vague($message) )
            $this->message = $message;
            
        if ( !::cPHP::is_vague( $code ) )
            $this->code = $code;
            
        if (!::cPHP::is_vague($arg, ::cPHP::ALLOW_ZERO) && $this->getTraceCount() > 0)
            $this->setArg($arg);
    }

    /**
     * Identify the argument that caused the problem
     *
     * @param Integer $offset
     * @param Integer $wrapFlag
     * @return object Returns a self reference
     */
    public function setArg ( $offset, $wrapFlag = ::cPHP::Ary::OFFSET_RESTRICT )
    {
        // If the fault isn't set, default to the end of the trace
        if ( !$this->issetFault())
            $this->setFault(0);
        
        $fault = $this->getFault();
        
        if ($fault === FALSE || !$fault->keyExists('args') || !is_array($fault['args']))
            trigger_error("Error fetching fault trace arguments", E_USER_ERROR);

        if (count($fault['args']) <= 0)
            return $this->unsetArg();
        
        $fault['args'] = new ::cPHP::Ary($fault['args']);

        $offset = $fault['args']->calcOffset($offset, $wrapFlag);

        if (is_int($offset))
            $this->arg = $offset;
        else
            unset($this->arg);

        return $this;
    }

    /**
     * Boolean whether or not an argument is set
     *
     * @return Boolean
     */
    public function issetArg ()
    {
        return isset($this->arg);
    }
    
    /**
     * Unsets the argument pointer
     *
     * @return object Returns a self reference
     */
    public function unsetArg ()
    {
        $this->arg = NULL;
        return $this;
    }

    /**
     * Get the integer offset of the problem integer
     *
     * @return Integer|NULL Returns null if the argument isn't set
     */
    public function getArgOffset ()
    {
        if ( !$this->issetArg() )
            return FALSE;
        else
            return $this->arg;
    }

    /**
     * Change the fault
     *
     * @param Integer $offset The new fault
     * @param Integer $arg If a new argument is responsible, it can be set here
     * @return Object Returns a self reference
     */
    public function setFault ($offset, $arg = NULL)
    {
        parent::setFault($offset);

        if (!::cPHP::is_vague($arg, ::cPHP::ALLOW_ZERO))
            $this->setArg( $arg );
        
        else if ( $this->issetArg() )
            $this->setArg( $this->arg );

        return $this;
    }
    
    /**
     * Unsets the fault
     *
     * @return Object Returns a self reference
     */
    public function unsetFault ()
    {
        $this->unsetArg();
        return parent::unsetFault();
    }

    /**
     * Get the value of the argument at fault
     *
     * @return mixed
     */
    public function getArgData ()
    {
        if ( !$this->issetArg() )
            return NULL;
        
        $trace = $this->getFault();
        if (count($trace['args']) <= 0)
            return NULL;
        
        return $trace['args'][ $this->getArgOffset() ];
    }

    /**
     * Returns specifics about this exception
     *
     * @return String
     */
    public function getDetailsString ()
    {
        if (!$this->issetMessage() && !$this->issetCode() && count( $this->data ) <= 0 )
            return NULL;
        
        $data = array();
        foreach ( $this->data AS $key => $value )
            $data[] = $key .": ". $value;
        
        return "Details:\n"
                .( $this->issetCode() ? "  Code: ". $this->getCode() ."\n" : "" )
                .( $this->issetMessage() ? "  Message: ". $this->getMessage() ."\n" : "" )
                .( $this->issetArg() ? "  Arg Offset: ". $this->getArgOffset() ."\n" : "" )
                .( $this->issetArg() ? "  Arg Value: ". ::cPHP::getDump( $this->getArgData() ) ."\n" : "" )
                .( count($data) > 0 ? "  ". implode("\n  ", $data) ."\n" : "" );
    }

    /**
     * Returns specifics about this exception rendered as HTML
     *
     * @return String
     */
    public function getDetailsHTML ()
    {
        if (!$this->issetMessage() && !$this->issetCode() && count( $this->data ) <= 0 )
            return NULL;
        
        $data = array();
        foreach ( $this->data AS $key => $value )
            $data[] = "<dt>". htmlspecialchars($key) ."</dt>"
                ."<dd>". $value ."</dd>";

        return
            "<div class='cPHP_Exception_Details'>\n"
            ."<h3>Details</h3>\n"
            ."<dl>\n"
            .($this->issetCode()?"<dt>Code</dt><dd>". $this->getCode() ."</dd>\n":"")
            .($this->issetMessage()?"<dt>Message</dt><dd>". $this->getMessage() ."</dd>\n":"")
            .( $this->issetArg() ? "<dt>Arg Offset</dt><dd>". $this->getArgOffset() ."</dd>\n" : "" )
            .( $this->issetArg() ? "<dt>Arg Value</dt><dd>". ::cPHP::getDump( $this->getArgData() ) ."</dd>\n" : "" )
            .( count($data) > 0 ? implode("\n", $data) ."\n" : "" )
            ."</dl>\n"
            ."</div>\n";
    }

}

?>