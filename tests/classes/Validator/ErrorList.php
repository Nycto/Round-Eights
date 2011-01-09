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
class classes_Validator_ErrorList extends PHPUnit_Framework_TestCase
{

    public function testAddError ()
    {
        $result = new \r8\Validator\ErrorList;

        $this->assertSame( $result, $result->addError("This is an error message") );


        $this->assertEquals(
            array("This is an error message"),
            $result->getErrors()
        );

        $this->assertSame( $result, $result->addError("This is another error message") );

        $this->assertEquals(
            array("This is an error message", "This is another error message"),
            $result->getErrors()
        );


        try {
            $result->addError("");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \r8\Exception\Argument $err ) {}
    }

    public function testAddErrors ()
    {
        $result = new \r8\Validator\ErrorList;

        $this->assertSame( $result, $result->addErrors("Error Message") );
        $this->assertEquals(
            array("Error Message"),
            $result->getErrors()
        );

        $result->clearErrors();


        $this->assertSame(
            $result,
            $result->addErrors(
                array(("Error Message"), "more"),
                "Another", "",
                array("more", "then some")
            )
        );
        $this->assertEquals(
            array("Error Message", "more", "Another", "then some"),
            $result->getErrors()
        );
    }

    public function testAddDuplicateError ()
    {
        $result = new \r8\Validator\ErrorList;

        $this->assertSame( $result, $result->addError("This is an error message") );
        $this->assertSame( $result, $result->addError("This is an error message") );

        $this->assertEquals(
            array("This is an error message"),
            $result->getErrors()
        );
    }

    public function testClearErrors ()
    {
        $result = new \r8\Validator\ErrorList;

        $result->addError("This is an error message");

        $this->assertEquals(
            array("This is an error message"),
            $result->getErrors()
        );

        $this->assertSame( $result, $result->clearErrors() );

        $this->assertEquals( array(), $result->getErrors() );
    }

    public function testSetErrors ()
    {
        $result = new \r8\Validator\ErrorList;

        $result->addError("This is an error message");

        $this->assertEquals(
            array("This is an error message"),
            $result->getErrors()
        );

        $this->assertSame( $result, $result->setError("This is a new error") );

        $this->assertEquals(
            array("This is a new error"),
            $result->getErrors()
        );
    }

    public function testHasErrors ()
    {
        $result = new \r8\Validator\ErrorList;

        $this->assertFalse( $result->hasErrors() );

        $result->addError("Test Error");

        $this->assertTrue( $result->hasErrors() );

        $result->clearErrors();

        $this->assertFalse( $result->hasErrors() );
    }

    public function testGetFirstError ()
    {
        $result = new \r8\Validator\ErrorList;

        $this->assertNull( $result->getFirstError() );

        $result->addError("Test Error");

        $this->assertEquals("Test Error", $result->getFirstError());

        $result->addError("Another Error");

        $this->assertEquals("Test Error", $result->getFirstError());
    }

    public function testIteration ()
    {
        $result = new \r8\Validator\ErrorList;
        \r8\Test\Constraint\Iterator::assertCount(0, $result);

        $result->addError("Test Error");
        \r8\Test\Constraint\Iterator::assert(
            array("Test Error"), $result
        );

        $result->addError("Error Two");
        \r8\Test\Constraint\Iterator::assert(
            array("Test Error", "Error Two"), $result
        );
    }

}
