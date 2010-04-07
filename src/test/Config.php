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
 * @package PHPUnit
 */

namespace r8\Test;

/**
 * Includes the config file and ensures that a set of constants exists
 */
class Config
{

    /**
     * The prefix for the constants
     *
     * @param String
     */
    private $prefix;

    /**
     * The list of constants
     *
     * @var array
     */
    private $constants;

    /**
     * Constructor...
     *
     * @param String $prefix The prefix for all the constants
     * @param array $constants The list of constants
     */
    public function __construct( $prefix, array $constants )
    {
        $this->prefix = trim( strval($prefix) );
        $this->constants = $constants;
    }

    /**
     * Helper function for throwing the skip exception
     *
     * @throws PHPUnit_Framework_SkippedTestError
     * @param String $message The message to skip with
     */
    private function skip ( $message )
    {
        throw new \PHPUnit_Framework_SkippedTestError($message);
    }

    /**
     * Tests to ensure the config file exists and that all the required
     * constants are defined
     *
     * @throws PHPUnit_Framework_SkippedTestError This will be thrown if any
     *      of the constants dont exist
     * @return null
     */
    public function test ()
    {
        if ( defined('r8_TESTCONFIG') )
            $config = r8_TESTCONFIG;
        else
            $config = rtrim( getcwd(), "/") ."/config.php";

        if ( !file_exists($config) )
            $this->skip("Config file does not exist: $config");

        if ( !is_readable($config) )
            $this->skip("Config file is not readable: $config");

        require_once $config;

        foreach ( $this->constants AS $constant ) {

            $constant = $this->prefix ."_". trim( strval($constant) );

            if ( !defined($constant) )
                $this->skip("Required constant is not defined: ". $constant);

            $value = constant($constant);

            if ( empty($value) )
                $this->skip("Required constant must not be empty: ". $constant);
        }
    }

}

?>