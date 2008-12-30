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
class classes_validator_method extends PHPUnit_Framework_TestCase
{

    public function testNonStrings ()
    {
        $validator = new \cPHP\Validator\Method;

        $result = $validator->validate($this->getMock("stubObject"));
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must be a string"),
                $result->getErrors()->get()
            );

        $result = $validator->validate(10);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must be a string"),
                $result->getErrors()->get()
            );

        $result = $validator->validate(1.5);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must be a string"),
                $result->getErrors()->get()
            );

        $result = $validator->validate(NULL);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must be a string"),
                $result->getErrors()->get()
            );

        $result = $validator->validate(TRUE);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must be a string"),
                $result->getErrors()->get()
            );

        $result = $validator->validate(FALSE);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must be a string"),
                $result->getErrors()->get()
            );
    }

    public function testValid ()
    {
        $validator = new \cPHP\Validator\Method;

        $this->assertTrue( $validator->validate("string")->isValid() );
        $this->assertTrue( $validator->validate("test1234")->isValid() );

        // Get a list of extended range ascii characters
        $string = implode( "", array_map("chr", range(127, 255) ) );
        $this->assertTrue( $validator->validate($string)->isValid() );
    }

    public function testInvalid ()
    {
        $validator = new \cPHP\Validator\Method;

        $result = $validator->validate("123");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must be a valid PHP function name"),
                $result->getErrors()->get()
            );

        $result = $validator->validate("test.period");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must be a valid PHP function name"),
                $result->getErrors()->get()
            );
    }

}

?>