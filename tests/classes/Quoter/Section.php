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
class classes_quoter_section extends PHPUnit_Framework_TestCase
{

    public function testSetContent ()
    {
        $section = $this->getMock("cPHP\Quoter\Section", array("isQuoted", "__toString"), array(0, null));

        $this->assertNull( $section->getContent() );

        $this->assertSame( $section, $section->setContent("new string") );

        $this->assertEquals( "new string", $section->getContent() );
    }

    public function testClearContent ()
    {
        $section = $this->getMock("cPHP\Quoter\Section", array("isQuoted", "__toString"), array(0, null));

        $this->assertNull( $section->getContent() );

        $section->setContent("new string");

        $this->assertEquals( "new string", $section->getContent() );

        $this->assertSame( $section, $section->clearContent() );

        $this->assertNull( $section->getContent() );
    }

    public function testContentExists ()
    {
        $section = $this->getMock("cPHP\Quoter\Section", array("isQuoted", "__toString"), array(0, null));

        $this->assertFalse( $section->contentExists() );

        $section->setContent("new string");

        $this->assertTrue( $section->contentExists() );

        $section->clearContent();

        $this->assertFalse( $section->contentExists() );

        $section->setContent("");

        $this->assertTrue( $section->contentExists() );
    }

    public function testIsEmpty ()
    {
        $section = $this->getMock("cPHP\Quoter\Section", array("isQuoted", "__toString"), array(0, null));

        $this->assertTrue( $section->isEmpty() );

        $section->setContent("");
        $this->assertTrue( $section->isEmpty() );

        $section->setContent("  ");
        $this->assertTrue( $section->isEmpty() );
        $this->assertFalse( $section->isEmpty( \cPHP\ALLOW_SPACES ) );

        $section->setContent("Some piece of content");
        $this->assertFalse( $section->isEmpty() );

        $section->clearContent();
        $this->assertTrue( $section->isEmpty() );
    }

    public function testConstruct ()
    {
        $section = $this->getMock("cPHP\Quoter\Section", array("isQuoted", "__toString"), array(10, "data"));
        $this->assertSame( 10, $section->getOffset() );
        $this->assertSame( "data", $section->getContent() );

        try {
            $section = $this->getMock("cPHP\Quoter\Section", array("isQuoted", "__toString"), array(-5, "data"));
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame( "Must not be less than zero", $err->getMessage() );
        }
    }

}

?>