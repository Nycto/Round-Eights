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

namespace r8\Backtrace\Formatter;

/**
 * Formats a backtrace for a Log file
 */
class JSON implements \r8\iface\Backtrace\Formatter
{

    /**
     * Returns the string to prepend to the resulted string
     *
     * @return String
     */
    public function prefix ()
    {
        return "[";
    }

    /**
     * Formats a single event from a backtrace and returns the result
     *
     * @param Integer $position The position in the stack at which this event occurred
     * @param String $name The resolved name of this event
     * @param Array $args Any arguments passed to this event
     * @param String $file The file this event occurred in
     * @param Integer $line The line this event occurred on
     * @return String
     */
    public function event ( $position, $name, array $args, $file, $line )
    {
        $result = array_filter( array(
        	"Stack" => $position,
            "Name" => $name,
            "Closure" => empty($name),
            "File" => $file,
            "Line" => $line,
            "Args" => array_map('\r8\getDump', $args)
        ) );

        return json_encode($result) .", ";
    }

    /**
     * Formats the main event of this backtrace
     *
     * @param Integer $position The position in the stack at which this event occurred
     * @param String $file The file this event occurred in
     * @return String
     */
    public function main ( $position, $file )
    {
        return json_encode( array(
        	"Stack" => $position,
            "Main" => empty($name),
            "File" => $file,
        ) );
    }

    /**
     * Returns the string to append to the resulted string
     *
     * @return String
     */
    public function suffix ()
    {
        return "]";
    }

}

?>