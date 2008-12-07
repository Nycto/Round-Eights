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
class classes_validator_errorlist extends PHPUnit_Framework_TestCase
{

    public function testAddError ()
    {
        $result = new \cPHP\Validator\ErrorList;

        $this->assertSame( $result, $result->addError("This is an error message") );


        $errors = $result->getErrors();

        $this->assertThat( $errors, $this->isInstanceOf("cPHP\Ary") );

        $this->assertEquals(
                array("This is an error message"),
                $result->getErrors()->get()
            );

        $this->assertSame( $result, $result->addError("This is another error message") );

        $this->assertEquals(
                array("This is an error message", "This is another error message"),
                $result->getErrors()->get()
            );


        try {
            $result->addError("");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \cPHP\Exception\Argument $err ) {}
    }

    public function testAddErrors ()
    {
        $result = new \cPHP\Validator\ErrorList;

        $this->assertSame( $result, $result->addErrors("Error Message") );
        $this->assertEquals(
                array("Error Message"),
                $result->getErrors()->get()
            );

        $result->clearErrors();


        $this->assertSame(
                $result,
                $result->addErrors( array(("Error Message"), "more"), "Another", "", array("more", "then some") )
            );
        $this->assertEquals(
                array("Error Message", "more", "Another", "then some"),
                $result->getErrors()->get()
            );
    }

    public function testAddDuplicateError ()
    {
        $result = new \cPHP\Validator\ErrorList;

        $this->assertSame( $result, $result->addError("This is an error message") );
        $this->assertSame( $result, $result->addError("This is an error message") );

        $this->assertEquals(
                array("This is an error message"),
                $result->getErrors()->get()
            );
    }

    public function testClearErrors ()
    {
        $result = new \cPHP\Validator\ErrorList;

        $result->addError("This is an error message");

        $this->assertEquals(
                array("This is an error message"),
                $result->getErrors()->get()
            );

        $this->assertSame( $result, $result->clearErrors() );

        $this->assertEquals( array(), $result->getErrors()->get() );
    }

    public function testSetErrors ()
    {
        $result = new \cPHP\Validator\ErrorList;

        $result->addError("This is an error message");

        $this->assertEquals(
                array("This is an error message"),
                $result->getErrors()->get()
            );

        $this->assertSame( $result, $result->setError("This is a new error") );

        $this->assertEquals(
                array("This is a new error"),
                $result->getErrors()->get()
            );
    }

    public function testHasErrors ()
    {
        $result = new \cPHP\Validator\ErrorList;

        $this->assertFalse( $result->hasErrors() );

        $result->addError("Test Error");

        $this->assertTrue( $result->hasErrors() );

        $result->clearErrors();

        $this->assertFalse( $result->hasErrors() );
    }

    public function testGetFirstError ()
    {
        $result = new \cPHP\Validator\ErrorList;

        $this->assertNull( $result->getFirstError() );

        $result->addError("Test Error");

        $this->assertEquals("Test Error", $result->getFirstError());

        $result->addError("Another Error");

        $this->assertEquals("Test Error", $result->getFirstError());
    }

}

?>