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
        $code = \r8\str\stripW($code, "_");
        if ( \r8\isEmpty($code) ) {
            throw new \r8\Exception\Argument(
                2, 'Code', 'Can only contain Numbers, Strings and Underscores'
            );
        }

        $this->message = (string) $message;
        $this->level = \r8\Log\Level::resolveValue( $level );
        $this->code = (string) $code;
        $this->data = $data;
        $this->time = \microtime( TRUE );
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
     * Returns a nicely formatted version of the time
     *
     * @return String
     */
    public function getFormattedTime ()
    {
        return sprintf(
            '%s.%02d',
            date( "Y-m-d H:i:s", (int) $this->time ),
            ($this->time - (int) $this->time) * 100
        );
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

    /**
     * Converts this message to a string
     *
     * @return String
     */
    public function __toString ()
    {
        return json_encode(array(
            $this->getFormattedTime(),
            "PID: ". getmypid(),
            $this->level,
            $this->code,
            $this->message,
            $this->data
        )) ."\n";
    }

}

?>