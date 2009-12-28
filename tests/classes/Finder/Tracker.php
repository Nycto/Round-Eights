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
class classes_Finder_Tracker extends PHPUnit_Framework_TestCase
{

    public function testGetTested ()
    {
        $tracker = new \r8\Finder\Tracker;
        $this->assertSame( array(), $tracker->getTested() );

        $this->assertType( "boolean", $tracker->test( "/test/r8/", "/sub/path" ) );
        $this->assertSame(
            array( "/test/r8/sub/path" ),
            $tracker->getTested()
        );

        $this->assertType( "boolean", $tracker->test( "/test/r82/", "/sub/../path/" ) );
        $this->assertSame(
            array( "/test/r8/sub/path", "/test/r82/path" ),
            $tracker->getTested()
        );

        $this->assertType( "boolean", $tracker->test( "/test/r82/", "/sub/../path/" ) );
        $this->assertSame(
            array( "/test/r8/sub/path", "/test/r82/path" ),
            $tracker->getTested()
        );
    }

    public function testTest_Empty ()
    {
        $tracker = new \r8\Finder\Tracker;
        $this->assertFalse( $tracker->test( __DIR__, "" ) );
    }

    public function testTest_Directory ()
    {
        $tracker = new \r8\Finder\Tracker;
        $this->assertFalse( $tracker->test( __DIR__, basename( __DIR__ ) ) );
    }

    public function testTest_NotFound ()
    {
        $tracker = new \r8\Finder\Tracker;
        $this->assertFalse( $tracker->test( __DIR__, basename( "Not a file" ) ) );
    }

    public function testTest_Found ()
    {
        $tracker = new \r8\Finder\Tracker;
        $this->assertTrue( $tracker->test( __DIR__, basename( __FILE__ ) ) );
    }

    public function testGetSuccess ()
    {
        $tracker = new \r8\Finder\Tracker;
        $this->assertNull( $tracker->getSuccess() );

        $this->assertTrue( $tracker->test( __DIR__, basename( __FILE__ ) ) );
        $this->assertSame( __FILE__, $tracker->getSuccess() );
    }

}

?>