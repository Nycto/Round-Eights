<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of RaindropPHP.
 *
 * RaindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * RaindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with RaindropPHP. If not, see <http://www.RaindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@Raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_validator_none extends PHPUnit_Framework_TestCase
{

    public function testNoValidators ()
    {
        $all = new \h2o\Validator\None;

        $result = $all->validate("example value");
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

        $none = new \h2o\Validator\None( $valid );
        $this->assertEquals( array($valid), $none->getValidators() );

        try {
            $none->validate("example value");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \h2o\Exception\Data $err ) {}
    }

    public function testValid ()
    {

        $valid1 = $this->getMock("h2o\iface\Validator", array("validate", "isValid"));
        $result1 = new \h2o\Validator\Result("example value");
        $result1->addError("Spoof Error");
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result1 ) );

        $valid2 = $this->getMock("h2o\iface\Validator", array("validate", "isValid"));
        $result2 = new \h2o\Validator\Result("example value");
        $result2->addError("Spoof Error");
        $valid2->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result2 ) );


        $none = new \h2o\Validator\None( $valid1, $valid2 );

        $result = $none->validate("example value");

        $this->assertThat( $result, $this->isInstanceOf("h2o\Validator\Result") );
        $this->assertTrue( $result->isValid() );

    }

    public function testOneInvalid ()
    {

        $result1 = new \h2o\Validator\Result("example value");
        $valid1 = $this->getMock("h2o\iface\Validator", array("validate", "isValid"));
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result1 ) );


        $none = new \h2o\Validator\None( $valid1 );

        $result = $none->validate("example value");

        $this->assertThat( $result, $this->isInstanceOf("h2o\Validator\Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Value is not valid"),
                $result->getErrors()
            );

    }

    public function testFirstInvalid ()
    {

        $result1 = new \h2o\Validator\Result("example value");
        $valid1 = $this->getMock("h2o\iface\Validator", array("validate", "isValid"));
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result1 ) );

        $valid2 = $this->getMock("h2o\iface\Validator", array("validate", "isValid"));
        $valid2->expects( $this->never() )
            ->method( "validate" );


        $none = new \h2o\Validator\None( $valid1, $valid2 );

        $result = $none->validate("example value");

        $this->assertThat( $result, $this->isInstanceOf("h2o\Validator\Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Value is not valid"),
                $result->getErrors()
            );

    }

    public function testSecondInvalid ()
    {

        $result1 = new \h2o\Validator\Result("example value");
        $result1->addError("This is an error");
        $valid1 = $this->getMock("h2o\iface\Validator", array("validate", "isValid"));
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result1 ) );

        $result2 = new \h2o\Validator\Result("example value");
        $valid2 = $this->getMock("h2o\iface\Validator", array("validate", "isValid"));
        $valid2->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result2 ) );


        $none = new \h2o\Validator\None( $valid1, $valid2 );

        $result = $none->validate("example value");

        $this->assertThat( $result, $this->isInstanceOf("h2o\Validator\Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Value is not valid"),
                $result->getErrors()
            );

    }

}

?>