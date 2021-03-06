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
class classes_Validator_None extends PHPUnit_Framework_TestCase
{

    public function testNoValidators ()
    {
        $all = new \r8\Validator\None;

        $result = $all->validate("example value");
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

        $none = new \r8\Validator\None( $valid );
        $this->assertEquals( array($valid), $none->getValidators() );

        try {
            $none->validate("example value");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \r8\Exception\Data $err ) {}
    }

    public function testValid ()
    {

        $valid1 = $this->getMock("r8\iface\Validator");
        $result1 = new \r8\Validator\Result("example value");
        $result1->addError("Spoof Error");
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result1 ) );

        $valid2 = $this->getMock("r8\iface\Validator");
        $result2 = new \r8\Validator\Result("example value");
        $result2->addError("Spoof Error");
        $valid2->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result2 ) );


        $none = new \r8\Validator\None( $valid1, $valid2 );

        $result = $none->validate("example value");

        $this->assertThat( $result, $this->isInstanceOf("r8\Validator\Result") );
        $this->assertTrue( $result->isValid() );

    }

    public function testOneInvalid ()
    {

        $result1 = new \r8\Validator\Result("example value");
        $valid1 = $this->getMock("r8\iface\Validator");
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result1 ) );


        $none = new \r8\Validator\None( $valid1 );

        $result = $none->validate("example value");

        $this->assertThat( $result, $this->isInstanceOf("r8\Validator\Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Value is not valid"),
                $result->getErrors()
            );

    }

    public function testFirstInvalid ()
    {

        $result1 = new \r8\Validator\Result("example value");
        $valid1 = $this->getMock("r8\iface\Validator");
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result1 ) );

        $valid2 = $this->getMock("r8\iface\Validator");
        $valid2->expects( $this->never() )
            ->method( "validate" );


        $none = new \r8\Validator\None( $valid1, $valid2 );

        $result = $none->validate("example value");

        $this->assertThat( $result, $this->isInstanceOf("r8\Validator\Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Value is not valid"),
                $result->getErrors()
            );

    }

    public function testSecondInvalid ()
    {

        $result1 = new \r8\Validator\Result("example value");
        $result1->addError("This is an error");
        $valid1 = $this->getMock("r8\iface\Validator");
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result1 ) );

        $result2 = new \r8\Validator\Result("example value");
        $valid2 = $this->getMock("r8\iface\Validator");
        $valid2->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result2 ) );


        $none = new \r8\Validator\None( $valid1, $valid2 );

        $result = $none->validate("example value");

        $this->assertThat( $result, $this->isInstanceOf("r8\Validator\Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Value is not valid"),
                $result->getErrors()
            );

    }

}

