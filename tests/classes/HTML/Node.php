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
class classes_HTML_Node extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a test HTML\Node instance
     *
     * @return \r8\HTML\Node
     */
    public function getTestNode ( $content = null )
    {
        return $this->getMock(
            '\r8\HTML\Node',
            array('render'),
            array( $content )
        );
    }

    public function testSetContent ()
    {
        $tag = $this->getTestNode();

        $this->assertNull( $tag->getContent() );

        $this->assertSame( $tag, $tag->setContent("This is a string") );
        $this->assertEquals( "This is a string", $tag->getContent() );

        $this->assertSame( $tag, $tag->setContent("0") );
        $this->assertEquals( "0", $tag->getContent() );

        $this->assertSame( $tag, $tag->setContent("") );
        $this->assertNull( $tag->getContent() );

        $this->assertSame( $tag, $tag->setContent("   ") );
        $this->assertEquals( "   ", $tag->getContent() );

        $this->assertSame( $tag, $tag->setContent(FALSE) );
        $this->assertNull( $tag->getContent() );

        $this->assertSame( $tag, $tag->setContent(TRUE) );
        $this->assertSame( "1", $tag->getContent() );

        $this->assertSame( $tag, $tag->setContent(1) );
        $this->assertSame( "1", $tag->getContent() );

        $mock = $this->getMock("stub_getContent", array("__toString"));
        $mock->expects( $this->once() )
            ->method("__toString")
            ->will( $this->returnValue("stringified"));

        $this->assertSame( $tag, $tag->setContent($mock) );
        $this->assertSame( "stringified", $tag->getContent() );
    }

    public function testAppendContent ()
    {
        $tag = $this->getTestNode();

        $this->assertNull( $tag->getContent() );

        $this->assertSame( $tag, $tag->appendContent("string") );
        $this->assertEquals( "string", $tag->getContent() );

        $this->assertSame( $tag, $tag->appendContent("0") );
        $this->assertEquals( "string0", $tag->getContent() );

        $this->assertSame( $tag, $tag->appendContent("") );
        $this->assertEquals( "string0", $tag->getContent() );

        $this->assertSame( $tag, $tag->appendContent("  ") );
        $this->assertEquals( "string0  ", $tag->getContent() );

        $this->assertSame( $tag, $tag->appendContent(FALSE) );
        $this->assertEquals( "string0  ", $tag->getContent() );

        $this->assertSame( $tag, $tag->appendContent(TRUE) );
        $this->assertEquals( "string0  1", $tag->getContent() );

        $this->assertSame( $tag, $tag->appendContent(1) );
        $this->assertEquals( "string0  11", $tag->getContent() );

        $mock = $this->getMock("stub_getContent", array("__toString"));
        $mock->expects( $this->once() )
            ->method("__toString")
            ->will( $this->returnValue("stringified"));

        $this->assertSame( $tag, $tag->appendContent($mock) );
        $this->assertEquals( "string0  11stringified", $tag->getContent() );
    }

    public function testClearContent ()
    {
        $tag = $this->getTestNode();

        $this->assertNull( $tag->getContent() );

        $tag->setContent("This is a string") ;
        $this->assertEquals( "This is a string", $tag->getContent() );

        $this->assertSame( $tag, $tag->clearContent() );

        $this->assertNull( $tag->getContent() );
    }

    public function testHasContent ()
    {
        $tag = $this->getTestNode();

        $this->assertFalse( $tag->hasContent() );

        $tag->setContent("This is a string") ;

        $this->assertTrue( $tag->hasContent() );

        $tag->clearContent();

        $this->assertFalse( $tag->hasContent() );
    }

    public function testToString()
    {
        $tag = $this->getTestNode();

        $tag->expects( $this->once() )
            ->method( "render" )
            ->will( $this->returnValue("Content") );

        $this->assertSame( "Content", $tag->__toString() );
    }

}

?>