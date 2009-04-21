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
class classes_validator extends PHPUnit_Framework_TestCase
{

    public function getMockValidator ( $return )
    {
        $mock = $this->getMock("cPHP\Validator", array("process"));
        $mock->expects( $this->once() )
            ->method( "process" )
            ->with( $this->equalTo("To Validate") )
            ->will( $this->returnValue( $return ) );

        return $mock;
    }

    public function testCallStatic ()
    {
        $validator = \cPHP\Validator::Email();
        $this->assertThat( $validator, $this->isInstanceOf("cPHP\Validator\Email") );

        try {
            \cPHP\Validator::ThisIsNotAValidator();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame( "Validator could not be found in \cPHP\Validator namespace", $err->getMessage() );
        }

        try {
            \cPHP\Validator::Result();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame( "Class does not implement \cPHP\iface\Validator", $err->getMessage() );
        }
    }

    public function testNullResult ()
    {
        $mock = $this->getMockValidator ( NULL );

        $result = $mock->validate("To Validate");

        $this->assertThat( $result, $this->isInstanceOf("cPHP\Validator\Result") );
        $this->assertTrue( $result->isValid() );
    }

    public function testFloatResult ()
    {
        $mock = $this->getMockValidator ( 278.09 );

        $result = $mock->validate("To Validate");

        $this->assertThat( $result, $this->isInstanceOf("cPHP\Validator\Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals( array("278.09"), $result->getErrors() );


        $mock = $this->getMockValidator ( 0.0 );
        $this->assertTrue( $mock->isValid("To Validate") );
    }

    public function testIntegerResult ()
    {
        $mock = $this->getMockValidator ( 278 );

        $result = $mock->validate("To Validate");

        $this->assertThat( $result, $this->isInstanceOf("cPHP\Validator\Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals( array("278"), $result->getErrors() );


        $mock = $this->getMockValidator ( 0 );
        $this->assertTrue( $mock->isValid("To Validate") );
    }

    public function testBooleanResult ()
    {
        $mock = $this->getMockValidator ( TRUE );
        $this->assertTrue( $mock->isValid("To Validate") );

        $mock = $this->getMockValidator ( FALSE );
        $this->assertTrue( $mock->isValid("To Validate") );
    }

    public function testStringError ()
    {
        $mock = $this->getMockValidator ("This is an Error");

        $result = $mock->validate("To Validate");

        $this->assertThat( $result, $this->isInstanceOf("cPHP\Validator\Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals( array("This is an Error"), $result->getErrors() );
    }

    public function testArrayError ()
    {
        $mock = $this->getMockValidator( array("First Error", "Second Error") );

        $result = $mock->validate("To Validate");

        $this->assertThat( $result, $this->isInstanceOf("cPHP\Validator\Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals( array("First Error", "Second Error"), $result->getErrors() );


        $mock = $this->getMockValidator( array( array("First Error"), "", "Second Error") );

        $result = $mock->validate("To Validate");

        $this->assertThat( $result, $this->isInstanceOf("cPHP\Validator\Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals( array("First Error", "Second Error"), $result->getErrors() );
    }

    public function testEmptyArrayError ()
    {
        $mock = $this->getMockValidator( array() );

        $result = $mock->validate("To Validate");

        $this->assertThat( $result, $this->isInstanceOf("cPHP\Validator\Result") );
        $this->assertTrue( $result->isValid() );


        $mock = $this->getMockValidator( array( "", FALSE, "  " ) );

        $result = $mock->validate("To Validate");

        $this->assertThat( $result, $this->isInstanceOf("cPHP\Validator\Result") );
        $this->assertTrue( $result->isValid() );
    }

    public function testResultError ()
    {
        $return = new \cPHP\Validator\Result("To Validate");
        $return->addErrors("First Error", "Second Error");
        $mock = $this->getMockValidator( $return );

        $result = $mock->validate("To Validate");

        $this->assertThat( $result, $this->isInstanceOf("cPHP\Validator\Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals( array("First Error", "Second Error"), $result->getErrors() );

    }

    public function testEmptyResultError ()
    {
        $return = new \cPHP\Validator\Result("To Validate");
        $mock = $this->getMockValidator( $return );

        $result = $mock->validate("To Validate");

        $this->assertThat( $result, $this->isInstanceOf("cPHP\Validator\Result") );
        $this->assertTrue( $result->isValid() );

    }

    public function testCustomErrors ()
    {
        $mock = $this->getMockValidator( "Default Error" );
        $mock->addError("Custom Error Message");

        $result = $mock->validate("To Validate");

        $this->assertThat( $result, $this->isInstanceOf("cPHP\Validator\Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals( array("Custom Error Message"), $result->getErrors() );

    }

    public function testIsValid ()
    {
        $passes = $this->getMockValidator( NULL );
        $this->assertTrue( $passes->isValid("To Validate") );

        $fails = $this->getMockValidator( "Default Error" );
        $this->assertFalse( $fails->isValid("To Validate") );
    }

    public function testEnsure ()
    {
        $passes = $this->getMockValidator( NULL );
        $this->assertSame( $passes, $passes->ensure("To Validate") );


        $fails = $this->getMockValidator( "This is an error" );

        try {
            $fails->ensure("To Validate");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Data $err ) {
            $this->assertSame(
                    "This is an error",
                    $err->getMessage()
                );
        }
    }

}

?>