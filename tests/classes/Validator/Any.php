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
class classes_Validator_Any extends PHPUnit_Framework_TestCase
{

    public function testNoValidators ()
    {
        $any = new \r8\Validator\Any;

        $result = $any->validate("example value");
        $this->assertThat( $result, $this->isInstanceOf("r8\Validator\Result") );
        $this->assertTrue( $result->isValid() );

    }

    public function testInvalidResult ()
    {
        $valid = $this->getMock("r8\iface\Validator");
        $valid->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue("This is an invalid result") );

        $any = new \r8\Validator\Any( $valid );
        $this->assertEquals( array($valid), $any->getValidators() );

        try {
            $any->validate("example value");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \r8\Exception\Data $err ) {}
    }

    public function testFirstValid ()
    {

        $valid1 = $this->getMock("r8\iface\Validator");
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( new \r8\Validator\Result("example value") ) );

        // This should never be called because the first validator should short circuit things
        $valid2 = $this->getMock("r8\iface\Validator");
        $valid2->expects( $this->never() )
            ->method( "validate" );


        $any = new \r8\Validator\Any( $valid1, $valid2 );

        $result = $any->validate("example value");

        $this->assertThat( $result, $this->isInstanceOf("r8\Validator\Result") );
        $this->assertTrue( $result->isValid() );

    }

    public function testSecondValid ()
    {
        $result1 = new \r8\Validator\Result("example value");
        $result1->addError("This is an Error");

        $valid1 = $this->getMock("r8\iface\Validator");
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result1 ) );

        $valid2 = $this->getMock("r8\iface\Validator");
        $valid2->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( new \r8\Validator\Result("example value") ) );


        $any = new \r8\Validator\Any( $valid1, $valid2 );

        $result = $any->validate("example value");

        $this->assertThat( $result, $this->isInstanceOf("r8\Validator\Result") );
        $this->assertTrue( $result->isValid() );

    }

    public function testOneInvalid ()
    {

        $result1 = new \r8\Validator\Result("example value");
        $result1->addError("This is an Error");

        $valid1 = $this->getMock("r8\iface\Validator");
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result1 ) );


        $any = new \r8\Validator\Any( $valid1 );

        $result = $any->validate("example value");

        $this->assertThat( $result, $this->isInstanceOf("r8\Validator\Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("This is an Error"),
                $result->getErrors()
            );

    }

    public function testMultipleInvalid ()
    {

        $result1 = new \r8\Validator\Result("example value");
        $result1->addError("This is an Error");

        $valid1 = $this->getMock("r8\iface\Validator");
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result1 ) );


        $result2 = new \r8\Validator\Result("example value");
        $result2->addError("This is another Error");

        $valid2 = $this->getMock("r8\iface\Validator");
        $valid2->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result2 ) );


        $any = new \r8\Validator\Any( $valid1, $valid2 );

        $result = $any->validate("example value");

        $this->assertThat( $result, $this->isInstanceOf("r8\Validator\Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("This is an Error", "This is another Error"),
                $result->getErrors()
            );

    }

    public function testDuplicateErrors ()
    {

        $result1 = new \r8\Validator\Result("example value");
        $result1->addError("This is an Error");

        $valid1 = $this->getMock("r8\iface\Validator");
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result1 ) );


        $result2 = new \r8\Validator\Result("example value");
        $result2->addError("This is an Error");

        $valid2 = $this->getMock("r8\iface\Validator");
        $valid2->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result2 ) );


        $any = new \r8\Validator\Any( $valid1, $valid2 );

        $result = $any->validate("example value");

        $this->assertThat( $result, $this->isInstanceOf("r8\Validator\Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("This is an Error"),
                $result->getErrors()
            );

    }

}

?>