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
class classes_validator_maxlength extends PHPUnit_Framework_TestCase
{

    public function testTrue()
    {
        $validator = new \h2o\Validator\MaxLength(10);
        $this->assertTrue( $validator->isValid(TRUE) );

        $validator = new \h2o\Validator\MaxLength(1);
        $this->assertTrue( $validator->isValid(TRUE) );

        $validator = new \h2o\Validator\MaxLength(0);
        $result = $validator->validate(TRUE);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be longer than 0 characters"),
                $result->getErrors()
            );
    }

    public function testFalse()
    {
        $validator = new \h2o\Validator\MaxLength(10);
        $this->assertTrue( $validator->isValid(FALSE) );

        $validator = new \h2o\Validator\MaxLength(1);
        $this->assertTrue( $validator->isValid(FALSE) );

        // When converted to a string, FALSE becomes ""
        $validator = new \h2o\Validator\MaxLength(0);
        $this->assertTrue( $validator->isValid(FALSE) );
    }

    public function testInteger()
    {
        $validator = new \h2o\Validator\MaxLength(10);
        $this->assertTrue( $validator->isValid(50) );

        $validator = new \h2o\Validator\MaxLength(2);
        $this->assertTrue( $validator->isValid(50) );

        $validator = new \h2o\Validator\MaxLength(1);
        $result = $validator->validate(50);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be longer than 1 character"),
                $result->getErrors()
            );
    }

    public function testZero()
    {
        $validator = new \h2o\Validator\MaxLength(10);
        $this->assertTrue( $validator->isValid(0) );

        $validator = new \h2o\Validator\MaxLength(1);
        $this->assertTrue( $validator->isValid(0) );

        $validator = new \h2o\Validator\MaxLength(0);
        $result = $validator->validate(0);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be longer than 0 characters"),
                $result->getErrors()
            );
    }

    public function testNull()
    {
        $validator = new \h2o\Validator\MaxLength(10);
        $this->assertTrue( $validator->isValid(NULL) );

        $validator = new \h2o\Validator\MaxLength(0);
        $this->assertTrue( $validator->isValid(NULL) );
    }

    public function testFloat()
    {
        $validator = new \h2o\Validator\MaxLength(10);
        $this->assertTrue( $validator->isValid(1.1) );

        $validator = new \h2o\Validator\MaxLength(3);
        $this->assertTrue( $validator->isValid(1.1) );

        $validator = new \h2o\Validator\MaxLength(2);
        $result = $validator->validate(1.1);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be longer than 2 characters"),
                $result->getErrors()
            );
    }

    public function testString()
    {
        $validator = new \h2o\Validator\MaxLength(7);
        $this->assertTrue( $validator->isValid("under") );

        $this->assertTrue( $validator->isValid("just at") );

        $result = $validator->validate("too long for it");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be longer than 7 characters"),
                $result->getErrors()
            );
    }

    public function testInvalidValues()
    {
        $validator = new \h2o\Validator\MaxLength(10);

        $result = $validator->validate($this->getMock("NoSpaces"));
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must be a string"),
                $result->getErrors()
            );
    }

}

?>