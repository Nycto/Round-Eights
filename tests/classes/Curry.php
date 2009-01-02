<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of commonPHP.
 *
 * commonPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * commonPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with commonPHP. If not, see <http://www.commonphp.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@commonphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * unit tests
 */
class classes_curry extends PHPUnit_Framework_TestCase
{

    public function testCallStatic ()
    {
        $curry = \cPHP\Curry::Call("trim");
        $this->assertThat( $curry, $this->isInstanceOf("cPHP\\Curry\\Call") );
        $this->assertSame( "trimmed", $curry("  trimmed  ") );


        $curry = \cPHP\Curry::Call("rtrim", "-");
        $this->assertThat( $curry, $this->isInstanceOf("cPHP\\Curry\\Call") );
        $this->assertSame( "--trimmed", $curry("--trimmed--") );


        $curry = \cPHP\Curry::Call("str_replace", "!", "original");
        $this->assertThat( $curry, $this->isInstanceOf("cPHP\\Curry\\Call") );
        $this->assertSame( "or!g!nal", $curry("i") );


        try {
            \cPHP\Curry::ThisIsNotReal();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame( 'Class could not be found in \\cPHP\\Curry namespace', $err->getMessage() );
        }
    }

    public function testSet ()
    {
        $curry = $this->getMock('\cPHP\Curry', array("filter"));

        $this->assertEquals( array(), $curry->getLeft() );
        $this->assertEquals( array(), $curry->getRight() );

        $this->assertSame(
                $curry,
                $curry->setRight("wakka", "peanut")
            );

        $this->assertEquals( array(), $curry->getLeft() );
        $this->assertEquals( array("wakka", "peanut"), $curry->getRight() );

        $this->assertSame(
                $curry,
                $curry->setLeft("bean", "orange")
            );

        $this->assertEquals( array("bean", "orange"), $curry->getLeft() );
        $this->assertEquals( array("wakka", "peanut"), $curry->getRight() );
    }

    public function testSetByArray ()
    {
        $curry = $this->getMock("cPHP\\Curry", array("filter"));

        $this->assertEquals( array(), $curry->getLeft() );
        $this->assertEquals( array(), $curry->getRight() );

        $this->assertSame(
                $curry,
                $curry->setRightByArray( array("wakka", "peanut") )
            );

        $this->assertEquals( array(), $curry->getLeft() );
        $this->assertEquals( array("wakka", "peanut"), $curry->getRight() );


        $this->assertSame(
                $curry,
                $curry->setLeftByArray( array("bean", "orange") )
            );

        $this->assertEquals( array("bean", "orange"), $curry->getLeft() );
        $this->assertEquals( array("wakka", "peanut"), $curry->getRight() );
    }

    public function testClearLeftRight ()
    {
        $curry = $this->getMock("cPHP\Curry", array("filter"));

        $curry->setRight("wakka", "peanut");
        $curry->setLeft("bean", "orange");

        $this->assertEquals( array("bean", "orange"), $curry->getLeft() );
        $this->assertEquals( array("wakka", "peanut"), $curry->getRight() );

        $this->assertSame(
                $curry,
                $curry->clearLeft()
            );

        $this->assertEquals( array(), $curry->getLeft() );
        $this->assertEquals( array("wakka", "peanut"), $curry->getRight() );

        $this->assertSame(
                $curry,
                $curry->clearRight()
            );

        $this->assertEquals( array(), $curry->getLeft() );
        $this->assertEquals( array(), $curry->getRight() );
    }

    public function testClearArgs ()
    {
        $curry = $this->getMock("cPHP\Curry", array("filter"));

        $curry->setRight("wakka", "peanut");
        $curry->setLeft("bean", "orange");

        $this->assertEquals( array("bean", "orange"), $curry->getLeft() );
        $this->assertEquals( array("wakka", "peanut"), $curry->getRight() );

        $this->assertSame(
                $curry,
                $curry->clearArgs()
            );

        $this->assertEquals( array(), $curry->getLeft() );
        $this->assertEquals( array(), $curry->getRight() );
    }

    public function testOffset ()
    {

        $curry = $this->getMock("cPHP\Curry", array("filter"));

        $this->assertEquals( 0, $curry->getOffset() );

        $this->assertSame( $curry, $curry->setOffset( 1 ) );

        $this->assertEquals( 1, $curry->getOffset() );

        $this->assertSame( $curry, $curry->clearOffset() );

        $this->assertEquals( 0, $curry->getOffset() );

        $this->assertSame( $curry, $curry->setOffset( 5 ) );

        $this->assertEquals( 5, $curry->getOffset() );
    }

    public function testLimit ()
    {
        $curry = $this->getMock("cPHP\Curry", array("filter"));

        $this->assertFalse( $curry->issetLimit() );
        $this->assertFalse( $curry->getLimit() );

        $this->assertSame( $curry, $curry->setLimit( 2 ) );

        $this->assertTrue( $curry->issetLimit() );
        $this->assertEquals( 2, $curry->getLimit() );

        $this->assertSame( $curry, $curry->clearLimit() );

        $this->assertFalse( $curry->issetLimit() );
        $this->assertFalse( $curry->getLimit() );

        $this->assertSame( $curry, $curry->setLimit( 5 ) );

        $this->assertTrue( $curry->issetLimit() );
        $this->assertEquals( 5, $curry->getLimit() );
    }

    public function testClearSlicing ()
    {

        $curry = $this->getMock("cPHP\Curry", array("filter"));

        $curry->setLimit( 1 );
        $curry->setOffset( 1 );

        $this->assertSame( $curry, $curry->clearSlicing() );

        $this->assertEquals( 0, $curry->getOffset() );
        $this->assertFalse( $curry->issetLimit() );
    }

    public function testClear ()
    {

        $curry = $this->getMock("cPHP\Curry", array("filter"));

        $curry->setRight("wakka", "peanut");
        $curry->setLeft("bean", "orange");
        $curry->setLimit( 1 );
        $curry->setOffset( 1 );


        $this->assertSame( $curry, $curry->clear() );

        $this->assertEquals( array(), $curry->getLeft() );
        $this->assertEquals( array(), $curry->getRight() );
        $this->assertEquals( 0, $curry->getOffset() );
        $this->assertFalse( $curry->issetLimit() );

    }

    public function testCollectArgs ()
    {
        $curry = $this->getMock("cPHP\Curry", array("filter"));

        $this->assertEquals(
                array(1, 2, 3),
                $curry->collectArgs( array(1, 2, 3) )
            );

        $curry->setLeft("l1", "l2");
        $this->assertEquals(
                array("l1", "l2", 1, 2, 3),
                $curry->collectArgs( array(1, 2, 3) )
            );

        $curry->setRight("r1", "r2");
        $this->assertEquals(
                array("l1", "l2", 1, 2, 3, "r1", "r2"),
                $curry->collectArgs( array(1, 2, 3) )
            );

        $curry->setOffset( 1 );
        $this->assertEquals(
                array("l1", "l2", 2, 3, "r1", "r2"),
                $curry->collectArgs( array(1, 2, 3) )
            );

        $curry->setLimit( 1 );

        $this->assertEquals(
                array("l1", "l2", 2, "r1", "r2"),
                $curry->collectArgs( array(1, 2, 3) )
            );

        $curry->clear();

        $curry->setLimit( 2 );
        $this->assertEquals(
                array(1, 2),
                $curry->collectArgs( array(1, 2, 3) )
            );
    }

}

?>