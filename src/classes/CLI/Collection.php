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
 * A collection of CLI options
 */
class Collection
{

    /**
     * The list of command line options
     *
     * @var Array
     */
    private $options = array();

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
     * Processes a list of input arguments
     *
     * @param \r8\CLI\Input $input
     * @return \r8\CLI\Result
     */
    public function process ( \r8\CLI\Input $input )
    {
        $result = new \r8\CLI\Result;

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

        return $result;
    }

}

?>