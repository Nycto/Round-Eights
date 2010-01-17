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

namespace r8\Backtrace\Formatter;

/**
 * Formats a backtrace node as HTML
 */
class HTML implements \r8\iface\Backtrace\Formatter
{

    /**
     * Renders a list of arguments
     *
     * @param Array $args The list of args
     * @return String
     */
    private function renderArgs ( array $args )
    {
        if ( count($args) == 0 )
            return NULL;

        $args = array_map( 'htmlspecialchars', array_map( '\r8\getDump', $args ) );

        return "Arguments:<ul>\n"
            ."                <li>"
            .implode("</li>\n                <li>", $args)
            ."</li>\n"
            ."            </ul>";
    }

    /**
     * Returns the string to prepend to the resulted string
     *
     * @return String
     */
    public function prefix ()
    {
        return "<ol style='list-style-type: decimal;'>\n";
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
        $details = array_filter( array(
            empty($file) ? NULL : "File: ". htmlspecialchars($file),
            empty($line) ? NULL : "Line: ". $line,
            $this->renderArgs( $args )
        ) );

        if ( empty($details) ) {
            $details = NULL;
        }
        else {
            $details = "        <ul>\n"
                ."            <li>"
                . implode( "</li>\n            <li>", $details )
                ."</li>\n"
                ."        </ul>\n";
        }

        return "    <li>\n"
            ."        <em>#". $position .":</em> "
            ."<strong>"
                .( empty($name) ? "<em>{closure}</em>" : htmlspecialchars($name) )
            ."</strong>\n"
            .$details
            ."    </li>\n";
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
        return "    <li>\n"
            ."        <em>#". $position .":</em> Main: "
            ."<strong>"
                .htmlspecialchars( $file )
             ."</strong>\n"
            ."    </li>\n";
    }

    /**
     * Returns the string to append to the resulted string
     *
     * @return String
     */
    public function suffix ()
    {
        return "</ol>\n";
    }

}

?>