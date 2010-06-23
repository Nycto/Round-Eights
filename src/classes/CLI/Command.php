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
 * A CLI command represented as a collection of options and arguments
 */
class Command
{

    /**
     * The name of this command
     *
     * @var String
     */
    private $name;

    /**
     * A description of this command
     *
     * @var String
     */
    private $description;

    /**
     * The list of command line options
     *
     * @var Array
     */
    private $options = array();

    /**
     * The list of arguments this command will consume
     *
     * @var Array An array of \r8\iface\CLI\Arg objects
     */
    private $args = array();

    /**
     * Constructor...
     *
     * @param String $name The name of this command
     * @param String $description A description of this command
     */
    public function __construct ( $name, $description )
    {
        $name = \r8\str\stripNoPrint( $name );
        if ( \r8\isEmpty($name) )
            throw new \r8\Exception\Argument(0, "Name", "Must not be empty");

        $description = \r8\str\stripNoPrint( $description );
        if ( \r8\isEmpty($description) )
            throw new \r8\Exception\Argument(0, "Description", "Must not be empty");

        $this->name = $name;
        $this->description = $description;
    }

    /**
     * Returns the Name of this command
     *
     * @return String
     */
    public function getName ()
    {
        return $this->name;
    }

    /**
     * Returns the Description of this command
     *
     * @return String
     */
    public function getDescription ()
    {
        return $this->description;
    }

    /**
     * Adds a new option to this collection
     *
     * @param \r8\CLI\Option $option
     * @return \r8\CLI\Collection Returns a self reference
     */
    public function addOption ( \r8\CLI\Option $option )
    {
        $this->options[ $option->getPrimaryFlag() ] = $option;
        return $this;
    }

    /**
     * Finds an option based on a flag
     *
     * @param String $flag The flag to look up
     * @return \r8\CLI\Option Returns NULL if there were no ptions with that
     *      flag registered
     */
    public function findByFlag ( $flag )
    {
        $flag = \r8\CLI\Option::normalizeFlag( $flag, FALSE );

        if ( isset($this->options[$flag]) )
            return $this->options[$flag];

        foreach ( $this->options AS $option ) {
            if ( $option->hasFlag($flag) )
                return $option;
        }
        return NULL;
    }

    /**
     * Adds a argument that this command will consume
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
     * Returns the Arguments loaded into this command
     *
     * @return Array An array of \r8\iface\CLI\Arg objects
     */
    public function getArgs ()
    {
        return $this->args;
    }

    /**
     * Returns the help details for this command
     *
     * @return String
     */
    public function getHelp ()
    {
        $args = \r8\ary\invoke($this->args, "describe");

        if ( !empty($this->options) )
            array_unshift($args, "[OPTIONS]...");

        $result = "USAGE:\n"
            ."    ". trim($this->name ." ". implode(" ", $args)) ."\n\n"
            ."DESCRIPTION:\n"
            ."    ". wordwrap($this->description, 76, "\n    ", TRUE) ."\n\n";

        if ( !empty($this->options) ) {
            $result .= "OPTIONS:\n"
                .implode("", \r8\ary\invoke($this->options, 'describe') )
                ."\n";
        }

        return $result;
    }

    /**
     * Processes a list of input arguments
     *
     * @param \r8\CLI\Input $input
     * @return \r8\CLI\Result
     */
    public function process ( \r8\CLI\Input $input )
    {
        $result = new \r8\CLI\Result;

        // Loop over all the flags and process each in turn
        while ( $input->hasNextOption() ) {
            $flag = $input->popOption();
            $option = $this->findByFlag( $flag );

            if ( $option === NULL ) {
                throw new \r8\Exception\Data(
                    "Flag",
                    $option,
                    "Unrecognized flag"
                );
            }

            $result->addOption( $option, $option->consume($input) );
        }

        // Now collect any command level arguments
        foreach ( $this->args AS $arg ) {
            $result->addArgs( $arg->consume($input) );
        }

        // If there are any arguments left over, let someone know
        if ( $input->hasNextArg() ) {
            throw new \r8\Exception\Data(
                "Flag",
                $input->popArgument(),
                "Unrecognized flag"
            );
        }

        return $result;
    }

}

?>