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

namespace r8\Benchmark;

/**
 * The result of a benchmark test run
 */
class Result implements \Countable
{

    /**
     * The list of seconds in which the test ran
     *
     * @var Array
     */
    private $times = array();

    /**
     * The name of this test
     *
     * @var String
     */
    private $name;

    /**
     * Constructor...
     *
     * @param String $name The name of the test
     */
    public function __construct ( $name )
    {
        $name = trim( (string) $name );

        if ( empty($name) )
            throw new \r8\Exception\Argument(0, "Test Name", "Must not be empty");

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
     * Returns the list of times in which this test ran
     *
     * @return Array The list of run times in seconds
     */
    public function getTimes ()
    {
        return $this->times;
    }

    /**
     * Adds a new time to this result set
     *
     * @param Float $time The seconds in which the test ran
     * @return \r8\Benchmark\Result
     */
    public function addTime ( $time )
    {
        $this->times[] = (float) $time;
        return $this;
    }

    /**
     * Returns the number of times the test was run
     *
     * @return Integer
     */
    public function count ()
    {
        return count( $this->times );
    }

    /**
     * Returns the total number of seconds it took to run this benchmark
     *
     * @return Float
     */
    public function getTotalTime ()
    {
        return array_sum( $this->times );
    }

    /**
     * The average number of seconds in which this test ran
     *
     * @return Float
     */
    public function getAverageTime ()
    {
        $count = $this->count();

        if ( $count == 0 )
            return 0;

        return $this->getTotalTime() / $count;
    }

}

?>