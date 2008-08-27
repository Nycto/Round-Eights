<?php
/**
 * The base Exception Class
 *
 * @package Exception
 */

namespace cPHP;

/**
 * The base exception class
 */
class Exception extends ::Exception
{

    /**
     * Whether to give full or restricted information in an exception string
     * This is good to turn off for production environments
     */
    static protected $verbose = TRUE;

    /**
     * The name of the exception
     */
    protected $exception = "General Exception";

    /**
     * A description of this Exception
     */
    protected $description = "A General Error Occured";

    /**
     * Identifies the offset in the backtrace that caused the problem
     */
    protected $fault;

    /**
     * Set the global verbosity of the errors
     *
     * @param Boolean $setting
     */
    static public function setVerbosity ($setting)
    {
        self::$verbose = $setting ? true : false;
    }
    
    /**
     * Returns the current global verbosity state
     *
     * @return Boolean
     */
    static public function getVerbosity ()
    {
        return self::$verbose;
    }

    /**
     * Constructor...
     *
     * @param String $message The error message
     * @param Integer $code The error code
     * @param Integer $fault The backtrace offset that caused the error
     */
    public function __construct ( $message = NULL, $code = 0, $fault = NULL )
    {
        parent::__construct($message, $code);

        if ( !is_bool($fault) && !is_null($fault) )
            $this->setFault($fault);
    }

    /**
     * Returns the trace string for a given offset
     *
     * @param integer $offset The offset of the trace
     * @param integer $wrapFlag The offset wrapping mode to use
     * @return array A list of the backtrace details at the given offset
     */
    public function getTraceByOffset ($offset, $wrapFlag = cPHP::Ary::OFFSET_RESTRICT)
    {
        $trace = $this->getTrace();
        if (count($trace) <= 0)
            return FALSE;
        
        $trace = new cPHP::Ary( $trace );
        
        return new cPHP::Ary(
                $trace->offset($offset, $wrapFlag)
            );
    }

    /**
     * Returns the length of the trace
     *
     * @return integer
     */
    public function getTraceCount ()
    {
        return count($this->getTrace());
    }

    /**
     * Returns whether the message is set
     *
     * @return Boolean
     */
    public function issetMessage ()
    {
        return !is_empty($this->getMessage());
    }

    /**
     * Returns Boolean whether the message is set
     *
     * @return Boolean
     */
    public function issetCode ()
    {
        return !is_empty($this->getCode());
    }

    /**
     * Sets the fault of the exception
     *
     * @param Integer $offset The offset at fault for the current exception
     * @param integer $wrapFlag The offset wrapping mode to use
     * @return object Returns a self reference
     */
    public function setFault ( $offset, $wrapFlag = cPHP::Ary::OFFSET_RESTRICT )
    {
        $trace = $this->getTrace();

        if (count($trace) <= 0)
            return FALSE;

        $trace = new cPHP::Ary( $trace );
        
        $this->fault = $trace->calcOffset( $offset, $wrapFlag);
        
        return $this;
    }

    /**
     * Unset the fault offset
     *
     * @return object Returns a self reference
     */
    public function unsetFault ()
    {
        unset($this->fault);
        return $this;
    }

    /**
     * Returns whether the fault is set
     *
     * @return Boolean
     */
    public function issetFault ()
    {
        return isset($this->fault);
    }

    /**
     * Returns the fault offset
     *
     * @return Integer|Boolean Returns the offset, or FALSE if no fault is set
     */
    public function getFaultOffset ()
    {
        if (!isset($this->fault))
            return FALSE;
        return $this->fault;
    }

    /**
     * Sets the fault relative to it's current value
     *
     * @param Integer
     * @return object Returns a self reference
     */
    public function shiftFault ($shift = -1)
    {
        $shift = intval(reduce($shift));

        $trace = new cPHP::Ary( $this->getTrace() );

        if (count($trace) <= 0)
            return FALSE;

        if ( !$this->issetFault() )
            $fault = -1;
        else
            $fault = $this->getFaultOffset();

        $fault += $shift;

        $fault = $trace->calcOffset($fault, cPHP::Ary::OFFSET_RESTRICT);

        return $this->setFault($fault);
    }

    /**
     * Returns the fault trace
     *
     * @return array Returns the details for the fault of the current exception
     */
    public function getFault ()
    {
        if ( !$this->issetFault() )
            return FALSE;
        
        return $this->getTraceByOffset( $this->getFaultOffset() );
    }

    /**
     * Returns a string detailing a trace offset
     *
     * @param Integer $offset The backtrace offset to generate a string for
     * @param Integer $wrapFlag
     * @return String
     */
    public function getTraceOffsetString ($offset, $wrapFlag = cPHP::Ary::OFFSET_RESTRICT)
    {
        
        $trace = $this->getTraceByOffset($offset, $wrapFlag);

        $args = Array();
        foreach ($trace['args'] AS $arg) {
            $args[] = getDump($arg);
        }

        return (array_key_exists('file', $trace)?"  File: ". $trace['file'] ."\n":"")
            .(array_key_exists('line', $trace)?"  Line: ". $trace['line'] ."\n":"")
            .(array_key_exists('function', $trace)?"  Function: ". $trace['function'] ."\n":"")
            .(count($args) > 0?"  Func Args: ". implode(", ", $args) ."\n":"");
    }

    /**
     * Returns a string detailing a trace offset
     * 
     * @param Integer $offset The backtrace offset to generate a string for
     * @param Integer $wrapFlag
     * @return String A string of HTML
     */
    public function getTraceOffsetHTML ($offset, $wrapFlag = RESTRICT)
    {
        $trace = $this->getTraceByOffset($offset, $wrapFlag);

        if ($trace === FALSE)
            return NULL;

        $args = Array();
        foreach ($trace['args'] AS $arg) {
            $args[] = getDump($arg);
        }

        return "<dl>"
            .(array_key_exists('file', $trace)?"<dt>File</dt><dd>". $trace['file'] ."</dd>":"")
            .(array_key_exists('line', $trace)?"<dt>Line</dt><dd>". $trace['line'] ."</dd>":"")
            .(array_key_exists('function', $trace)?"<dt>Function</dt><dd>". $trace['function'] ."</dd>":"")
            .(count($args) > 0?"<dt>Func Args</dt><dd>". implode("</dd><dd>", $args) ."</dd>":"")
            ."</dl>";
    }

    /**
     * Returns the fault information as a string
     *
     * @return String
     */
    public function getFaultString ()
    {
        $fault = $this->getFaultOffset();
        if ($fault === FALSE)
            return NULL;
        return "Caused By:\n". $this->getTraceOffsetString($fault);
    }

    /**
     * Returns the fault information as an HTML formatted string
     *
     * @return String An HTML string
     */
    public function getFaultHTML ()
    {
        $fault = $this->getFaultOffset();
        if ($fault === FALSE)
            return NULL;
        return "<b>Caused By</b>". $this->getOffsetHTML($fault);
    }

    /**
     * Returns specifics about this exception
     */
    public function getDetailsString ()
    {

        if (!$this->issetMessage() && !$this->issetCode() && !$this->issetData() && !$this->issetLabel())
            return NULL;
        else
            return "Details:\n"
                .($this->issetCode()?"  Code: ". $this->getCode() ."\n":"")
                .($this->issetLabel()?"  ". $this->label_string .": ". $this->getLabel() ."\n":"")
                .($this->issetData()?"  ". $this->data_string .": ". $this->getDataString() ."\n":"")
                .($this->issetMessage()?"  Message: ". $this->getMessage() ."\n":"");
    }

    /**
     * Returns specifics about this exception rendered as HTML
     */
    public function getDetailsHTML ()
    {

        if (!$this->issetMessage() && !$this->issetCode() && !$this->issetData() && !$this->issetLabel())
            return NULL;
        else
            return "<b>Details:</b>"
                ."<dl>"
                .($this->issetCode()?"<dt>Code</dt><dd>". $this->getCode() ."</dd>":"")
                .($this->issetLabel()?"<dt>". $this->label_string ."</dt><dd>". $this->getLabel() ."</dd>":"")
                .($this->issetData()?"<dt>". $this->data_string ."</dt><dd>". $this->getDataString() ."</dd>":"")
                .($this->issetMessage()?"<dt>Message</dt><dd>". $this->getMessage() ."</dd>":"")
                ."</dl>";
    }

    /**
     * Get a string detailing where the exception was thrown
     *
     * @return string
     */
    public function getThrownString ()
    {
        return "Thrown At:\n"
            ."  File: ". $this->getFile() ."\n"
            ."  Line: ". $this->getLine() ."\n";
    }

    /**
     * Returns the HTML for displaying the thrown information
     *
     * @return string an HTML string
     */
    public function getThrownHTML ()
    {
        return "<strong>Thrown At:</strong>"
            ."<dl>"
                ."<dt>File</dt><dd>". $this->getFile() ."</dd>"
                ."<dt>Line</dt><dd>". $this->getLine() ."</dd>"
            ."</dl>";
    }

    /**
     * Return a string representing the exception class
     *
     * @return String
     */
    public function getClassString ()
    {
        $exc = isset($this->exception) && !is_empty($this->exception);
        $desc = isset($this->description) && !is_empty($this->description);

        if ($exc && $desc)
            return $this->exception ." (". $this->description .")";
        else if ($exc)
            return $this->exception;
        else if ($desc)
            return $this->description;
        else
            return NULL;
    }

    /**
     * Returns the stack trace formatted as HTML
     */
    public function getTraceHTML ()
    {
        return "<ol>". preg_replace('/^\#[0-9]+\s+(.+)$/m', '<li>\1</li>', $this->getTraceAsString() ) ."</ol>";
    }

    /**
     * Returns a verbose string detailing this exception
     */
    public function getVerboseString ()
    {
        return "Exception Thrown: ". $this->getClassString() ."\n"
            .$this->getDetailsString()
            .$this->getFaultString()
            .$this->getThrownString()
            ."Full Stack Trace:\n  "
            .str_replace( "\n", "\n  ", $this->getTraceAsString() );
    }

    /**
     * Returns the HTML for displaying this error
     */
    public function getVerboseHTML ()
    {
        return "<dl><dt>Exception Thrown</dt><dd>". $this->getClassString() ."</dd></dl>\n"
            .$this->getDetailsHTML() ."\n"
            .$this->getFaultHTML() ."\n"
            .$this->getThrownHTML() ."\n"
            ."<b>Full Stack Trace:</b>" .$this->getTraceHTML() ."\n";
    }

    /**
     * Returns a short string detailing this exception
     */
    public function getShortString ()
    {
        return "Exception Thrown: ". $this->getClassString() ."\n";
    }

    /**
     * Return the HTML version
     */
    public function getShortHTML ()
    {
        return "<p>". $this->getShortString() ."</p>";
    }

    /**
     * Returns a string about this exception
     */
    public function __toString ()
    {
        if (self::$verbose)
            return $this->getVerboseString();
        else
            return $this->getShortString();
    }

    /**
     * Returns the HTML for this exception
     */
    public function toHTML ()
    {
        if (self::$verbose)
            return $this->getVerboseHTML();
        else
            return $this->getShortHTML();
    }

}

?>