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
class classes_validator_multifield extends PHPUnit_Framework_TestCase
{

    public function testNonBasics ()
    {
        $field = $this->getMock("h2o\Form\Multi", array("_mock"), array("fld"));
        $valid = new \h2o\Validator\MultiField( $field );

        $result = $valid->validate( array() );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Value is not a valid selection"),
                $result->getErrors()
            );


        $result = $valid->validate( $this->getMock("stub") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Value is not a valid selection"),
                $result->getErrors()
            );
    }

    public function testEmptyField ()
    {
        $field = $this->getMock("h2o\Form\Multi", array("_mock"), array("fld"));
        $valid = new \h2o\Validator\MultiField( $field );

        $result = $valid->validate( 50 );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Value is not a valid selection"),
                $result->getErrors()
            );
    }

    public function testInvalidOption ()
    {
        $field = $this->getMock("h2o\Form\Multi", array("_mock"), array("fld"));
        $field->importOptions(array("one" => "Single", 2 => "Double", "three" => "Triple"));

        $valid = new \h2o\Validator\MultiField( $field );

        $result = $valid->validate( 4 );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Value is not a valid selection"),
                $result->getErrors()
            );

        $result = $valid->validate( "Triple" );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Value is not a valid selection"),
                $result->getErrors()
            );

        $result = $valid->validate( "ONE" );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Value is not a valid selection"),
                $result->getErrors()
            );
    }

    public function testValid ()
    {
        $field = $this->getMock("h2o\Form\Multi", array("_mock"), array("fld"));
        $field->importOptions(array("one" => "Single", 2 => "Double", "three" => "Triple"));

        $valid = new \h2o\Validator\MultiField( $field );

        $this->assertTrue( $valid->isValid( "one" ) );
        $this->assertTrue( $valid->isValid( 2 ) );
        $this->assertTrue( $valid->isValid( "2" ) );
        $this->assertTrue( $valid->isValid( "three" ) );
    }

}

?>