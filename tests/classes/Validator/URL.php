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
class classes_validator_url extends PHPUnit_Framework_TestCase
{

    public function testNonStrings ()
    {
        $validator = new \cPHP\Validator\URL;

        $result = $validator->validate(5);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("URL must be a string"),
                $result->getErrors()->get()
            );

        $result = $validator->validate(5.5);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("URL must be a string"),
                $result->getErrors()->get()
            );

        $result = $validator->validate(null);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("URL must be a string"),
                $result->getErrors()->get()
            );

        $result = $validator->validate(TRUE);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("URL must be a string"),
                $result->getErrors()->get()
            );
    }

    public function testInvalid ()
    {
        $validator = new \cPHP\Validator\URL;

        // Spaces
        $result = $validator->validate("http:// www.example.com");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("URL must not contain spaces"),
                $result->getErrors()->get()
            );

        // Tab
        $result = $validator->validate("http://\twww.example.com");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("URL must not contain tabs"),
                $result->getErrors()->get()
            );

        // Line break... \n
        $result = $validator->validate("http://\nwww.example.com");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("URL must not contain line breaks"),
                $result->getErrors()->get()
            );

        // Line break... \r
        $result = $validator->validate("http://\rwww.example.com");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("URL must not contain line breaks"),
                $result->getErrors()->get()
            );

        // invalid characters
        $result = $validator->validate('http://'. chr(15) .'example.com');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("URL contains invalid characters"),
                $result->getErrors()->get()
            );


        $result = $validator->validate('example');
        $this->assertFalse( $result->isValid() );
        $this->assertSame(
                array("URL is not valid"),
                $result->getErrors()->get()
            );

        $result = $validator->validate('example.com');
        $this->assertFalse( $result->isValid() );
        $this->assertSame(
                array("URL is not valid"),
                $result->getErrors()->get()
            );

        $result = $validator->validate('www.example.com/test.php');
        $this->assertFalse( $result->isValid() );
        $this->assertSame(
                array("URL is not valid"),
                $result->getErrors()->get()
            );

        $result = $validator->validate('/dir/test.php');
        $this->assertFalse( $result->isValid() );
        $this->assertSame(
                array("URL is not valid"),
                $result->getErrors()->get()
            );

    }

    public function testValid ()
    {
        $validator = new \cPHP\Validator\URL;

        $this->assertTrue(
                $validator->validate('http://example.com')->isValid()
            );

        $this->assertTrue(
                $validator->validate('https://www.example.com')->isValid()
            );

        $this->assertTrue(
                $validator->validate('ftp://www.example.com/')->isValid()
            );

        $this->assertTrue(
                $validator->validate('ftp://www.example.com/index.php')->isValid()
            );

        $this->assertTrue(
                $validator->validate('ftp://www.example.com/dir')->isValid()
            );

        $this->assertTrue(
                $validator->validate('ftp://www.example.com/dir/index.php')->isValid()
            );

        $this->assertTrue(
                $validator->validate('ftp://www.example.com?test=1')->isValid()
            );

        $this->assertTrue(
                $validator->validate('ftp://www.example.com?test=1#fragment')->isValid()
            );

        $this->assertTrue(
                $validator->validate('foo://example.com:8042/over/there?name=ferret#nose')->isValid()
            );

    }

    public function testValid_Relative ()
    {
        $validator = new \cPHP\Validator\URL(
                \cPHP\Validator\URL::ALLOW_RELATIVE
            );

        $this->assertTrue(
                $validator->validate('/over/there?name=ferret#nose')->isValid()
            );

        $this->assertTrue(
                $validator->validate('foo://example.com:8042/over/there?name=ferret#nose')->isValid()
            );
    }

}

?>