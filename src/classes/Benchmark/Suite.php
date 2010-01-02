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
 * A suite of benchmarks
 */
class Suite
{

    /**
     * The number of times to run each test
     *
     * @var Integer
     */
    private $times;

    /**
     * The list of benchmarks in this Suite
     *
     * @var Array
     */
    private $benchmarks = array();

    /**
     * Constructor...
     *
     * @param Integer $times The number of times to run each test
     */
    public function __construct ( $times = 1000 )
    {
        $this->times = max(1, (int) $times);
    }

    /**
     * Returns the Benchmarks loaded in this suite
     *
     * @return Array An array of \r8\Benchmark objects
     */
    public function getBenchmarks ()
    {
        return $this->benchmarks;
    }

    /**
     * Adds a new test to this instance
     *
     * @param \r8\Benchmark $benchmark The benchmark to add to this suite
     * @return \r8\Benchmark\Suite Returns a self reference
     */
    public function addBenchmark ( \r8\Benchmark $benchmark )
    {
        if ( !in_array($benchmark, $this->benchmarks, TRUE) )
            $this->benchmarks[] = $benchmark;
        return $this;
    }

    /**
     * Helper method for construction a benchmark object and adding it to this suite
     *
     * @return \r8\Benchmark\Suite Returns a self reference
     */
    public function add ( $name, $test )
    {
        return $this->addBenchmark(
            new \r8\Benchmark( $name, $test )
        );
    }

    /**
     * Runs the suite of tests and returns the array of results
     *
     * @return Array An array of \r8\Benchmark\Result object
     */
    public function run ()
    {
        $result = array();

        foreach ( $this->benchmarks AS $benchmark ) {
            $result[] = $benchmark->run( $this->times );
        }

        return $result;
    }

    /**
     * Dumps the results of this test suite to the client formatted for the command line
     *
     * @return \r8\Benchmark\Suite
     */
    public function dumpCLI ()
    {
        echo "Benchmark Suite Results:\n";

        $resultList = $this->run();

        if ( count($resultList) <= 0 )
            echo "    No tests found\n";

        foreach ( $resultList AS $result ) {
            echo "    Test: ". $result->getName() ."\n"
                ."        Total Time: ". $result->getTotalTime() ." seconds\n"
                ."        Iterations: ". $result->count() ."\n"
                ."        Average Time: ". $result->getAverageTime() ." seconds\n";
        }

        return $this;
    }

    /**
     * Dumps the results of this test suite to the client formatted as HTML
     *
     * @return \r8\Benchmark\Suite
     */
    public function dumpHTML ()
    {
        echo "<table>\n"
            ."<tr><th colspan='4'>Benchmark Suite Results</th></tr>\n"
            ."<tr>\n"
                ."<th>Test Name</th>\n"
                ."<th>Total Time</th>\n"
                ."<th>Iterations</th>\n"
                ."<th>Average Time</th>\n"
            ."</tr>\n";

        $resultList = $this->run();

        if ( count($resultList) <= 0 )
            echo "<tr><td colspan='4'>No tests found</td></tr>\n";

        foreach ( $resultList AS $result ) {
            echo "<tr>\n"
                ."<td>". htmlspecialchars( $result->getName() ) ."</td>\n"
                ."<td>". htmlspecialchars( $result->getTotalTime() ) ." seconds</td>\n"
                ."<td>". htmlspecialchars( $result->count() ) ."</td>\n"
                ."<td>". htmlspecialchars( $result->getAverageTime() ) ." seconds</td>\n"
                ."</tr>\n";
        }

        echo "</table>\n";

        return $this;
    }

    /**
     * Dumps the results of this test suite to the client
     *
     * @return \r8\Benchmark\Suite
     */
    public function dump ()
    {
        return \r8\Env::Request()->isCLI() ? $this->dumpCLI() : $this->dumpHTML();
    }

}

?>