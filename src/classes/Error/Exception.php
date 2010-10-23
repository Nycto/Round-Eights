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
 * @package Error
 */

namespace r8\Error;

/**
 * Normalizes an Exception into an Error
 */
class Exception implements \r8\iface\Error
{

    /**
     * The Exception being wrapped
     *
     * @var \Exception
     */
    private $exception;

    /**
     * The backtrace that lead up to this exception
     *
     * @var \r8\Backtrace
     */
    private $backtrace;

    /**
     * Constructor...
     *
     * @param \Exception $exception The exception being wrapped
     */
    public function __construct ( \Exception $exception )
    {
        $this->exception = $exception;
    }

    /**
     * Returns the error code identifying this error
     *
     * @return Integer
     */
    public function getCode ()
    {
        return $this->exception->getCode();
    }

    /**
     * Returns the Error Message associated with this error
     *
     * @return String
     */
    public function getMessage ()
    {
        return $this->exception->getMessage();
    }

    /**
     * Returns the file the error occurred in
     *
     * @return String
     */
    public function getFile ()
    {
        return $this->exception->getFile();
    }

    /**
     * Returns the line number the error occurred on
     *
     * @return Integer
     */
    public function getLine ()
    {
        return $this->exception->getLine();
    }

    /**
     * Returns whether this error should halt execution of the script
     *
     * @return Boolean
     */
    public function isFatal ()
    {
        return TRUE;
    }

    /**
     * Returns the backtrace that lead up to this error
     *
     * @return \r8\Backtrace
     */
    public function getBacktrace ()
    {
        if ( !isset($this->backtrace) ) {
            $this->backtrace = \r8\Backtrace::from(
                $this->exception->getTrace()
            );
            $this->backtrace->unshiftEvent(
                new \r8\Backtrace\Event\Func(
                    "throw",
                    $this->exception->getFile(),
                    $this->exception->getLine(),
                    array( $this->exception )
                )
            );
        }

        return $this->backtrace;
    }

    /**
     * Returns the human readable type of this error
     *
     * @return String
     */
    public function getType ()
    {
        return "Uncaught Exception";
    }

    /**
     * Returns an array of details about this error
     *
     * @return Array
     */
    public function getDetails ()
    {
        if ( !($this->exception instanceof \r8\Exception) )
            return array();

        return array(
                "Exception" => '\\'. get_class($this->exception),
                "Description" => $this->exception->getDescription()
            )
            + $this->exception->getData();
    }

}

