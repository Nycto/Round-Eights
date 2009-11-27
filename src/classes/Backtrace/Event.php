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
     */
    public function __construct ( $file )
    {
        $file = trim( (string) $file );

        if ( empty($file) )
            throw new \r8\Exception\Argument( 0, "File", "Must not be empty" );

        $this->file = $file;
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
     * Invokes the appropriate visitor method
     *
     * @param \r8\iface\Backtrace\Visitor $visitor The object to visit
     * @return NULL
     */
    abstract public function visit ( \r8\iface\Backtrace\Visitor $visitor );

}

?>