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
class classes_Curry_Call extends PHPUnit_Framework_TestCase
{

    // This method exists simply to test the calling of static methods
    static public function staticMethod ()
    {
        return "called";
    }

    public function testCallInternal ()
    {
        $callback = new \r8\Curry\Call("trim");
        $this->assertEquals( "trimmed", $callback("  trimmed  ") );
    }

    public function testCallClosure ()
    {
        $callback = new \r8\Curry\Call(function ( $value ) {
            return trim($value);
        });

        $this->assertEquals( "trimmed", $callback("  trimmed  ") );
    }

    public function testCallMethod ()
    {
        $hasMethod = $this->getMock('testCall', array('toCall'));

        $hasMethod
            ->expects( $this->once() )
            ->method('toCall')
            ->with( $this->equalTo('argument') )
            ->will( $this->returnValue("called") );

        $callback = new \r8\Curry\Call( array($hasMethod, "toCall") );

        $this->assertSame( "called", $callback("argument") );
    }

    public function testCallInvokable ()
    {
        $invokable = $this->getMock('Invokable', array('__invoke'));

        $invokable
            ->expects( $this->once() )
            ->method('__invoke')
            ->with( $this->equalTo('argument') )
            ->will( $this->returnValue("called") );

        $callback = new \r8\Curry\Call($invokable);

        $this->assertSame( "called", $callback("argument") );

    }

    public function testCallStatic ()
    {
        $callback = new \r8\Curry\Call( array(__CLASS__, "staticMethod") );

        $this->assertEquals( "called", $callback("argument") );
    }

    public function testInstantiateException ()
    {
        $this->setExpectedException('\r8\Exception\Argument');
        $callback = new \r8\Curry\Call( "ThisIsUnUncallableValue" );
    }

    public function testWithArgs ()
    {
        $invokable = $this->getMock('Invokable', array('__invoke'));

        $invokable
            ->expects( $this->once() )
            ->method('__invoke')
            ->with(
                    $this->equalTo('l1'),
                    $this->equalTo('l2'),
                    $this->equalTo('arg1'),
                    $this->equalTo('arg2'),
                    $this->equalTo('r1'),
                    $this->equalTo('r2')
                )
            ->will( $this->returnValue("called") );

        $callback = new \r8\Curry\Call($invokable);
        $callback->setLeft("l1", "l2");
        $callback->setRight("r1", "r2");

        $this->assertSame( "called", $callback("arg1", "arg2") );
    }

}

?>