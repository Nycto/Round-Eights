<?php
/**
 * @license Artistic License 2.0
 *
 * This file is part of Round Eights.
 *
 * Round Eights is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * Round Eights is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with Round Eights. If not, see <http://www.RoundEights.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <James@RoundEights.com>
 * @copyright Copyright 2009, James Frasca, All Rights Reserved
 * @package Exception
 */

namespace r8;

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
     * Identifies the offset in the backtrace that caused the problem
     *
     * @var Integer
     */
    private $fault;

    /**
     * Stores specific exception data. Each item has a label and a value
     *
     * @var Array
     */
    protected $data = array();

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

        if ( !\r8\isVague($fault, \r8\ALLOW_ZERO) )
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
    public function getTraceByOffset ($offset, $wrapFlag = \r8\num\OFFSET_RESTRICT)
    {
        $trace = $this->getTrace();
        if (count($trace) <= 0)
            return FALSE;

        return \r8\ary\offset($trace, $offset, $wrapFlag);
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
        return !\r8\isEmpty($this->getMessage());
    }

    /**
     * Returns Boolean whether the message is set
     *
     * @return Boolean
     */
    public function issetCode ()
    {
        return !\r8\isEmpty($this->getCode());
    }

    /**
     * Sets the fault of the exception
     *
     * @param Integer $offset The offset at fault for the current exception
     * @param integer $wrapFlag The offset wrapping mode to use
     * @return \r8\Exception Returns a self reference
     */
    public function setFault ( $offset, $wrapFlag = \r8\num\OFFSET_RESTRICT )
    {
        $trace = $this->getTrace();

        if (count($trace) <= 0)
            return $this;

        $this->fault = \r8\ary\calcOffset($trace, $offset, $wrapFlag);

        return $this;
    }

    /**
     * Unset the fault offset
     *
     * @return \r8\Exception Returns a self reference
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
     * @return \r8\Exception Returns a self reference
     */
    public function shiftFault ($shift = 1, $wrapFlag = \r8\ary\OFFSET_RESTRICT)
    {

        // Shifting the fault when no fault is set marks it to the end of the list
        if ( !$this->issetFault() )
            return $this->setFault(0);

        $shift = (int) $shift;

        $trace = $this->getTrace();

        if (count($trace) <= 0)
            return FALSE;

        $fault = $this->getFaultOffset();

        $fault += $shift;

        $fault = \r8\ary\calcOffset($trace, $fault, \r8\ary\OFFSET_RESTRICT);

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
     * @return \r8\Exception Returns a self reference
     */
    public function addData ( $label, $value )
    {
        $this->data[ trim((string) $label) ] = $value;
        return $this;
    }

    /**
     * Returns the data list for the current instance
     *
     * @return Array
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
        $label = trim((string) $label);

        if ( array_key_exists($label, $this->data) )
            return $this->data[ $label ];
        else
            return NULL;
    }

    /**
     * Return a string that briefly describes this exception
     *
     * @return String
     */
    public function getDescription ()
    {
        return static::TITLE ." (". static::DESCRIPTION .")";
    }

    /**
     * Returns a string about this exception
     *
     * @return String
     */
    public function __toString ()
    {
        if ( \r8\Env::request()->isCLI() )
            $formatter = new \r8\Error\Formatter\Text( \r8\Env::request() );
        else
            $formatter = new \r8\Error\Formatter\HTML( \r8\Env::request() );

        return $formatter->format( $this );
    }

}

?>