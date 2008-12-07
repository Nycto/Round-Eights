<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of commonPHP.
 *
 * commonPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * commonPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with commonPHP. If not, see <http://www.commonphp.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@commonphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_validator_nospaces extends PHPUnit_Framework_TestCase
{

    public function testValidNonStrings()
    {
        $validator = new \cPHP\Validator\NoSpaces;

        $this->assertTrue( $validator->isValid(TRUE) );
        $this->assertTrue( $validator->isValid(FALSE) );
        $this->assertTrue( $validator->isValid(50) );
        $this->assertTrue( $validator->isValid(0) );
        $this->assertTrue( $validator->isValid(1.5) );
        $this->assertTrue( $validator->isValid(NULL) );

    }

    public function testValidStrings()
    {
        $validator = new \cPHP\Validator\NoSpaces;

        $this->assertTrue( $validator->isValid("NoSpaces") );
        $this->assertTrue( $validator->isValid("!@$^$@$#{}:<>?") );
        $this->assertTrue( $validator->isValid("") );
    }

    public function testInvalidNonStrings()
    {
        $validator = new \cPHP\Validator\NoSpaces;

        $result = $validator->validate($this->getMock("NoSpaces"));
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must be a string"),
                $result->getErrors()->get()
            );
    }

    public function testInvalidStrings()
    {
        $validator = new \cPHP\Validator\NoSpaces;

        $result = $validator->validate("   ");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not contain any spaces"),
                $result->getErrors()->get()
            );

        $result = $validator->validate("String With Spaces");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not contain any spaces"),
                $result->getErrors()->get()
            );

        $result = $validator->validate("\tTabbed");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not contain any tabs"),
                $result->getErrors()->get()
            );

        $result = $validator->validate("lineBreak\n");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not contain any new lines"),
                $result->getErrors()->get()
            );

        $result = $validator->validate("return\r");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not contain any new lines"),
                $result->getErrors()->get()
            );

    }

}

?>