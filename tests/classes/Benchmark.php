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

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * unit tests
 */
class classes_Benchmark extends PHPUnit_Framework_TestCase
{

    public function testConstruct_Error ()
    {
        try {
            new \r8\Benchmark( "  ", "Not a method" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }

        try {
            new \r8\Benchmark( "test", "Not a method" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "Must be callable", $err->getMessage() );
        }
    }

    public function testGetName ()
    {
        $bench = new \r8\Benchmark( "test", function () {} );
        $this->assertSame( "test", $bench->getName() );
    }

    public function testRun ()
    {
        $bench = new \r8\Benchmark( "test", function () {} );

        $result = $bench->run();
        $this->assertThat( $result, $this->isInstanceOf( '\r8\Benchmark\Result' ) );
        $this->assertSame( 1000, $result->count() );

        $result = $bench->run( 500 );
        $this->assertThat( $result, $this->isInstanceOf( '\r8\Benchmark\Result' ) );
        $this->assertSame( 500, $result->count() );

        $result = $bench->run( -10 );
        $this->assertThat( $result, $this->isInstanceOf( '\r8\Benchmark\Result' ) );
        $this->assertSame( 1, $result->count() );
    }

}

?>