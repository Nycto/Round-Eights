<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of raindropPHP.
 *
 * raindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * raindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with raindropPHP. If not, see <http://www.raindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_curry_invoke extends PHPUnit_Framework_TestCase
{

    public function testConstructError ()
    {
        try {
            new \h2o\Curry\Invoke( "This is not a valid method" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\Argument $err ) {
            $this->assertSame( "Invalid method name", $err->getMessage() );
        }
    }

    public function testConstructArgs ()
    {
        $curry = new \h2o\Curry\Invoke( "methodName" );
        $this->assertEquals( array(), $curry->getRight() );
        $this->assertEquals( array(), $curry->getLeft() );

        $curry = new \h2o\Curry\Invoke( "methodName", "one", "two" );
        $this->assertEquals( array("one", "two"), $curry->getRight() );
        $this->assertEquals( array(), $curry->getLeft() );
    }

    public function testExec_noArgs ()
    {
        $obj = $this->getMock('stdClass', array('method'));
        $obj->expects( $this->once() )
            ->method('method')
            ->will( $this->returnValue("Result") );

        $curry = new \h2o\Curry\Invoke('method');

        $this->assertSame(
                "Result",
                $curry->exec($obj)
            );
    }

    public function testExec_oneArgs ()
    {
        $obj = $this->getMock('stdClass', array('method'));
        $obj->expects( $this->once() )
            ->method('method')
            ->with(
                    $this->equalTo( "wakka" )
                )
            ->will( $this->returnValue("Result") );

        $curry = new \h2o\Curry\Invoke('method');

        $this->assertSame(
                "Result",
                $curry->exec($obj, "wakka")
            );
    }

    public function testExec_manyArgs ()
    {
        $obj = $this->getMock('stdClass', array('method'));
        $obj->expects( $this->once() )
            ->method('method')
            ->with(
                    $this->equalTo( "wakka" ),
                    $this->equalTo( "test1")
                )
            ->will( $this->returnValue("Result") );

        $curry = new \h2o\Curry\Invoke('method');

        $this->assertSame(
                "Result",
                $curry->exec($obj, "wakka", "test1")
            );
    }

    public function testExecWithLeftRight ()
    {
        $obj = $this->getMock('stdClass', array('method'));
        $obj->expects( $this->once() )
            ->method('method')
            ->with(
                    $this->equalTo("l1"),
                    $this->equalTo("l2"),
                    $this->equalTo(1),
                    $this->equalTo(2),
                    $this->equalTo(3),
                    $this->equalTo("r1"),
                    $this->equalTo("r2")
                )
            ->will( $this->returnValue("Result") );


        $curry = new \h2o\Curry\Invoke('method');
        $curry->setLeft("l1", "l2");
        $curry->setRight("r1", "r2");


        $this->assertSame(
                "Result",
                $curry->exec($obj, 1, 2, 3)
            );
    }

    public function testApply ()
    {
        $obj = $this->getMock('stdClass', array('method'));
        $obj->expects( $this->once() )
            ->method('method')
            ->with(
                    $this->equalTo("wakka"),
                    $this->equalTo("test1")
                )
            ->will( $this->returnValue("Result") );


        $curry = new \h2o\Curry\Invoke('method');


        $this->assertSame(
                "Result",
                $curry->apply($obj, array("wakka", "test1"))
            );

    }

    public function testApplyWithLeftRight ()
    {
        $obj = $this->getMock('stdClass', array('method'));
        $obj->expects( $this->once() )
            ->method('method')
            ->with(
                    $this->equalTo("l1"),
                    $this->equalTo("wakka"),
                    $this->equalTo("test1"),
                    $this->equalTo("r1")
                )
            ->will( $this->returnValue("Result") );


        $curry = new \h2o\Curry\Invoke('method');
        $curry->setLeft("l1");
        $curry->setRight("r1");


        $this->assertSame(
                "Result",
                $curry->apply($obj, array("wakka", "test1"))
            );

    }

    public function testInvoke ()
    {
        $obj = $this->getMock('stdClass', array('method'));
        $obj->expects( $this->once() )
            ->method('method')
            ->with(
                    $this->equalTo("wakka"),
                    $this->equalTo("test1")
                )
            ->will( $this->returnValue("Result") );


        $curry = new \h2o\Curry\Invoke('method');


        $this->assertSame(
                "Result",
                $curry($obj, "wakka", "test1")
            );

    }

    public function testInvokeWithLeftRight ()
    {
        $obj = $this->getMock('stdClass', array('method'));
        $obj->expects( $this->once() )
            ->method('method')
            ->with(
                    $this->equalTo("l1"),
                    $this->equalTo("wakka"),
                    $this->equalTo("test1"),
                    $this->equalTo("r1")
                )
            ->will( $this->returnValue("Result") );


        $curry = new \h2o\Curry\Invoke('method');
        $curry->setLeft("l1");
        $curry->setRight("r1");


        $this->assertSame(
                "Result",
                $curry($obj, "wakka", "test1")
            );

    }

    public function testFilter ()
    {

        $obj = $this->getMock('stdClass', array('method'));
        $obj->expects( $this->once() )
            ->method('method')
            ->with()
            ->will( $this->returnValue("Result") );


        $curry = new \h2o\Curry\Invoke('method');


        $this->assertSame(
                "Result",
                $curry->filter($obj)
            );
    }

    public function testFilterWithLeftRight ()
    {
        $obj = $this->getMock('stdClass', array('method'));
        $obj->expects( $this->once() )
            ->method('method')
            ->with(
                    $this->equalTo("l1"),
                    $this->equalTo("r1")
                )
            ->will( $this->returnValue("Result") );


        $curry = new \h2o\Curry\Invoke('method');
        $curry->setLeft("l1");
        $curry->setRight("r1");


        $this->assertSame(
                "Result",
                $curry->filter($obj)
            );
    }

}

?>