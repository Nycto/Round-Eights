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
class classes_validator_all extends PHPUnit_Framework_TestCase
{

    public function testNoValidators ()
    {
        $all = new \h2o\Validator\All;

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

        $all = new \h2o\Validator\All( $valid );
        $this->assertEquals( array($valid), $all->getValidators() );

        try {
            $all->validate("example value");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \h2o\Exception\Data $err ) {}
    }

    public function testValid ()
    {

        $valid1 = $this->getMock("h2o\iface\Validator", array("validate", "isValid"));
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( new \h2o\Validator\Result("example value") ) );

        $valid2 = $this->getMock("h2o\iface\Validator", array("validate", "isValid"));
        $valid2->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( new \h2o\Validator\Result("example value") ) );


        $all = new \h2o\Validator\All( $valid1, $valid2 );

        $result = $all->validate("example value");

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


        $all = new \h2o\Validator\All( $valid1 );

        $result = $all->validate("example value");

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


        $all = new \h2o\Validator\All( $valid1, $valid2 );

        $result = $all->validate("example value");

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


        $all = new \h2o\Validator\All( $valid1, $valid2 );

        $result = $all->validate("example value");

        $this->assertThat( $result, $this->isInstanceOf("h2o\Validator\Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("This is an Error"),
                $result->getErrors()
            );

    }

}

?>