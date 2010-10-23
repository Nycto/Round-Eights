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
 * Represents a PHP error
 */
class PHP implements \r8\iface\Error
{

    /**
     * The list of error codes that are considered fatal
     *
     * @var Array
     */
    static private $fatal = array(
        E_ERROR, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR,
        E_COMPILE_WARNING, E_USER_ERROR, E_RECOVERABLE_ERROR
    );

    /**
     * The list of Error types and their human readable equivalent
     *
     * @var Array
     */
    static private $types = array(
        E_ERROR => "Error", E_WARNING => "Warning", E_PARSE => "Parse",
        E_NOTICE => "Notice", E_CORE_ERROR => "Core Error",
        E_CORE_WARNING => "Core Warning", E_COMPILE_ERROR => "Compile Error",
        E_COMPILE_WARNING => "Compile Warning", E_USER_ERROR => "User Error",
        E_USER_WARNING => "User Warning", E_USER_NOTICE => "User Notice",
        E_STRICT => "Strict", E_RECOVERABLE_ERROR => "Recoverable Error",
        E_DEPRECATED => "Deprecated", E_USER_DEPRECATED => "User Deprecated",
    );

    /**
     * The file this error occurred in
     *
     * @var String
     */
    private $file;

    /**
     * The line the error occurred on
     *
     * @var Integer
     */
    private $line;

    /**
     * The error code
     *
     * @var Integer
     */
    private $code;

    /**
     * The error message
     *
     * @var String
     */
    private $message;

    /**
     * The backtrace leading up to the error
     *
     * @var \r8\Backtrace
     */
    private $backtrace;

    /**
     * Constructor...
     *
     * @param String $file The file this error occurred in
     * @param Integer $line The line the error occurred on
     * @param Integer $code The error code
     * @param String $message The error message
     * @param \r8\Backtrace $backtrace The backtrace leading up to the error
     */
    public function __construct ( $file, $line, $code, $message, \r8\Backtrace $backtrace )
    {
        $file = trim( (string) $file );
        $this->file = empty($file) ? NULL : $file;

        $this->line = (int) $line > 0 ? (int) $line : NULL;

        $this->code = (int) $code > 0 ? (int) $code : NULL;

        $message = trim( (string) $message );
        $this->message = empty($message) ? NULL : $message;

        $this->backtrace = $backtrace;
    }

    /**
     * Returns the error code identifying this error
     *
     * @return Integer
     */
    public function getCode ()
    {
        return $this->code;
    }

    /**
     * Returns the Error Message associated with this error
     *
     * @return String
     */
    public function getMessage ()
    {
        return $this->message;
    }

    /**
     * Returns the file the error occurred in
     *
     * @return \r8\FileSys\File
     */
    public function getFile ()
    {
        return $this->file;
    }

    /**
     * Returns the line number the error occurred on
     *
     * @return Integer
     */
    public function getLine ()
    {
        return $this->line;
    }

    /**
     * Returns whether this error should halt execution of the script
     *
     * @return Boolean
     */
    public function isFatal ()
    {
        return in_array( $this->code, self::$fatal );
    }

    /**
     * Returns the backtrace that lead up to this error
     *
     * @return \r8\Backtrace
     */
    public function getBacktrace ()
    {
        return $this->backtrace;
    }

    /**
     * Returns the human readable type of this error
     *
     * @return String
     */
    public function getType ()
    {
        if ( isset(self::$types[ $this->code ]) )
            return self::$types[ $this->code ];
        else
            return "Unknown Error";
    }

    /**
     * Returns an array of details about this error
     *
     * @return Array
     */
    public function getDetails ()
    {
        return array();
    }

}

