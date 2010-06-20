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
     * Normalizes a flag
     *
     * @param String $flag
     * @return String
     */
    static private function normalizeFlag ( $flag )
    {
        return strtolower( \r8\str\stripW($flag) );
    }

    /**
     * Add a set of options to this result
     *
     * @param Array $flags The list of flag aliases that all reference the given
     *      argument list
     * @param Array $args The list of arguments associated with the flags
     * @return \r8\CLI\Result Returns a self reference
     */
    public function addOption ( array $flags, array $args )
    {
        $flags = array_map( array(__CLASS__, 'normalizeFlag'), $flags );
        $flags = \r8\ary\compact( $flags );

        foreach ( $flags AS $flag )
        {
            $this->flags[ $flag ] =& $args;
        }

        return $this;
    }

    /**
     * Returns whether a flag is set
     *
     * @param String $flag The flag to test
     * @return Boolean
     */
    public function flagExists ( $flag )
    {
        return isset( $this->flags[ self::normalizeFlag($flag) ] );
    }

    /**
     * Returns the arguments associated with a flag
     *
     * @param String $flag The flag to look up
     * @return Array
     */
    public function getArgs ( $flag )
    {
        $flag = self::normalizeFlag($flag);
        return isset( $this->flags[ $flag ] ) ? $this->flags[ $flag ] : NULL;
    }

}

?>