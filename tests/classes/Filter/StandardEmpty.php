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
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_filter_standardempty extends PHPUnit_Framework_TestCase
{

    public function testConstruct ()
    {
        $filter = new \r8\Filter\StandardEmpty();
        $this->assertEquals( 0, $filter->getFlags() );
        $this->assertEquals( NULL, $filter->getValue() );

        $filter = new \r8\Filter\StandardEmpty( "Empty Value", 5 );
        $this->assertEquals( 5, $filter->getFlags() );
        $this->assertEquals( "Empty Value", $filter->getValue() );
    }

    public function testSetValue ()
    {
        $filter = new \r8\Filter\StandardEmpty();

        $this->assertEquals( NULL, $filter->getValue() );

        $this->assertSame( $filter, $filter->setValue("New Empty") );

        $this->assertEquals( "New Empty", $filter->getValue() );
    }

    public function testSetFlags ()
    {
        $filter = new \r8\Filter\StandardEmpty();

        $this->assertEquals( 0, $filter->getFlags() );

        $this->assertSame( $filter, $filter->setFlags(5) );

        $this->assertEquals( 5, $filter->getFlags() );
    }

    public function testAddFlags ()
    {
        $filter = new \r8\Filter\StandardEmpty();

        $this->assertEquals( 0, $filter->getFlags() );

        $this->assertSame( $filter, $filter->addFlags(1) );

        $this->assertEquals( 1, $filter->getFlags() );

        $this->assertSame( $filter, $filter->addFlags(2) );

        $this->assertEquals( 3, $filter->getFlags() );

        $this->assertSame( $filter, $filter->addFlags(8) );

        $this->assertEquals( 11, $filter->getFlags() );
    }

    public function testInteger ()
    {
        $filter = new \r8\Filter\StandardEmpty;
        $this->assertSame( NULL, $filter->filter(0) );
        $this->assertSame( 1, $filter->filter(1) );
        $this->assertSame( 20, $filter->filter(20) );
        $this->assertSame( -10, $filter->filter(-10) );
    }

    public function testBoolean ()
    {
        $filter = new \r8\Filter\StandardEmpty;
        $this->assertSame( NULL, $filter->filter(FALSE) );
        $this->assertSame( TRUE, $filter->filter(TRUE) );
    }

    public function testFloat ()
    {
        $filter = new \r8\Filter\StandardEmpty;

        $this->assertSame( NULL, $filter->filter(0.0) );
        $this->assertSame( 20.25, $filter->filter(20.25) );
    }

    public function testNull ()
    {
        $filter = new \r8\Filter\StandardEmpty;

        $this->assertSame( NULL, $filter->filter(NULL) );
    }

    public function testString ()
    {
        $filter = new \r8\Filter\StandardEmpty;

        $this->assertSame( NULL, $filter->filter("") );
        $this->assertSame( NULL, $filter->filter("    ") );
        $this->assertSame( "Some String", $filter->filter("Some String") );
    }

    public function testArray ()
    {
        $filter = new \r8\Filter\StandardEmpty;

        $this->assertSame( array(50), $filter->filter( array(50) ) );
        $this->assertSame( NULL, $filter->filter( array() ) );
    }

    public function testObject ()
    {
        $filter = new \r8\Filter\StandardEmpty;

        $mock = $this->getMock("stub_random_obj");

        $this->assertSame( $mock, $filter->filter( $mock ) );

    }

}

?>