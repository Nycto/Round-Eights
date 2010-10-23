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
class classes_validator_in extends PHPUnit_Framework_TestCase
{

    public function testConstruct ()
    {
        $valid = new \r8\Validator\In(array("one", "two", "three"));

        $this->assertSame(
                array("one", "two", "three"),
                $valid->getList()
            );
    }

    public function testSetList ()
    {
        $valid = new \r8\Validator\In;

        $this->assertSame( $valid, $valid->setList(array("one", "two", "three")) );

        $this->assertSame(
                array("one", "two", "three"),
                $valid->getList()
            );
    }

    public function testSetList_unique ()
    {
        $valid = new \r8\Validator\In;
        $valid->setList(array("one", "two", "three", "Three", "two"));

        $this->assertSame(
                array("one", "two", "three", "Three"),
                $valid->getList()
            );
    }

    public function testAdd ()
    {
        $valid = new \r8\Validator\In;

        $this->assertSame( $valid, $valid->add("one") );

        $this->assertSame( array("one"), $valid->getList() );


        $this->assertSame( $valid, $valid->add("two") );

        $this->assertSame( array("one", "two"), $valid->getList() );
    }

    public function testExists ()
    {
        $valid = new \r8\Validator\In(array("one", "two", "three"));

        $this->assertTrue( $valid->exists("one") );
        $this->assertFalse( $valid->exists("four") );
    }

    public function testRemove ()
    {
        $valid = new \r8\Validator\In(array("one", "two", "three", "four"));

        $this->assertSame( $valid, $valid->remove("two") );
        $this->assertSame(
                array("one", "three", "four"),
                $valid->getList()
            );


        $this->assertSame( $valid, $valid->remove("five") );
        $this->assertSame(
                array("one", "three", "four"),
                $valid->getList()
            );


        $this->assertSame( $valid, $valid->remove("FOUR") );
        $this->assertSame(
                array("one", "three", "four"),
                $valid->getList()
            );
    }

    public function testValid ()
    {
        $valid = new \r8\Validator\In(array(1, 1.5, "two", TRUE, FALSE, NULL));

        $this->assertTrue( $valid->isValid(1) );
        $this->assertTrue( $valid->isValid("1") );
        $this->assertTrue( $valid->isValid(1.5) );
        $this->assertTrue( $valid->isValid("two") );
        $this->assertTrue( $valid->isValid(TRUE) );
        $this->assertTrue( $valid->isValid(FALSE) );
        $this->assertTrue( $valid->isValid(NULL) );
    }

    public function testInalid ()
    {
        $valid = new \r8\Validator\In(array(1, 1.5, "two", TRUE, FALSE, NULL));

        $result = $valid->validate("123");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Invalid option"),
                $result->getErrors()
            );
    }

}

