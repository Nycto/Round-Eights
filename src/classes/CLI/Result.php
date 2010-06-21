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
 * The result of an argument parsing
 */
class Result
{

    /**
     * The list of flags that were parsed
     *
     * @var Array
     */
    private $flags = array();

    /**
     * Add a set of options to this result
     *
     * @param \r8\CLI\Option $option The option that was matched
     * @param Array $args The list of arguments associated with the flags
     * @return \r8\CLI\Result Returns a self reference
     */
    public function addOption ( \r8\CLI\Option $option, array $args )
    {
        $primary = $option->getPrimaryFlag();

        if ( !isset( $this->flags[$primary] ) )
            $this->flags[$primary] = array('opt' => $option, 'args' => array());

        $this->flags[$primary]['args'][] = $args;

        return $this;
    }

    /**
     * A helper method for finding the appropriate data set index based on a flag
     *
     * @param String $flag
     * @return String
     */
    private function findFlagIndex ( $flag )
    {
        $flag = \r8\CLI\Option::normalizeFlag($flag, FALSE);

        if ( isset( $this->flags[ $flag ] ) )
            return $flag;

        foreach ( $this->flags AS $primary => $option ) {
            if ( $option['opt']->hasFlag($flag) )
                return $primary;
        }

        return NULL;
    }

    /**
     * Returns whether a flag is set
     *
     * @param String $flag The flag to test
     * @return Boolean
     */
    public function flagExists ( $flag )
    {
        return $this->findFlagIndex( $flag ) !== NULL;
    }

    /**
     * Returns a single set of arguments for the given flag
     *
     * @param String $flag The flag to look up
     * @return Array The list of arguments for this flag
     */
    public function getOneArgList ( $flag )
    {
        $flag = $this->findFlagIndex( $flag );

        if ( !isset( $this->flags[ $flag ] ) )
            return array();

        return \reset( $this->flags[ $flag ]['args'] );
    }

    /**
     * Returns all the list of arguments for the given flag. This is useful when
     * a flag can appear multiple times in the input
     *
     * @param String $flag The flag to look up
     * @return Array The multi-dimensional list of arguments for this flag
     */
    public function getAllArgLists ( $flag )
    {
        $flag = $this->findFlagIndex( $flag );

        return isset( $this->flags[ $flag ] )
            ? $this->flags[ $flag ]['args'] : array();
    }

}

?>