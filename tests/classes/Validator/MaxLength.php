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
class classes_validator_maxlength extends PHPUnit_Framework_TestCase
{

    public function testTrue()
    {
        $validator = new \r8\Validator\MaxLength(10);
        $this->assertTrue( $validator->isValid(TRUE) );

        $validator = new \r8\Validator\MaxLength(1);
        $this->assertTrue( $validator->isValid(TRUE) );

        $validator = new \r8\Validator\MaxLength(0);
        $result = $validator->validate(TRUE);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be longer than 0 characters"),
                $result->getErrors()
            );
    }

    public function testFalse()
    {
        $validator = new \r8\Validator\MaxLength(10);
        $this->assertTrue( $validator->isValid(FALSE) );

        $validator = new \r8\Validator\MaxLength(1);
        $this->assertTrue( $validator->isValid(FALSE) );

        // When converted to a string, FALSE becomes ""
        $validator = new \r8\Validator\MaxLength(0);
        $this->assertTrue( $validator->isValid(FALSE) );
    }

    public function testInteger()
    {
        $validator = new \r8\Validator\MaxLength(10);
        $this->assertTrue( $validator->isValid(50) );

        $validator = new \r8\Validator\MaxLength(2);
        $this->assertTrue( $validator->isValid(50) );

        $validator = new \r8\Validator\MaxLength(1);
        $result = $validator->validate(50);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be longer than 1 character"),
                $result->getErrors()
            );
    }

    public function testZero()
    {
        $validator = new \r8\Validator\MaxLength(10);
        $this->assertTrue( $validator->isValid(0) );

        $validator = new \r8\Validator\MaxLength(1);
        $this->assertTrue( $validator->isValid(0) );

        $validator = new \r8\Validator\MaxLength(0);
        $result = $validator->validate(0);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be longer than 0 characters"),
                $result->getErrors()
            );
    }

    public function testNull()
    {
        $validator = new \r8\Validator\MaxLength(10);
        $this->assertTrue( $validator->isValid(NULL) );

        $validator = new \r8\Validator\MaxLength(0);
        $this->assertTrue( $validator->isValid(NULL) );
    }

    public function testFloat()
    {
        $validator = new \r8\Validator\MaxLength(10);
        $this->assertTrue( $validator->isValid(1.1) );

        $validator = new \r8\Validator\MaxLength(3);
        $this->assertTrue( $validator->isValid(1.1) );

        $validator = new \r8\Validator\MaxLength(2);
        $result = $validator->validate(1.1);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be longer than 2 characters"),
                $result->getErrors()
            );
    }

    public function testString()
    {
        $validator = new \r8\Validator\MaxLength(7);
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
        $validator = new \r8\Validator\MaxLength(10);

        $result = $validator->validate($this->getMock("NoSpaces"));
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must be a string"),
                $result->getErrors()
            );
    }

}

