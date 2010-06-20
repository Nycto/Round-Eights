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
 * @package CLI
 */

namespace r8\CLI;

/**
 * Parses a list of raw command line arguments
 */
class Input
{

    /**
     * The input argument that was parsed
     */
    const TYPE_OPT = 1;
    const TYPE_ARG = 2;

    /**
     * The parsed list of argument, flags and switches
     *
     * @var Array
     */
    private $args = array();

    /**
     * The current read offset
     *
     * @var Integer
     */
    private $pointer = -1;

    /**
     * Constructor...
     *
     * @param Array $args The argument list being parsed
     */
    public function __construct ( array $argv )
    {
        $doubleDash = FALSE;

        foreach ( $argv AS $arg ) {

            if ( $doubleDash )
                $this->args[] = array(self::TYPE_ARG, $arg);
            else if ( $arg == "--" )
                $doubleDash = TRUE;
            elseif ( \r8\str\startsWith($arg, "--") )
                $this->parseSwitch($arg);
            else if ( \r8\str\startsWith($arg, "-") )
                $this->parseFlag($arg);
            else
                $this->args[] = array(self::TYPE_ARG, $arg);
        }
    }

    /**
     * A helper method for parsing a cli flag
     *
     * @param String $flags The flag string to parse
     * @return NULL
     */
    private function parseFlag ( $flags )
    {
        $flags = ltrim($flags, '-');

        list($flags, $arg) = explode("=", $flags, 2) + array(NULL, NULL);

        $flags = str_split($flags, 1);

        foreach ( $flags AS $flag ) {
            $this->args[] = array(self::TYPE_OPT, $flag);
        }

        if ( $arg !== NULL )
            $this->args[] = array(self::TYPE_ARG, $arg);
    }

    /**
     * A helper method for parsing a cli switch
     *
     * @param String $flags The switch string to parse
     * @return NULL
     */
    private function parseSwitch ( $switch )
    {
        $switch = ltrim($switch, '-');

        list($switch, $arg) = explode("=", $switch, 2) + array(NULL, NULL);

        $this->args[] = array(self::TYPE_OPT, strtolower($switch));

        if ( $arg !== NULL )
            $this->args[] = array(self::TYPE_ARG, $arg);
    }

    /**
     * Returns whether there are any more options to parse
     *
     * @return Boolean
     */
    public function hasNextOption ()
    {
        $len = count( $this->args );
        for ( $i = $this->pointer + 1; $i < $len; $i++ ) {
            if ( $this->args[$i][0] == self::TYPE_OPT )
                return TRUE;
        }
        return FALSE;
    }

    /**
     * Returns whether there are any more arguments that could be parsed as
     * part of the current option
     *
     * @return Boolean
     */
    public function hasNextArg ()
    {
        return isset($this->args[ $this->pointer + 1 ])
            && $this->args[ $this->pointer + 1 ][0] == self::TYPE_ARG;
    }

    /**
     * Pops the next option off the input list and returns it
     *
     * @throws \r8\Exception\Data This will be thrown if there are any unconsumed
     *      arguments between the current offset and the next flag
     * @throws \r8\Exception\Index This is thrown if there are no more options
     *      to be popped off of this input list
     * @return String
     */
    public function popOption ()
    {
        $this->pointer++;

        if ( !isset($this->args[ $this->pointer ]) ) {
            throw new \r8\Exception\Index(
                "Offset",
                $this->pointer,
                "End of option list reached"
            );
        }

        if ( $this->args[ $this->pointer ][0] == self::TYPE_ARG ) {
            throw new \r8\Exception\Data(
                "Argument",
                $this->args[ $this->pointer ][1],
                "Unrecognized argument"
            );
        }

        if ( $this->args[ $this->pointer ][1] === "" ) {
            throw new \r8\Exception\Data(
                "Option",
                $this->args[ $this->pointer ][1],
                "Empty option"
            );
        }

        return $this->args[ $this->pointer ][1];
    }

    /**
     * Pops the next argument off of this list
     *
     * @return String|NULL
     */
    public function popArgument ()
    {
        if ( !isset($this->args[ $this->pointer + 1 ]) )
            return NULL;

        // This allows the consumer to pop as many arguments as they want
        // without consuming any options
        if ( $this->args[ $this->pointer + 1 ][0] == self::TYPE_OPT )
            return NULL;

        $this->pointer++;

        return $this->args[ $this->pointer ][1];
    }

    /**
     * Rewinds this argument list back to the beginning
     *
     * @return \r8\CLI\Input Returns a self reference
     */
    public function rewind ()
    {
        $this->pointer = -1;
        return $this;
    }

}

?>