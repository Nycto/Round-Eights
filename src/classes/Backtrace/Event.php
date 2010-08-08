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
 * @package Backtrace
 */

namespace r8\Backtrace;

/**
 * A node within a backtrace
 */
abstract class Event
{

    /**
     * The file the event occurred within
     *
     * @var String
     */
    private $file;

    /**
     * The line the call was made on
     *
     * @var Integer
     */
    private $line;

    /**
     * Constructs a new Backtrace event from an array
     *
     * @param Array $backtrace The backtrace event array to build from
     * @return \r8\Backtrace\Event
     */
    static public function from ( array $event )
    {
        $event = \r8\ary\hone(
                $event,
                array( "function", "line", "file", "class", "type", "args" )
            )
            + array(
                "function" => null, "line" => null, "file" => null,
                 "class" => null, "type" => null, "args" => array()
            );

        if ( $event['function'] == '{closure}' ) {
            return new \r8\Backtrace\Event\Closure(
                $event['file'], $event['line'],
                (array) $event['args']
            );
        }

        else if ( empty($event['class']) ) {
            return new \r8\Backtrace\Event\Func(
                $event['function'], $event['file'],
                $event['line'], (array) $event['args']
            );
        }

        else if ( $event['type'] == '::' ) {
            return new \r8\Backtrace\Event\StaticMethod(
                $event['class'], $event['function'], $event['file'],
                $event['line'], (array) $event['args']
            );
        }

        else if ( $event['type'] == '->' ) {
            return new \r8\Backtrace\Event\Method(
                $event['class'], $event['function'], $event['file'],
                $event['line'], (array) $event['args']
            );
        }

        else {
            throw new \r8\Exception\Argument( 0 , "Event Array", "Invalid event format" );
        }
    }

    /**
     * Constructor...
     *
     * @param String $file The file the event occurred within
     * @param Integer $line The line the call was made on
     */
    public function __construct ( $file = null, $line = null )
    {
        $file = trim( (string) $file );
        $this->file = empty( $file ) ? NULL : $file;

        $this->line = (int) $line <= 0 ? null : (int) $line;
    }

    /**
     * Returns the file this event occurred within
     *
     * @return String
     */
    public function getFile ()
    {
        return $this->file;
    }

    /**
     * Returns the Line the call was made on
     *
     * @return Integer
     */
    public function getLine ()
    {
        return $this->line;
    }

    /**
     * Returns the name of the class this function is a member of
     *
     * @return String
     */
    public function getClass ()
    {
        return NULL;
    }

    /**
     * Returns the Name of this function
     *
     * @return String
     */
    public function getName ()
    {
        return NULL;
    }

    /**
     * Returns the Arguments passed in on this call
     *
     * @return Array
     */
    public function getArgs ()
    {
        return array();
    }

    /**
     * Returns a specific argument from this event
     *
     * @param Integer $offset The offset of the argument to return
     * @return Mixed
     */
    public function getArg ( $offset )
    {
        try {
            return \r8\ary\offset($this->getArgs(), $offset);
        }
        catch ( \r8\Exception\Index $err ) {
            return NULL;
        }
    }

    /**
     * Invokes the appropriate visitor method
     *
     * @param \r8\iface\Backtrace\Visitor $visitor The object to visit
     * @return NULL
     */
    abstract public function visit ( \r8\iface\Backtrace\Visitor $visitor );

    /**
     * Returns the fully resolved name of this event
     *
     * @return String
     */
    abstract public function getResolvedName ();

}

?>