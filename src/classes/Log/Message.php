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
 * @package Log
 */

namespace r8\Log;

/**
 * A log message
 */
class Message
{

    /**
     * The alpha-numeric code of this message
     *
     * @var String
     */
    private $code;

    /**
     * The actual string message
     *
     * @var String
     */
    private $message;

    /**
     * The level of this error, as defined by \r8\Log\Level
     *
     * @var String
     */
    private $level;

    /**
     * Any key/value data associated with this log entry
     *
     * @var Array
     */
    private $data;

    /**
     * A limited backtrace that includes only the file and line
     *
     * @var Array
     */
    private $backtrace;

    /**
     * The time at which this message was created
     *
     * @var Float
     */
    private $time;

    /**
     * Constructor...
     *
     * @param String $message
     * @param String $level
     * @param String $code
     * @param Array $data
     */
    public function __construct ( $message, $level, $code, array $data = array() )
    {
        $code = (string) $code;
        if ( !ctype_alnum($code) )
            throw new \r8\Exception\Argument(2, 'Code', 'Must be alpha-numeric');

        $this->message = (string) $message;
        $this->level = \r8\Log\Level::resolveValue( $level );
        $this->code = (string) $code;
        $this->data = $data;

        $this->time = \microtime( TRUE );

        $backtrace = \debug_backtrace();
        \array_pop( $backtrace );
        $this->backtrace = \array_map(
            function ( $call ) {
                return sprintf(
                    '%s:%d',
                    isset($call['file']) ? $call['file'] : '{none}',
                    isset($call['line']) ? $call['line'] : 0
                );
            },
            $backtrace
        );
    }

    /**
     * Returns the Message
     *
     * @return String
     */
    public function getMessage ()
    {
        return $this->message;
    }

    /**
     * Returns the Code
     *
     * @return String
     */
    public function getCode ()
    {
        return $this->code;
    }

    /**
     * Returns the Level of this message
     *
     * @return String
     */
    public function getLevel ()
    {
        return $this->level;
    }

    /**
     * Returns the Time that this log message was created
     *
     * @return Float
     */
    public function getTime ()
    {
        return $this->time;
    }

    /**
     * Returns the Backtrace that lead to this message
     *
     * @return Array
     */
    public function getBacktrace ()
    {
        return $this->backtrace;
    }

    /**
     * Returns the Data associated with this log message
     *
     * @return Array
     */
    public function getData ()
    {
        return $this->data;
    }

    /**
     * Sets a piece of data in this message
     *
     * @param String $key
     * @param Mixed $value
     * @return \r8\Log\Message Returns a self reference
     */
    public function setData ( $key, $value )
    {
        $this->data[ $key ] = $value;
        return $this;
    }

}

?>