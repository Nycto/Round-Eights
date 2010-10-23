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
     * The list of forms this command can accept
     *
     * @var Array An array of \r8\CLI\Form objects
     */
    private $forms = array();

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

        $this->forms[] = new \r8\CLI\Form;
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
     * Returns the Forms this command can accept
     *
     * @return Array An array of \r8\CLI\Form objects
     */
    public function getForms ()
    {
        return $this->forms;
    }

    /**
     * Adds a Form this command can accept
     *
     * @param \r8\CLI\Form $form
     * @return \r8\CLI\Command Returns a self reference
     */
    public function addForm ( \r8\CLI\Form $form )
    {
        $this->forms[] = $form;
        return $this;
    }

    /**
     * Adds a new option to this collection
     *
     * @param \r8\CLI\Option $option
     * @return \r8\CLI\Collection Returns a self reference
     */
    public function addOption ( \r8\CLI\Option $option )
    {
        \r8\ary\first($this->forms)->addOption($option);
        return $this;
    }

    /**
     * Adds a argument that this command will consume
     *
     * @param \r8\iface\CLI\Arg $arg
     * @return \r8\Args\Option Returns a self reference
     */
    public function addArg ( \r8\iface\CLI\Arg $arg )
    {
        \r8\ary\first($this->forms)->addArg($arg);
        return $this;
    }

    /**
     * Returns the help details for this command
     *
     * @return String
     */
    public function getHelp ()
    {
        $forms = array();
        $options = array();
        foreach ( $this->forms as $form ) {
            $forms[] = $form->describe($this->name);
            $options = array_merge( $options, $form->getOptions() );
        }

        $result = "USAGE:\n"
            ."    ". implode("\n    ", $forms) ."\n\n"
            ."DESCRIPTION:\n"
            ."    ". wordwrap($this->description, 76, "\n    ", TRUE) ."\n\n";

        if ( !empty($options) ) {
            ksort($options);
            $result .= "OPTIONS:\n"
                .implode("", \r8\ary\invoke($options, 'describe') )
                ."\n";
        }

        return $result;
    }

    /**
     * Processes a list of input arguments
     *
     * @param \r8\CLI\Input $input If left empty, this will pull the input
     *      from the environment
     * @return \r8\CLI\Result
     */
    public function process ( \r8\CLI\Input $input = NULL )
    {
        if ( empty($input) )
            $input = \r8\Env::request()->getCLIArgs();

        $firstError = NULL;
        foreach ( $this->forms as $form ) {
            try {
                $input->rewind();
                return $form->process($input);
            }
            catch ( \r8\Exception\Data $err ) {
                if ( !isset($firstError) )
                    $firstError = $err;
            }
        }
        throw $firstError;
    }

}

