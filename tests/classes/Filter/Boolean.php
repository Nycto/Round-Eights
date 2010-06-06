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
class classes_Filter_Boolean extends PHPUnit_Framework_TestCase
{

    public function testBoolean ()
    {
        $filter = new \r8\Filter\Boolean;
        $this->assertTrue( $filter->filter(TRUE) );
        $this->assertFalse( $filter->filter(FALSE) );
    }

    public function testInteger ()
    {
        $filter = new \r8\Filter\Boolean;
        $this->assertTrue( $filter->filter(1) );
        $this->assertTrue( $filter->filter(20) );
        $this->assertTrue( $filter->filter(-10) );
        $this->assertFalse( $filter->filter(0) );
    }

    public function testFloat ()
    {
        $filter = new \r8\Filter\Boolean;
        $this->assertTrue( $filter->filter(1.0) );
        $this->assertTrue( $filter->filter(.5) );
        $this->assertTrue( $filter->filter(20.5) );
        $this->assertTrue( $filter->filter(-10.5) );
        $this->assertFalse( $filter->filter(0.0) );
    }

    public function testNull ()
    {
        $filter = new \r8\Filter\Boolean;

        $this->assertFalse( $filter->filter(NULL) );
    }

    public function testString ()
    {
        $filter = new \r8\Filter\Boolean;

        $this->assertTrue( $filter->filter("t") );
        $this->assertTrue( $filter->filter("T") );
        $this->assertTrue( $filter->filter("true") );
        $this->assertTrue( $filter->filter("TRUE") );

        $this->assertTrue( $filter->filter("y") );
        $this->assertTrue( $filter->filter("Y") );
        $this->assertTrue( $filter->filter("yes") );
        $this->assertTrue( $filter->filter("YES") );

        $this->assertTrue( $filter->filter("on") );
        $this->assertTrue( $filter->filter("ON") );

        $this->assertTrue( $filter->filter("Some Other String") );

        $this->assertFalse( $filter->filter("f") );
        $this->assertFalse( $filter->filter("F") );
        $this->assertFalse( $filter->filter("false") );
        $this->assertFalse( $filter->filter("FALSE") );

        $this->assertFalse( $filter->filter("n") );
        $this->assertFalse( $filter->filter("N") );
        $this->assertFalse( $filter->filter("no") );
        $this->assertFalse( $filter->filter("NO") );

        $this->assertFalse( $filter->filter("off") );
        $this->assertFalse( $filter->filter("OFF") );

        $this->assertFalse( $filter->filter("") );
        $this->assertFalse( $filter->filter("  ") );
    }

    public function testArray ()
    {
        $filter = new \r8\Filter\Boolean;

        $this->assertTrue( $filter->filter( array(50) ) );
        $this->assertFalse( $filter->filter( array() ) );
    }

    public function testOther ()
    {
        $filter = new \r8\Filter\Boolean;

        $this->assertTrue( $filter->filter( $this->getMock("stub_spoof") ) );
    }

}

?>