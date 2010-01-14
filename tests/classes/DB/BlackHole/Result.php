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

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_DB_Blackhole_Read extends PHPUnit_Framework_TestCase
{

    public function testConstruct_Empty ()
    {
        $result = new \r8\DB\BlackHole\Result;
        $this->assertSame( array(), $result->getAllRows() );
    }

    public function testConstruct_WithRows ()
    {
        $result = new \r8\DB\BlackHole\Result( array( 1, 2 ), array( 3, 4 ) );

        $this->assertSame(
            array( array( 1, 2 ), array( 3, 4 ) ),
            $result->getAllRows()
        );
    }

    public function testCount_Empty ()
    {
        $result = new \r8\DB\BlackHole\Result;
        $this->assertSame( 0, $result->count() );
    }

    public function testCount_WithRows ()
    {
        $result = new \r8\DB\BlackHole\Result( array( 1, 2 ), array( 3, 4 ) );
        $this->assertSame( 2, $result->count() );
    }

    public function testFields_Empty ()
    {
        $result = new \r8\DB\BlackHole\Result;
        $this->assertSame( array(), $result->getFields() );
    }

    public function testFields_WithRows ()
    {
        $result = new \r8\DB\BlackHole\Result(
            array( "one" => 1, "two" => 2 ),
            array( "one" => 3, "two" => 4 )
        );
        $this->assertSame( array("one", "two"), $result->getFields() );
    }

    public function testFetch_Empty ()
    {
        $result = new \r8\DB\BlackHole\Result;
        $this->assertNull( $result->fetch() );
        $this->assertNull( $result->fetch() );
        $this->assertNull( $result->fetch() );
    }

    public function testFetch_WithRows ()
    {
        $result = new \r8\DB\BlackHole\Result(
            array( 1, 2 ),
            array( 3, 4 ),
            array( 5, 6 )
        );
        $this->assertSame( array( 1, 2 ), $result->fetch() );
        $this->assertSame( array( 3, 4 ), $result->fetch() );
        $this->assertSame( array( 5, 6 ), $result->fetch() );
        $this->assertSame( NULL, $result->fetch() );
        $this->assertSame( NULL, $result->fetch() );
    }

    public function testSeek_Empty ()
    {
        $result = new \r8\DB\BlackHole\Result;

        $this->assertNull( $result->seek(1) );
        $this->assertNull( $result->fetch() );

        $this->assertNull( $result->seek(2) );
        $this->assertNull( $result->fetch() );
    }

    public function testSeek_WithRows ()
    {
        $result = new \r8\DB\BlackHole\Result(
            array( 1, 2 ),
            array( 3, 4 ),
            array( 5, 6 )
        );

        $this->assertNull( $result->seek(-1) );
        $this->assertSame( array( 5, 6 ), $result->fetch() );

        $this->assertNull( $result->seek(0) );
        $this->assertSame( array( 1, 2 ), $result->fetch() );

        $this->assertNull( $result->seek(1) );
        $this->assertSame( array( 3, 4 ), $result->fetch() );
    }

    public function testFree_Empty ()
    {
        $result = new \r8\DB\BlackHole\Result;
        $this->assertNull( $result->free() );
    }

    public function testFree_WithRows ()
    {
        $result = new \r8\DB\BlackHole\Result( array( 1, 2 ), array( 3, 4 ) );
        $this->assertNull( $result->free() );
        $this->assertSame( array(), $result->getAllRows() );
    }

}

?>