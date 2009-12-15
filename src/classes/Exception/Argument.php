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
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Exception
 */

namespace r8\Exception;

/**
 * Exception class to handle bad arguments
 */
class Argument extends \r8\Exception
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

        if ( !\r8\isVague($label) )
            $this->addData("Arg Label", $label);

        if ( !\r8\isVague($message) )
            $this->message = $message;

        if ( !\r8\isVague( $code ) )
            $this->code = $code;

        if (!\r8\isVague($arg, \r8\ALLOW_ZERO) && $this->getTraceCount() > 0)
            $this->setArg($arg);
    }

    /**
     * Identify the argument that caused the problem
     *
     * @param Integer $offset
     * @param Integer $wrapFlag
     * @return object Returns a self reference
     */
    public function setArg ( $offset, $wrapFlag = \r8\ary\OFFSET_RESTRICT )
    {
        // If the fault isn't set, default to the end of the trace
        if ( !$this->issetFault())
            $this->setFault(0);

        $fault = $this->getFault();

        if ($fault === FALSE || !isset($fault['args']) || !is_array($fault['args']))
            trigger_error("Error fetching fault trace arguments", E_USER_ERROR);

        if (count($fault['args']) <= 0)
            return $this->unsetArg();

        $offset = \r8\ary\calcOffset($fault['args'], $offset, $wrapFlag);

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

        if (!\r8\isVague($arg, \r8\ALLOW_ZERO))
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
     * Returns the data list for the current instance
     *
     * @return Array
     */
    public function getData ()
    {
        return array(
                "Arg Offset" => $this->getArgOffset(),
                "Arg Value" => \r8\getDump( $this->getArgData() )
            )
            + $this->data;
    }

}

?>