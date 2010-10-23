<?php
/**
 * Unit Test File
 *
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
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_Benchmark_Suite extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a test benchmark
     *
     * @return \r8\Benchmark
     */
    public function getTestBenchmark ( $times = 1000, \r8\Benchmark\Result $result = null )
    {
        $mark = $this->getMock('\r8\Benchmark', array(), array(), '', FALSE);

        if ( $result ) {
            $mark->expects( $this->once() )
                ->method( "run" )
                ->with( $this->equalTo( $times ) )
                ->will( $this->returnValue( $result ) );
        }

        return $mark;
    }

    /**
     * Returns a test benchmark result
     *
     * @return \r8\Benchmark\Result
     */
    public function getTestResult ( $name = "test", $time = 5, $count = 1000 )
    {
        $result = $this->getMock(
            '\r8\Benchmark\Result',
            array("getTotalTime", "count"),
            array($name)
        );

        $result->expects( $this->any() )
            ->method( "getTotalTime" )
            ->will( $this->returnValue( $time ) );

        $result->expects( $this->any() )
            ->method( "count" )
            ->will( $this->returnValue( $count ) );

        return $result;
    }

    public function testAddBenchmark ()
    {
        $suite = new \r8\Benchmark\Suite;
        $this->assertSame( array(), $suite->getBenchmarks() );

        $mark1 = $this->getTestBenchmark();
        $this->assertSame( $suite, $suite->addBenchmark($mark1) );
        $this->assertSame( array($mark1), $suite->getBenchmarks() );

        $mark2 = $this->getTestBenchmark();
        $this->assertSame( $suite, $suite->addBenchmark($mark2) );
        $this->assertSame( array($mark1, $mark2), $suite->getBenchmarks() );

        $this->assertSame( $suite, $suite->addBenchmark($mark1) );
        $this->assertSame( array($mark1, $mark2), $suite->getBenchmarks() );
    }

    public function testAdd ()
    {
        $suite = new \r8\Benchmark\Suite;

        $this->assertSame( $suite, $suite->add( "test", function () {} ) );

        $tests = $suite->getBenchmarks();
        $this->assertSame( 1, count($tests) );
        $this->assertArrayHasKey( 0, $tests );

        $this->assertThat( $tests[0], $this->isInstanceOf( '\r8\Benchmark' ) );
        $this->assertSame( "test", $tests[0]->getName() );
    }

    public function testRun_Empty ()
    {
        $suite = new \r8\Benchmark\Suite;
        $this->assertSame( array(), $suite->run() );
    }

    public function testRun_Full ()
    {
        $suite = new \r8\Benchmark\Suite( 100 );

        $result1 = $this->getTestResult();
        $suite->addBenchmark( $this->getTestBenchmark( 100, $result1 ) );

        $result2 = $this->getTestResult();
        $suite->addBenchmark( $this->getTestBenchmark( 100, $result2 ) );

        $this->assertSame( array($result1, $result2), $suite->run() );
    }

    public function testDumpCLI ()
    {
        $suite = new \r8\Benchmark\Suite( 100 );
        $suite->addBenchmark(
            $this->getTestBenchmark( 100, $this->getTestResult( "Test One", 5, 100) )
        );
        $suite->addBenchmark(
            $this->getTestBenchmark( 100, $this->getTestResult( "Test 2", 5, 1000) )
        );

        ob_start();
        $this->assertSame( $suite, $suite->dumpCLI() );
        $this->assertGreaterThan( 0, strlen(ob_get_clean()) );
    }

    public function testDumpHTML ()
    {
        $suite = new \r8\Benchmark\Suite( 100 );
        $suite->addBenchmark(
            $this->getTestBenchmark( 100, $this->getTestResult( "Test One", 5, 100) )
        );
        $suite->addBenchmark(
            $this->getTestBenchmark( 100, $this->getTestResult( "Test 2", 5, 1000) )
        );

        ob_start();
        $this->assertSame( $suite, $suite->dumpHTML() );
        $this->assertGreaterThan( 0, strlen(ob_get_clean()) );
    }

    public function testDump ()
    {
        $suite = new \r8\Benchmark\Suite( 100 );
        $suite->addBenchmark(
            $this->getTestBenchmark( 100, $this->getTestResult( "Test One", 5, 100) )
        );
        $suite->addBenchmark(
            $this->getTestBenchmark( 100, $this->getTestResult( "Test 2", 5, 1000) )
        );

        ob_start();
        $this->assertSame( $suite, $suite->dump() );
        $this->assertGreaterThan( 0, strlen(ob_get_clean()) );
    }

}

