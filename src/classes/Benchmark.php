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
 * @package Benchmark
 */

namespace r8;

/**
 * A quick and easy way to run a benchmark of a snippet of code
 */
class Benchmark
{

    /**
     * The name of this test
     *
     * @var String
     */
    private $name;

    /**
     * The test function to run
     *
     * @var Mixed
     */
    private $test;

    /**
     * Constructor...
     *
     * @param String $name The name of this test
     * @param Mixed $test The test function to run
     */
    public function __construct ( $name, $test )
    {
        $name = trim( (string) $name );

        if ( empty($name) )
            throw new \r8\Exception\Argument(0, "Test Name", "Must not be empty");

        if ( !is_callable($test) )
            throw new \r8\Exception\Argument(1, "Test Function", "Must be callable");

        $this->test = $test;
        $this->name = $name;
    }

    /**
     * Returns the name of this test
     *
     * @return String
     */
    public function getName ()
    {
        return $this->name;
    }

    /**
     * Runs the test and returns the result
     *
     * @param Integer $times The number of times to run this test
     * @return \r8\Benchmark\Result
     */
    public function run ( $times = 1000 )
    {
        $result = new \r8\Benchmark\Result;

        $times = max( (int) $times, 1 );

        $test = $this->test;

        for ( $i = 0; $i < $times; $i++ ) {
            $start = microtime( TRUE );
            $test();
            $end = microtime( TRUE );
            $result->addTime( $start - $end );
        }

        return $result;
    }

}

?>