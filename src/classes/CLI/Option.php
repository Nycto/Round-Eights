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
     * A human readable description of this option
     *
     * @var String
     */
    private $description;

    /**
     * Cleans and normalizes a flag
     *
     * @param String $flag
     * @param Boolean $require Whether to throw an exception if
     *      this flag is empty
     * @return String
     */
    static private function cleanFlag ( $flag, $require = TRUE )
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
     * @param String A human readable description of this option
     */
    public function __construct ( $primaryFlag, $description )
    {
        $primaryFlag = self::cleanFlag( $primaryFlag );
        $this->primaryFlag = $primaryFlag;
        $this->flags[] = $primaryFlag;

        $this->description = trim( (string) $description );
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
     * Adds a new flag to this instance
     *
     * @param String $flag
     * @return \r8\Args\Option Returns a self reference
     */
    public function addFlag ( $flag )
    {
        $flag = self::cleanFlag( $flag );

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
        return in_array( self::cleanFlag( $flag, FALSE ), $this->flags );
    }

    /**
     * Adds a argument that this option will consume
     *
     * @param \r8\iface\CLI\Arg $arg
     * @return \r8\Args\Option Returns a self reference
     */
    public function addArg ( \r8\iface\CLI\Arg $arg )
    {
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

?>