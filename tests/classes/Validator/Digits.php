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
class classes_validator_digits extends PHPUnit_Framework_TestCase
{

    public function testNonStrings ()
    {
        $validator = new \h2o\Validator\Digits;

        $result = $validator->validate($this->getMock("stubObject"));
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must be a string"),
                $result->getErrors()
            );

        $result = $validator->validate(10);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must be a string"),
                $result->getErrors()
            );

        $result = $validator->validate(1.5);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must be a string"),
                $result->getErrors()
            );

        $result = $validator->validate(NULL);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must be a string"),
                $result->getErrors()
            );

        $result = $validator->validate(TRUE);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must be a string"),
                $result->getErrors()
            );

        $result = $validator->validate(FALSE);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must be a string"),
                $result->getErrors()
            );
    }

    public function testValid ()
    {
        $validator = new \h2o\Validator\Digits;
        $this->assertTrue( $validator->validate('1234567890')->isValid() );
    }

    public function testInvalid ()
    {
        $validator = new \h2o\Validator\Digits;

        $result = $validator->validate('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must only contain digits"),
                $result->getErrors()
            );

        $result = $validator->validate('abcdefghijklmnopqrstuvwxyz');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must only contain digits"),
                $result->getErrors()
            );

        // Get a list of extended range ascii characters
        $string = implode( "", array_map("chr", range(127, 255) ) );
        $result = $validator->validate($string);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must only contain digits"),
                $result->getErrors()
            );

        $result = $validator->validate('!"#$%&\'()*+,-/:;<=>?@[\]^`{|}~');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must only contain digits"),
                $result->getErrors()
            );
    }

}

?>