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
class classes_validator_email extends PHPUnit_Framework_TestCase
{

    public function testValidAddresses ()
    {
        $validator = new \cPHP\Validator\Email;

        $this->assertTrue( $validator->isValid('abc@example.com') );
        $this->assertTrue( $validator->isValid('Abc@example.com') );
        $this->assertTrue( $validator->isValid('aBC@example.com') );
        $this->assertTrue( $validator->isValid('abc.123@example.com') );
        $this->assertTrue( $validator->isValid('abc.123@sub.example.com') );
        $this->assertTrue( $validator->isValid('abc.123@sub.sub.example.com') );
        $this->assertTrue( $validator->isValid('abc+123@example.com') );
        $this->assertTrue( $validator->isValid('1234567890@example.com') );
        $this->assertTrue( $validator->isValid('_______@example.com') );
        $this->assertTrue( $validator->isValid('abc+mailbox/department=shipping@example.com') );
        $this->assertTrue( $validator->isValid('!#$%&\'*+-/=?^_`.{|}~@example.com') );

        // Just under the length caps
        $this->assertTrue( $validator->isValid( str_repeat('a', 64) .'@example.com') );
        $this->assertTrue( $validator->isValid( "abc@". str_repeat('a', 251) .'.com') );
    }

    public function testInvalidAddresses ()
    {
        $validator = new \cPHP\Validator\Email;

        // Empty addresses
        $result = $validator->validate('');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address must not be empty"),
                $result->getErrors()->get()
            );

        $result = $validator->validate('    ');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address must not be empty"),
                $result->getErrors()->get()
            );

        // Missing an @ symbol
        $result = $validator->validate('Abc.example.com');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address must contain an 'at' (@) symbol"),
                $result->getErrors()->get()
            );

        // Multiple @ symbols
        $result = $validator->validate('A@b@c@example.com');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address must only contain one 'at' (@) symbol"),
                $result->getErrors()->get()
            );

        // Repated periods
        $result = $validator->validate('Abc..123@example.com');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address must not contain repeated periods"),
                $result->getErrors()->get()
            );

        // Spaces
        $result = $validator->validate('Abc. 123@ example.com');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address must not contain spaces"),
                $result->getErrors()->get()
            );

        // Line Breaks... \n
        $result = $validator->validate("Abc.\n123@example.com");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address must not contain line breaks"),
                $result->getErrors()->get()
            );

        // Line Breaks... \r
        $result = $validator->validate("Abc.\r123@example.com");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address must not contain line breaks"),
                $result->getErrors()->get()
            );

        // Tabs
        $result = $validator->validate("Abc123@\texample.com");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address must not contain tabs"),
                $result->getErrors()->get()
            );

        // invalid characters
        $result = $validator->validate('()[]\;:,<>@example.com');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address contains invalid characters"),
                $result->getErrors()->get()
            );

        // Period as the last character
        $result = $validator->validate('Abc@example.com.');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address must not end with a period"),
                $result->getErrors()->get()
            );

        // Period as the first character
        $result = $validator->validate('.Abc@example.com');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address must not start with a period"),
                $result->getErrors()->get()
            );

        // Period as the last character in the local part
        $result = $validator->validate('Abc.@example.com');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address is not valid"),
                $result->getErrors()->get()
            );

        // Period as the first character of the domain
        $result = $validator->validate('Abc@.example.com');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address is not valid"),
                $result->getErrors()->get()
            );

        // The local part is too long
        $result = $validator->validate( str_repeat('a', 65) .'@example.com');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address is too long"),
                $result->getErrors()->get()
            );

        // The domain is too long
        $result = $validator->validate( "abc@". str_repeat('a', 252) .'.com');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address is too long"),
                $result->getErrors()->get()
            );

        // Nothing before the @
        $result = $validator->validate('@example.com');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address is not valid"),
                $result->getErrors()->get()
            );

        // Nothing after the @
        $result = $validator->validate('abc@');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address is not valid"),
                $result->getErrors()->get()
            );

        // No top level domain
        $result = $validator->validate('abc@example');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address is not valid"),
                $result->getErrors()->get()
            );

    }

}

?>