<?php
/**
 * The base Exception Class
 *
 * @license Artistic License 2.0
 *
 * This file is part of raindropPHP.
 *
 * raindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * raindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with raindropPHP. If not, see <http://www.raindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Exception
 */

namespace h2o;

/**
 * The base exception class
 */
class Exception extends \Exception
{

    /**
     * The name of the exception
     */
    const TITLE = "General Exception";

    /**
     * A description of this Exception
     */
    const DESCRIPTION = "General Errors";

    /**
     * Whether to give full or restricted information in an exception string
     * This is good to turn off for production environments
     */
    static protected $verbose = TRUE;

    /**
     * Identifies the offset in the backtrace that caused the problem
     */
    protected $fault;

    /**
     * Stores specific exception data. Each item has a label and a value
     */
    protected $data = array();

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

        if ( !\h2o\isVague($fault, \h2o\ALLOW_ZERO) )
            $this->setFault($fault);
    }

    /**
     * Invoking an exception will cause it to be thrown
     *
     * @param Integer $shift Passing an argument allws you to shift the fault
     */
    public function __invoke ( $shift = -1 )
    {
        $this->shiftFault( $shift );
        throw $this;
    }

    /**
     * Returns the trace string for a given offset
     *
     * @param integer $offset The offset of the trace
     * @param integer $wrapFlag The offset wrapping mode to use
     * @return array A list of the backtrace details at the given offset
     */
    public function getTraceByOffset ($offset, $wrapFlag = \h2o\num\OFFSET_RESTRICT)
    {
        $trace = $this->getTrace();
        if (count($trace) <= 0)
            return FALSE;

        return \h2o\ary\offset($trace, $offset, $wrapFlag);
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
        return !\h2o\isEmpty($this->getMessage());
    }

    /**
     * Returns Boolean whether the message is set
     *
     * @return Boolean
     */
    public function issetCode ()
    {
        return !\h2o\isEmpty($this->getCode());
    }

    /**
     * Sets the fault of the exception
     *
     * @param Integer $offset The offset at fault for the current exception
     * @param integer $wrapFlag The offset wrapping mode to use
     * @return object Returns a self reference
     */
    public function setFault ( $offset, $wrapFlag = \h2o\num\OFFSET_RESTRICT )
    {
        $trace = $this->getTrace();

        if (count($trace) <= 0)
            return $this;

        $this->fault = \h2o\ary\calcOffset($trace, $offset, $wrapFlag);

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
     * @param Integer $shift
     * @param Integer $wrapFlag
     * @return object Returns a self reference
     */
    public function shiftFault ($shift = 1, $wrapFlag = \h2o\ary\OFFSET_RESTRICT)
    {

        // Shifting the fault when no fault is set marks it to the end of the list
        if ( !$this->issetFault() )
            return $this->setFault(0);

        $shift = intval(reduce($shift));

        $trace = $this->getTrace();

        if (count($trace) <= 0)
            return FALSE;

        $fault = $this->getFaultOffset();

        $fault += $shift;

        $fault = \h2o\ary\calcOffset($trace, $fault, \h2o\ary\OFFSET_RESTRICT);

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
     * Adds a piece of data to this instance
     *
     * @param String $label The label of this data
     * @param mixed $data The actual data
     * @return object Returns a self reference
     */
    public function addData ( $label, $value )
    {
        $this->data[ trim(strval($label)) ] = $value;
        return $this;
    }

    /**
     * Returns the data list for the current instance
     *
     * @return object Returns an array object. Each entry is a piece of data with a label and value
     */
    public function getData ()
    {
        return $this->data;
    }

    /**
     * Returns the value of a specific piece of data
     *
     * @param String $label The label to fetch
     * @return mixed Returns the value. If it the requested data isn't set, NULL is returned
     */
    public function getDataValue ( $label )
    {
        $label = trim(strval($label));

        if ( array_key_exists($label, $this->data) )
            return $this->data[ $label ];
        else
            return NULL;
    }

    /**
     * Returns a string detailing a trace offset
     *
     * @param Integer $offset The backtrace offset to generate a string for
     * @param Integer $wrapFlag
     * @return String
     */
    public function getTraceOffsetString ($offset, $wrapFlag = \h2o\ary\OFFSET_RESTRICT)
    {

        $trace = $this->getTraceByOffset($offset, $wrapFlag);

        $args = Array();
        foreach ($trace['args'] AS $arg) {
            $args[] = \h2o\getDump($arg);
        }

        if ( $trace->keyExists('function') ) {
            $function = $trace['function'];

            if ( $trace->keyExists('type') )
                $function = $trace['type'] . $function;

            if ( $trace->keyExists('class') )
                $function = $trace['class'] . $function;

            $function .= "()";
        }
        else {
            $function = FALSE;
        }

        return ( $trace->keyExists('file') ?"  File: ". $trace['file'] ."\n" : "" )
            .( $trace->keyExists('line') ?"  Line: ". $trace['line'] ."\n" : "" )
            .( $function ? "  Function: ". $function ."\n" : "" )
            .( count($args) > 0 ? "  Arguments:\n    ". implode("\n    ", $args) ."\n" : "" );
    }

    /**
     * Returns a string detailing a trace offset
     *
     * @param Integer $offset The backtrace offset to generate a string for
     * @param Integer $wrapFlag
     * @return String A string of HTML
     */
    public function getTraceOffsetHTML ($offset, $wrapFlag = \h2o\ary\OFFSET_RESTRICT)
    {
        $trace = $this->getTraceByOffset($offset, $wrapFlag);

        $args = Array();
        foreach ($trace['args'] AS $arg) {
            $args[] = htmlspecialchars( \h2o\getDump($arg) );
        }

        if ( $trace->keyExists('function') ) {
            $function = $trace['function'];

            if ( $trace->keyExists('type') )
                $function = $trace['type'] . $function;

            if ( $trace->keyExists('class') )
                $function = $trace['class'] . $function;

            $function .= "()";
        }
        else {
            $function = FALSE;
        }

        return
            "<dl class='h2o_Exception_TraceItem'>\n"
            .( $trace->keyExists('file') ?
                    "<dt class='h2o_Exception_TraceItem_File'>File</dt>\n"
                    ."<dd class='h2o_Exception_TraceItem_File'>". htmlspecialchars($trace['file']) ."</dd>\n" : "" )
            .( $trace->keyExists('line') ?
                    "<dt class='h2o_Exception_TraceItem_Line'>Line</dt>\n"
                    ."<dd class='h2o_Exception_TraceItem_Line'>". htmlspecialchars($trace['line']) ."</dd>\n" : "" )
            .( $function ?
                    "<dt class='h2o_Exception_TraceItem_Func'>Function</dt>\n"
                    ."<dd class='h2o_Exception_TraceItem_Func'>". htmlspecialchars($function) ."</dd>\n" : "" )
            .( count($args) > 0 ?
                    "<dt class='h2o_Exception_TraceItem_Arg'>Arguments</dt>\n"
                    ."<dd class='h2o_Exception_TraceItem_Arg'>". implode("</dd><dd>", $args) ."</dd>\n" : "" )
            ."</dl>\n";

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

        return
            "<div class='h2o_Exception_Fault'>\n"
            ."<h3>Caused By</h3>\n"
            .$this->getTraceOffsetHTML($fault)
            ."</div>\n";
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
            "<div class='h2o_Exception_Details'>\n"
            ."<h3>Details</h3>\n"
            ."<dl>\n"
            .($this->issetCode()?"<dt>Code</dt><dd>". $this->getCode() ."</dd>\n":"")
            .($this->issetMessage()?"<dt>Message</dt><dd>". $this->getMessage() ."</dd>\n":"")
            .( count($data) > 0 ? implode("\n", $data) ."\n" : "" )
            ."</dl>\n"
            ."</div>\n";
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
        return
            "<div class='h2o_Exception_Thrown'>\n"
            ."<h3>Thrown At:</h3>\n"
            ."<dl>\n"
                ."<dt>File</dt>\n"
                ."<dd>". $this->getFile() ."</dd>\n"
                ."<dt>Line</dt>\n"
                ."<dd>". $this->getLine() ."</dd>\n"
            ."</dl>"
            ."</div>";
    }

    /**
     * Return a string representing the exception class
     *
     * @return String
     */
    public function getClassString ()
    {
        return static::TITLE ." (". static::DESCRIPTION .")";
    }

    /**
     * Returns a well formatted version of the stack trace
     *
     * @return String
     */
    public function getTraceString ()
    {
        $result = "";

        $length = count( $this->getTrace() );

        for( $i = 0; $i < $length; $i++ ) {
            $result .=
                "  #". ( $length - $i ) ."\n"
                ."  ". str_replace("\n", "\n  ", rtrim($this->getTraceOffsetString( $i )) ) ."\n";
        }

        return "Full Stack Trace:\n". $result ."  #0\n    {main}\n";
    }

    /**
     * Returns the stack trace formatted as HTML
     *
     * @return String
     */
    public function getTraceHTML ()
    {

        $result = "";

        $length = count( $this->getTrace() );

        for( $i = 0; $i < $length; $i++ ) {
            $result .=
                "<li>\n"
                .$this->getTraceOffsetHTML( $i )
                ."</li>\n";
        }

        return
            "<div class='h2o_Exception_Trace'>\n"
            ."<h3>Full Stack Trace</h3>\n"
            ."<ol>"
            .$result
            ."<li>{main}</li>\n"
            ."</ol>\n"
            ."</div>\n";
    }

    /**
     * Returns a verbose string detailing this exception
     *
     * @return String
     */
    public function getVerboseString ()
    {
        return "Exception Thrown: ". $this->getClassString() ."\n"
            .$this->getDetailsString()
            .$this->getFaultString()
            .$this->getThrownString()
            .$this->getTraceString();
    }

    /**
     * Returns the HTML for displaying this error
     *
     * @return String
     */
    public function getVerboseHTML ()
    {
        return
            "<div class='h2o_Exception'>\n"
            ."<h1>Exception Thrown</h1>\n"
            ."<h2>". $this->getClassString() ."</h2>\n"
            .$this->getDetailsHTML()
            .$this->getFaultHTML()
            .$this->getThrownHTML()
            .$this->getTraceHTML()
            ."</div>";
    }

    /**
     * Returns a short string detailing this exception
     *
     * @return String
     */
    public function getShortString ()
    {
        return "Exception Thrown: ". $this->getClassString() ."\n";
    }

    /**
     * Return the HTML version
     *
     * @return String
     */
    public function getShortHTML ()
    {
        return "<p>". $this->getShortString() ."</p>";
    }

    /**
     * Returns a string about this exception
     *
     * @return String
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
     *
     * @return String
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