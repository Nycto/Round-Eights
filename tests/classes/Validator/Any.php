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
class classes_validator_any extends PHPUnit_Framework_TestCase
{

    public function testNoValidators ()
    {
        $any = new \h2o\Validator\Any;

        $result = $any->validate("example value");
        $this->assertThat( $result, $this->isInstanceOf("h2o\Validator\Result") );
        $this->assertTrue( $result->isValid() );

    }

    public function testInvalidResult ()
    {
        $valid = $this->getMock("h2o\iface\Validator", array("validate", "isValid"));
        $valid->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue("This is an invalid result") );

        $any = new \h2o\Validator\Any( $valid );
        $this->assertEquals( array($valid), $any->getValidators() );

        try {
            $any->validate("example value");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \h2o\Exception\Data $err ) {}
    }

    public function testFirstValid ()
    {

        $valid1 = $this->getMock("h2o\iface\Validator", array("validate", "isValid"));
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( new \h2o\Validator\Result("example value") ) );

        // This should never be called because the first validator should short circuit things
        $valid2 = $this->getMock("h2o\iface\Validator", array("validate", "isValid"));
        $valid2->expects( $this->never() )
            ->method( "validate" );


        $any = new \h2o\Validator\Any( $valid1, $valid2 );

        $result = $any->validate("example value");

        $this->assertThat( $result, $this->isInstanceOf("h2o\Validator\Result") );
        $this->assertTrue( $result->isValid() );

    }

    public function testSecondValid ()
    {
        $result1 = new \h2o\Validator\Result("example value");
        $result1->addError("This is an Error");

        $valid1 = $this->getMock("h2o\iface\Validator", array("validate", "isValid"));
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result1 ) );

        $valid2 = $this->getMock("h2o\iface\Validator", array("validate", "isValid"));
        $valid2->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( new \h2o\Validator\Result("example value") ) );


        $any = new \h2o\Validator\Any( $valid1, $valid2 );

        $result = $any->validate("example value");

        $this->assertThat( $result, $this->isInstanceOf("h2o\Validator\Result") );
        $this->assertTrue( $result->isValid() );

    }

    public function testOneInvalid ()
    {

        $result1 = new \h2o\Validator\Result("example value");
        $result1->addError("This is an Error");

        $valid1 = $this->getMock("h2o\iface\Validator", array("validate", "isValid"));
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result1 ) );


        $any = new \h2o\Validator\Any( $valid1 );

        $result = $any->validate("example value");

        $this->assertThat( $result, $this->isInstanceOf("h2o\Validator\Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("This is an Error"),
                $result->getErrors()
            );

    }

    public function testMultipleInvalid ()
    {

        $result1 = new \h2o\Validator\Result("example value");
        $result1->addError("This is an Error");

        $valid1 = $this->getMock("h2o\iface\Validator", array("validate", "isValid"));
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result1 ) );


        $result2 = new \h2o\Validator\Result("example value");
        $result2->addError("This is another Error");

        $valid2 = $this->getMock("h2o\iface\Validator", array("validate", "isValid"));
        $valid2->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result2 ) );


        $any = new \h2o\Validator\Any( $valid1, $valid2 );

        $result = $any->validate("example value");

        $this->assertThat( $result, $this->isInstanceOf("h2o\Validator\Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("This is an Error", "This is another Error"),
                $result->getErrors()
            );

    }

    public function testDuplicateErrors ()
    {

        $result1 = new \h2o\Validator\Result("example value");
        $result1->addError("This is an Error");

        $valid1 = $this->getMock("h2o\iface\Validator", array("validate", "isValid"));
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result1 ) );


        $result2 = new \h2o\Validator\Result("example value");
        $result2->addError("This is an Error");

        $valid2 = $this->getMock("h2o\iface\Validator", array("validate", "isValid"));
        $valid2->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result2 ) );


        $any = new \h2o\Validator\Any( $valid1, $valid2 );

        $result = $any->validate("example value");

        $this->assertThat( $result, $this->isInstanceOf("h2o\Validator\Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("This is an Error"),
                $result->getErrors()
            );

    }

}

?>