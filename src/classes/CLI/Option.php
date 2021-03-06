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
 * A command-line option
 */
class Option
{

    /**
     * The primary flag that will trigger this option
     *
     * @var String
     */
    private $primaryFlag;

    /**
     * A human readable description of this option
     *
     * @var String
     */
    private $description;

    /**
     * Whether to allow this option to appear multiple times in the command
     * line list
     *
     * @var Boolean
     */
    private $many;

    /**
     * The list of flags that will trigger this option
     *
     * @var Array
     */
    private $flags = array();

    /**
     * The list of arguments this option will consume
     *
     * @var Array An array of \r8\iface\CLI\Arg objects
     */
    private $args = array();

    /**
     * Cleans and normalizes a flag
     *
     * @param String $flag
     * @param Boolean $require Whether to throw an exception if
     *      this flag is empty
     * @return String
     */
    static public function normalizeFlag ( $flag, $require = TRUE )
    {
        $flag = trim( str_replace(" ", "-", (string) $flag), "-" );
        $flag = \r8\str\stripW( $flag, "-" );

        if ( $require && \r8\isEmpty($flag) )
            throw new \r8\Exception\Argument(0, "Flag", "Must not be empty");

        else if ( strlen($flag) > 1 )
            $flag = strtolower($flag);

        return $flag;
    }

    /**
     * Constructor...
     *
     * @param String $primaryFlag The primary flag that will trigger this option
     * @param String $description A human readable description of this option
     * @param Boolean $many Whether to allow this option to appear multiple times
     *      in the command line list
     */
    public function __construct ( $primaryFlag, $description, $many = FALSE )
    {
        $primaryFlag = self::normalizeFlag( $primaryFlag );
        $this->primaryFlag = $primaryFlag;
        $this->flags[] = $primaryFlag;

        $this->description = trim( (string) $description );
        $this->many = (bool) $many;
    }

    /**
     * Returns a description of this option to be used in the help view
     *
     * @return String
     */
    public function describe ()
    {
        $flags = array_map(
            function ($flag) {
                return (strlen($flag) == 1 ? "-" : "--" ) . $flag;
            },
            $this->flags
        );

        return sprintf(
            "    %s\n        %s\n",
            trim(
                implode(", ", $flags) ." "
                .implode(" ", \r8\ary\invoke($this->args, "describe"))
            ),
            wordwrap($this->description, 72, "\n        ", TRUE)
        );
    }

    /**
     * Returns the Primary Flag of this option
     *
     * @return String
     */
    public function getPrimaryFlag ()
    {
        return $this->primaryFlag;
    }

    /**
     * Returns the description of this option
     *
     * @return String
     */
    public function getDescription ()
    {
        return $this->description;
    }

    /**
     * Returns whether this option is allowed to appear multiple times in
     * the command line argument list
     *
     * @return Boolean
     */
    public function allowMany ()
    {
        return $this->many;
    }

    /**
     * Adds a new flag to this instance
     *
     * @param String $flag
     * @return \r8\Args\Option Returns a self reference
     */
    public function addFlag ( $flag )
    {
        $flag = self::normalizeFlag( $flag );

        if ( !in_array($flag, $this->flags) )
            $this->flags[] = $flag;

        return $this;
    }

    /**
     * Returns the Flags that will trigger this option
     *
     * @return Array An array of strings
     */
    public function getFlags ()
    {
        return $this->flags;
    }

    /**
     * Returns whether a given input is a flag associated with this option
     *
     * @param String $flag The flag to test
     * @return Boolean
     */
    public function hasFlag ( $flag )
    {
        return in_array( self::normalizeFlag( $flag, FALSE ), $this->flags );
    }

    /**
     * Adds a argument that this option will consume
     *
     * @param \r8\iface\CLI\Arg $arg
     * @return \r8\Args\Option Returns a self reference
     */
    public function addArg ( \r8\iface\CLI\Arg $arg )
    {
        // Don't let them add any more arguments if the previous argument is
        // greedy... it would be pointless.
        $last = end( $this->args );
        if ( $last && $last->isGreedy() ) {
            throw new \r8\Exception\Data(
                $last, "Greedy Arg",
                "Addition arguments can not be added after a greedy argument"
            );
        }

        $this->args[] = $arg;
        return $this;
    }

    /**
     * Returns the Arguments loaded into this option
     *
     * @return Array An array of \r8\iface\CLI\Arg objects
     */
    public function getArgs ()
    {
        return $this->args;
    }

    /**
     * Consumes the arguments from an input list and returns them after
     * processing
     *
     * @param \r8\CLI\Input $input The input argument list
     * @return Array
     */
    public function consume ( \r8\CLI\Input $input )
    {
        return array_reduce(
            $this->args,
            function ( $accum, $arg ) use ( $input ) {
                return array_merge( $accum, (array) $arg->consume( $input ) );
            },
            array()
        );
    }

}

