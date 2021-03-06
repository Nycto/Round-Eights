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
 * One of multiple forms for which a CLI command can be used
 */
class Form
{

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
     * Returns the Options indexed by their primary flag
     *
     * @return Array
     */
    public function getOptions ()
    {
        return $this->options;
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
        // Don't let them add any more arguments if the previous argument is
        // greedy... it would be pointless.
        $last = end( $this->args );
        if ( $last && $last->isGreedy() ) {
            throw new \r8\Exception\Data(
                $last, "Greedy Arg",
                "Additional arguments can not be added after a greedy argument"
            );
        }

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
                    $flag, "Flag",
                    "Unrecognized flag"
                );
            }

            if ( !$option->allowMany() && $result->flagExists($flag) ) {
                throw new \r8\Exception\Data(
                    $option, "Flag",
                    "Flag can not appear multiple times"
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

    /**
     * Returns a description of this command form
     *
     * @param String $commandName The name of the command
     * @return String
     */
    public function describe ( $commandName )
    {
        $args = \r8\ary\invoke($this->args, "describe");

        if ( !empty($this->options) ) {
            $options = array_map( function ($opt) {
                return strlen($opt) == 1 ? '-'. $opt : '--'. $opt;
            }, array_keys($this->options) );
            sort($options);
            array_unshift($args, '['. implode(',', $options)  .']' );
        }

        return trim($commandName ." ". implode(" ", $args));
    }

}

