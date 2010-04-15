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
 * Test Suite
 */
class classes_Template_Callback
{

    public static function suite()
    {
        $suite = new \r8\Test\Suite;
        $suite->addTestSuite( 'classes_Template_Callback_Standard' );
        $suite->addTestSuite( 'classes_Template_Callback_Output' );
        return $suite;
    }

}

/**
 * unit tests
 */
class classes_Template_Callback_Standard extends PHPUnit_Framework_TestCase
{

    public function testConstruct_Errors ()
    {
        try {
            new \r8\Template\Callback;
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Interaction $err ) {}

        try {
            new \r8\Template\Callback("Not Callable");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {}
    }

    public function testRender_noArgs ()
    {
        $callback = $this->getMock('stdClass', array('__invoke'));
        $callback->expects( $this->once() )
            ->method( "__invoke" )
            ->will( $this->returnValue( "data" ) );

        $tpl = new \r8\Template\Callback( $callback );

        $this->assertSame( "data", $tpl->render() );
    }

    public function testRender_withArgs ()
    {
        $callback = $this->getMock('stdClass', array('__invoke'));
        $callback->expects( $this->once() )
            ->method( "__invoke" )
            ->with(
                $this->equalTo( "a string" ),
                $this->equalTo( NULL ),
                $this->equalTo( 3.1415 )
            )
            ->will( $this->returnValue( "data" ) );

        $tpl = new \r8\Template\Callback( 'one', 'two', 'three', $callback );
        $tpl->set("one", "a string");
        $tpl->set("three", 3.1415);

        $this->assertSame( "data", $tpl->render() );
    }

}

class classes_Template_Callback_Output extends PHPUnit_Extensions_OutputTestCase
{

    public function testDisplay ()
    {
        $this->expectOutputString("data");

        $callback = $this->getMock('stdClass', array('__invoke'));
        $callback->expects( $this->once() )
            ->method( "__invoke" )
            ->with(
                $this->equalTo( "a string" ),
                $this->equalTo( NULL ),
                $this->equalTo( 3.1415 )
            )
            ->will( $this->returnValue( "data" ) );

        $tpl = new \r8\Template\Callback( 'one', 'two', 'three', $callback );
        $tpl->set("one", "a string");
        $tpl->set("three", 3.1415);

        $this->assertSame( $tpl, $tpl->display() );
    }

}

?>