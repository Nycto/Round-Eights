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
class classes_validator_regex extends PHPUnit_Framework_TestCase
{

    public function testConstruct ()
    {
        try {
            new \h2o\Validator\RegEx("");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }

        try {
            new \h2o\Validator\RegEx("   ");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }
    }

    public function testInvalidRegex ()
    {
        $regex = new \h2o\Validator\RegEx("1234");

        try {
            $regex->validate( "test" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( PHPUnit_Framework_Error $err ) {
            $this->assertSame(
                    "preg_match(): Delimiter must not be alphanumeric or backslash",
                    $err->getMessage()
                );
        }

    }

    public function testInvalidNonStrings()
    {
        $validator = new \h2o\Validator\RegEx("/[a-z]/");

        $result = $validator->validate($this->getMock("NoSpaces"));
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must be a string"),
                $result->getErrors()
            );
    }

    public function testTrue()
    {
        $validator = new \h2o\Validator\RegEx('/^1$/');
        $this->assertTrue( $validator->isValid(TRUE) );

        $validator = new \h2o\Validator\RegEx('/[a-z]/');
        $result = $validator->validate(TRUE);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must match the following regular expression: /[a-z]/"),
                $result->getErrors()
            );
    }

    public function testFalse()
    {
        $validator = new \h2o\Validator\RegEx('/^$/');
        $this->assertTrue( $validator->isValid(FALSE) );

        $validator = new \h2o\Validator\RegEx('/[a-z]/');
        $result = $validator->validate(FALSE);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must match the following regular expression: /[a-z]/"),
                $result->getErrors()
            );
    }

    public function testInteger()
    {
        $validator = new \h2o\Validator\RegEx('/^50$/');
        $this->assertTrue( $validator->isValid(50) );

        $validator = new \h2o\Validator\RegEx('/[a-z]/');
        $result = $validator->validate(50);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must match the following regular expression: /[a-z]/"),
                $result->getErrors()
            );
    }

    public function testZero()
    {
        $validator = new \h2o\Validator\RegEx('/^0$/');
        $this->assertTrue( $validator->isValid(0) );

        $validator = new \h2o\Validator\RegEx('/[a-z]/');
        $result = $validator->validate(0);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must match the following regular expression: /[a-z]/"),
                $result->getErrors()
            );
    }

    public function testNull()
    {
        $validator = new \h2o\Validator\RegEx('/^$/');
        $this->assertTrue( $validator->isValid(NULL) );

        $validator = new \h2o\Validator\RegEx('/[a-z]/');
        $result = $validator->validate(NULL);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must match the following regular expression: /[a-z]/"),
                $result->getErrors()
            );
    }

    public function testFloat()
    {
        $validator = new \h2o\Validator\RegEx('/^1\.1$/');
        $this->assertTrue( $validator->isValid(1.1) );

        $validator = new \h2o\Validator\RegEx('/[a-z]/');
        $result = $validator->validate(1.1);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must match the following regular expression: /[a-z]/"),
                $result->getErrors()
            );
    }

    public function _testString()
    {
        $validator = new \h2o\Validator\RegEx('/\.php$/');
        $this->assertTrue( $validator->isValid("file.php") );

        $validator = new \h2o\Validator\RegEx('/[0-9]/');
        $result = $validator->validate("This is a string");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must match the following regular expression: /[0-9]/"),
                $result->getErrors()
            );
    }

}

?>