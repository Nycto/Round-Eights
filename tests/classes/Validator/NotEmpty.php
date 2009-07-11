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
 * @author James Frasca <James@RaindropPHP.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_validator_notempty extends PHPUnit_Framework_TestCase
{

    public function testInvalid_noFlags ()
    {

        $validator = new \h2o\Validator\NotEmpty;

        $result = $validator->validate("");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be empty"),
                $result->getErrors()
            );

        $result = $validator->validate("    ");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be empty"),
                $result->getErrors()
            );

        $result = $validator->validate(NULL);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be empty"),
                $result->getErrors()
            );

        $result = $validator->validate(0);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be empty"),
                $result->getErrors()
            );

        $result = $validator->validate(FALSE);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be empty"),
                $result->getErrors()
            );

        $result = $validator->validate(array());
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be empty"),
                $result->getErrors()
            );

    }

    public function testInvalid_flags ()
    {
        $validator = new \h2o\Validator\NotEmpty( \h2o\ALLOW_BLANK );
        $this->assertTrue( $validator->isValid("") );

        $result = $validator->validate("    ");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be empty"),
                $result->getErrors()
            );


        $validator = new \h2o\Validator\NotEmpty( \h2o\ALLOW_NULL );
        $this->assertTrue( $validator->isValid(NULL) );

        $result = $validator->validate(0);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be empty"),
                $result->getErrors()
            );


        $validator = new \h2o\Validator\NotEmpty( \h2o\ALLOW_FALSE );
        $this->assertTrue( $validator->isValid(FALSE) );

        $result = $validator->validate(array());
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be empty"),
                $result->getErrors()
            );

    }

    public function testValid ()
    {
        $validator = new \h2o\Validator\NotEmpty;

        $this->assertTrue( $validator->isValid("0") );
        $this->assertTrue( $validator->isValid("this is not empty") );
        $this->assertTrue( $validator->isValid( $this->getMock("NotEmpty") ) );
        $this->assertTrue( $validator->isValid( TRUE ) );
        $this->assertTrue( $validator->isValid( 20 ) );
    }

}

?>