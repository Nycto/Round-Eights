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
 * This is a stub function used to test the callback validator
 *
 * @param mixed $value The value being validated
 * @return null|string
 */
function stub_validator_callback_func ( $value )
{
    if ( $value != "cheese" )
        return "Value must be cheese";
}

/**
 * unit tests
 */
class classes_validator_callback extends PHPUnit_Framework_TestCase
{

    static public function staticCallbackTest ( $value )
    {
        if ( $value != "jelly" )
            return "Value must be jelly";
    }

    public function instanceCallbackTest ( $value )
    {
        if ( $value != "milk" )
            return "Value must be milk";
    }

    public function __invoke ( $value )
    {
        if ( $value != "sugar" )
            return "Value must be sugar";
    }

    public function testClosure ()
    {
        $valid = new \r8\Validator\Callback(function ($value) {
            if ( $value != "tonic" )
                return "Value must be tonic";
        });

        $this->assertTrue( $valid->isValid("tonic") );

        $result = $valid->validate("gin");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Value must be tonic"),
                $result->getErrors()
            );
    }

    public function testFunction ()
    {
        $valid = new \r8\Validator\Callback("stub_validator_callback_func");

        $this->assertTrue( $valid->isValid("cheese") );

        $result = $valid->validate("Crackers");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Value must be cheese"),
                $result->getErrors()
            );
    }

    public function testStaticMethod ()
    {
        $valid = new \r8\Validator\Callback(array(__CLASS__, "staticCallbackTest"));

        $this->assertTrue( $valid->isValid("jelly") );

        $result = $valid->validate("peanut butter");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Value must be jelly"),
                $result->getErrors()
            );
    }

    public function testInstanceMethod ()
    {
        $valid = new \r8\Validator\Callback(array($this, "instanceCallbackTest"));

        $this->assertTrue( $valid->isValid("milk") );

        $result = $valid->validate("cookies");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Value must be milk"),
                $result->getErrors()
            );
    }

    public function testInvokableObject ()
    {
        $valid = new \r8\Validator\Callback($this);

        $this->assertTrue( $valid->isValid("sugar") );

        $result = $valid->validate("cream");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Value must be sugar"),
                $result->getErrors()
            );
    }

    public function testArrayResult ()
    {
        $valid = new \r8\Validator\Callback(function ($value) {
            if ( $value > 10 )
                return array("Must be <= 10", array("Greater than 10"));
            return array("", NULL);
        });

        $this->assertTrue( $valid->isValid(5) );

        $result = $valid->validate(20);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must be <= 10", "Greater than 10"),
                $result->getErrors()
            );
    }

    public function testAryResult ()
    {
        $valid = new \r8\Validator\Callback(function ($value) {
            if ( $value > 10 )
                return array("Must be <= 10", "Greater than 10");
            return array();
        });

        $this->assertTrue( $valid->isValid(5) );

        $result = $valid->validate(20);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must be <= 10", "Greater than 10"),
                $result->getErrors()
            );
    }

    public function testTraversableResult ()
    {
        $valid = new \r8\Validator\Callback(function ($value) {
            if ( $value > 10 )
                return new ArrayIterator(array("Must be <= 10", "Greater than 10"));
            return new ArrayIterator;
        });

        $this->assertTrue( $valid->isValid(5) );

        $result = $valid->validate(20);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must be <= 10", "Greater than 10"),
                $result->getErrors()
            );
    }

    public function testResultObjectResult ()
    {
        $valid = new \r8\Validator\Callback(function ($value) {
            $result = new \r8\Validator\Result($value);
            if ( $value > 10 )
                $result->addError("Error one")->addError("error two");
            return $result;
        });

        $this->assertTrue( $valid->isValid(5) );

        $result = $valid->validate(20);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Error one", "error two"),
                $result->getErrors()
            );
    }

    public function testStringResult ()
    {
        $valid = new \r8\Validator\Callback(function ($value) {
            if ( $value > 10 )
                return "Error";
            return "   ";
        });

        $this->assertTrue( $valid->isValid(5) );

        $result = $valid->validate(20);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Error"),
                $result->getErrors()
            );
    }

    public function testFloatResult ()
    {
        $valid = new \r8\Validator\Callback(function ($value) {
            if ( $value > 10 )
                return 1.505;
            return 0.0;
        });

        $this->assertTrue( $valid->isValid(5) );

        $result = $valid->validate(20);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("1.505"),
                $result->getErrors()
            );
    }

    public function testIntegerResult ()
    {
        $valid = new \r8\Validator\Callback(function ($value) {
            if ( $value > 10 )
                return 99;
            return 0;
        });

        $this->assertTrue( $valid->isValid(5) );

        $result = $valid->validate(20);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("99"),
                $result->getErrors()
            );
    }

    public function testNullResult ()
    {
        $valid = new \r8\Validator\Callback(function ($value) {
            return null;
        });

        $this->assertTrue( $valid->isValid(5) );
    }

    public function testBoolResult ()
    {
        $valid = new \r8\Validator\Callback(function ($value) {
            if ( $value > 10 )
                return TRUE;
            return FALSE;
        });

        $this->assertTrue( $valid->isValid(5) );
        $this->assertTrue( $valid->isValid(20) );
    }

}

