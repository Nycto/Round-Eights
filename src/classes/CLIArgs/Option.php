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
 * @package CLIArgs
 */

namespace r8\CLIArgs;

/**
 * A command-line option
 */
class Option
{

    /**
     * The list of flags that will trigger this option
     *
     * @var Array
     */
    private $flags = array();

    /**
     * The list of arguments this option will consume
     *
     * @var Array
     */
    private $args = array();

    /**
     * A human readable description of this option
     *
     * @var String
     */
    private $description;

    /**
     * Constructor...
     *
     * @param String A human readable description of this option
     */
    public function __construct ( $description )
    {
        $this->description = trim( (string) $description );
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
        $flag = trim( str_replace(" ", "-", (string) $flag), "-" );
        $flag = \r8\str\stripW( $flag, "-" );

        if ( empty($flag) )
            throw new \r8\Exception\Argument(0, "Flag", "Must not be empty");

        else if ( strlen($flag) > 1 )
            $flag = strtolower($flag);

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
     * Adds a argument that this option will consume
     *
     * @param String $arg
     * @return \r8\Args\Option Returns a self reference
     */
    public function addArg ( $arg )
    {
        $this->args[] = $arg;
        return $this;
    }

}

?>