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
class classes_Benchmark_Result extends PHPUnit_Framework_TestCase
{

    public function testAddTime ()
    {
        $result = new \r8\Benchmark\Result;
        $this->assertSame( array(), $result->getTimes() );

        $this->assertSame( $result, $result->addTime(0.5) );
        $this->assertSame( $result, $result->addTime(0.05) );
        $this->assertSame( $result, $result->addTime(0.001) );
        $this->assertSame( $result, $result->addTime(0.05) );
        $this->assertSame( array(0.5, 0.05, 0.001, 0.05), $result->getTimes() );
    }

    public function testCount ()
    {
        $result = new \r8\Benchmark\Result;
        $this->assertSame( 0, $result->count() );

        $result->addTime(0.5);
        $result->addTime(0.05);
        $this->assertSame( 2, $result->count() );

        $result->addTime(0.001);
        $result->addTime(0.05);
        $this->assertSame( 4, $result->count() );
    }

    public function testGetTotalTime ()
    {
        $result = new \r8\Benchmark\Result;
        $this->assertSame( 0, $result->getTotalTime() );

        $result->addTime(0.5);
        $result->addTime(0.05);
        $this->assertSame( "0.55", (string) $result->getTotalTime() );

        $result->addTime(0.001);
        $result->addTime(0.05);
        $this->assertSame( "0.601", (string) $result->getTotalTime() );
    }

    public function testGetAverageTime ()
    {
        $result = new \r8\Benchmark\Result;
        $this->assertSame( 0, $result->getAverageTime() );

        $result->addTime(0.5);
        $result->addTime(0.05);
        $result->addTime(0.001);
        $result->addTime(0.05);

        $this->assertSame( "0.15025", (string) $result->getAverageTime() );
    }

}

?>