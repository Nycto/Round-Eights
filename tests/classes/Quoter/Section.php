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
 * @author James Frasca <james@Raindropphp.com>
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
        $section = $this->getMock("h2o\Quoter\Section", array("isQuoted", "__toString"), array(null));

        $this->assertNull( $section->getContent() );

        $this->assertSame( $section, $section->setContent("new string") );

        $this->assertEquals( "new string", $section->getContent() );
    }

    public function testClearContent ()
    {
        $section = $this->getMock("h2o\Quoter\Section", array("isQuoted", "__toString"), array(null));

        $this->assertNull( $section->getContent() );

        $section->setContent("new string");

        $this->assertEquals( "new string", $section->getContent() );

        $this->assertSame( $section, $section->clearContent() );

        $this->assertNull( $section->getContent() );
    }

    public function testContentExists ()
    {
        $section = $this->getMock("h2o\Quoter\Section", array("isQuoted", "__toString"), array(null));

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
        $section = $this->getMock("h2o\Quoter\Section", array("isQuoted", "__toString"), array(null));

        $this->assertTrue( $section->isEmpty() );

        $section->setContent("");
        $this->assertTrue( $section->isEmpty() );

        $section->setContent("  ");
        $this->assertTrue( $section->isEmpty() );
        $this->assertFalse( $section->isEmpty( \h2o\ALLOW_SPACES ) );

        $section->setContent("Some piece of content");
        $this->assertFalse( $section->isEmpty() );

        $section->clearContent();
        $this->assertTrue( $section->isEmpty() );
    }

    public function testConstruct ()
    {
        $section = $this->getMock("h2o\Quoter\Section", array("isQuoted", "__toString"), array("data"));
        $this->assertSame( "data", $section->getContent() );
    }

}

?>