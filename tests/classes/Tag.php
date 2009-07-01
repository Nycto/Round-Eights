<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of raindropPHP.
 *
 * raindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * raindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with raindropPHP. If not, see <http://www.raindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * unit tests
 */
class classes_tag extends PHPUnit_Framework_TestCase
{

    public function testConstruct ()
    {
        $tag = new \h2o\Tag("A");

        $this->assertSame( "a", $tag->getTag() );
        $this->assertNull($tag->getContent());

        try {
            $tag = new \h2o\Tag("  ");
            $this->fail("An expected exception has not been thrown");
        }
        catch ( \h2o\Exception\Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }


        $tag = new \h2o\Tag("a", "a snip of content");
        $this->assertSame("a", $tag->getTag());
        $this->assertSame("a snip of content", $tag->getContent());


        $tag = new \h2o\Tag("a", "a snip of content", array("href" => "#"));
        $this->assertSame("a", $tag->getTag());
        $this->assertSame("a snip of content", $tag->getContent());
        $this->assertSame(array("href" => "#"), $tag->getAttrs());
    }

    public function testSetTag ()
    {
        $tag = new \h2o\Tag("a");

        $this->assertSame( $tag, $tag->setTag("div") );
        $this->assertSame( "div", $tag->getTag() );

        $this->assertSame( $tag, $tag->setTag("  Sp !@#$ a n") );
        $this->assertSame( "span", $tag->getTag() );

        try {
            $tag->setTag("");
            $this->fail("An expected exception has not been thrown");
        }
        catch ( \h2o\Exception\Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }
    }

    public function testSetContent ()
    {
        $tag = new \h2o\Tag("a");

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
        $tag = new \h2o\Tag("a");

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
        $tag = new \h2o\Tag("a");

        $this->assertNull( $tag->getContent() );

        $tag->setContent("This is a string") ;
        $this->assertEquals( "This is a string", $tag->getContent() );

        $this->assertSame( $tag, $tag->clearContent() );

        $this->assertNull( $tag->getContent() );
    }

    public function testHasContent ()
    {
        $tag = new \h2o\Tag("a");

        $this->assertFalse( $tag->hasContent() );

        $tag->setContent("This is a string") ;

        $this->assertTrue( $tag->hasContent() );

        $tag->clearContent();

        $this->assertFalse( $tag->hasContent() );
    }

    public function testIsEmpty ()
    {
        $tag = new \h2o\Tag("hr");
        $this->assertTrue( $tag->isEmpty() );

        $tag->setContent("Random");
        $this->assertFalse( $tag->isEmpty() );

        $tag->clearContent();
        $this->assertTrue( $tag->isEmpty() );

        $tag->setTag('a');
        $this->assertFalse( $tag->isEmpty() );
    }

    public function testSetEmpty ()
    {
        $tag = new \h2o\Tag("hr");
        $this->assertTrue( $tag->isEmpty() );

        $this->assertSame( $tag, $tag->setEmpty(FALSE) );
        $this->assertFalse( $tag->isEmpty() );

        $this->assertSame( $tag, $tag->setEmpty(TRUE) );
        $this->assertTrue( $tag->isEmpty() );


        $tag = new \h2o\Tag("a");
        $this->assertFalse( $tag->isEmpty() );

        $this->assertSame( $tag, $tag->setEmpty(TRUE) );
        $this->assertTrue( $tag->isEmpty() );

        $this->assertSame( $tag, $tag->setEmpty(FALSE) );
        $this->assertFalse( $tag->isEmpty() );
    }

    public function testClearEmpty ()
    {
        $tag = new \h2o\Tag("hr");
        $this->assertTrue( $tag->isEmpty() );

        $this->assertSame( $tag, $tag->setEmpty(FALSE) );
        $this->assertFalse( $tag->isEmpty() );

        $this->assertSame( $tag, $tag->clearEmpty() );
        $this->assertTrue( $tag->isEmpty() );


        $tag = new \h2o\Tag("a");
        $this->assertFalse( $tag->isEmpty() );

        $this->assertSame( $tag, $tag->setEmpty(TRUE) );
        $this->assertTrue( $tag->isEmpty() );

        $this->assertSame( $tag, $tag->clearEmpty() );
        $this->assertFalse( $tag->isEmpty() );
    }

    public function testNormalizeAttrName ()
    {
        $this->assertSame( "attr", \h2o\Tag::normalizeAttrName("Attr") );
        $this->assertSame( "attr", \h2o\Tag::normalizeAttrName(" a!@#t tr ") );

        try {
            \h2o\Tag::normalizeAttrName("  ");
            $this->fail("An expected exception has not been thrown");
        }
        catch ( \h2o\Exception\Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }
    }

    public function testGetAttrs ()
    {
        $tag = new \h2o\Tag("hr");

        $tag->setAttr("Rel", "nofollow");

        $this->assertSame(
                array("rel" => "nofollow"),
                $tag->getAttrs()
            );
    }

    public function testHasAttrs ()
    {
        $tag = new \h2o\Tag("hr");

        $this->assertFalse( $tag->hasAttrs() );

        $tag->setAttr("Rel", "nofollow");

        $this->assertTrue( $tag->hasAttrs() );
    }

    public function testSetAttr ()
    {
        $tag = new \h2o\Tag("hr");

        $this->assertSame( $tag, $tag->setAttr("Rel", "nofollow") );
        $this->assertSame( array("rel" => "nofollow"), $tag->getAttrs() );

        $this->assertSame( $tag, $tag->setAttr("class", "title") );
        $this->assertSame( array("rel" => "nofollow", "class" => "title"), $tag->getAttrs() );

        $this->assertSame( $tag, $tag->setAttr("class", "link") );
        $this->assertSame( array("rel" => "nofollow", "class" => "link"), $tag->getAttrs() );

        $this->assertSame( $tag, $tag->setAttr("rel", TRUE) );
        $this->assertSame( array("rel" => TRUE, "class" => "link"), $tag->getAttrs() );

        $this->assertSame( $tag, $tag->setAttr("class") );
        $this->assertSame( array("rel" => TRUE, "class" => TRUE), $tag->getAttrs() );

        $this->assertSame( $tag, $tag->setAttr("rel", FALSE) );
        $this->assertSame( array("rel" => FALSE, "class" => TRUE), $tag->getAttrs() );

        $this->assertSame( $tag, $tag->setAttr("class", null) );
        $this->assertSame( array("rel" => FALSE, "class" => null), $tag->getAttrs() );

        $this->assertSame( $tag, $tag->setAttr("rel", 50) );
        $this->assertSame( array("rel" => 50, "class" => null), $tag->getAttrs() );

        $this->assertSame( $tag, $tag->setAttr("class", array( 1.5, 50 ) ) );
        $this->assertSame( array("rel" => 50, "class" => 1.5), $tag->getAttrs() );

        try {
            $tag->setAttr("  ", "empty");
            $this->fail("An expected exception has not been thrown");
        }
        catch ( \h2o\Exception\Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }

        try {
            $tag->setAttr("  ");
            $this->fail("An expected exception has not been thrown");
        }
        catch ( \h2o\Exception\Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }
    }

    public function testAttrExists ()
    {
        $tag = new \h2o\Tag("hr");

        $this->assertFalse( $tag->attrExists("class") );

        $tag->setAttr("class", "title");

        $this->assertTrue( $tag->attrExists("class") );

        try {
            $tag->attrExists("  ");
            $this->fail("An expected exception has not been thrown");
        }
        catch ( \h2o\Exception\Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }
    }

    public function testUnsetAttr ()
    {
        $tag = new \h2o\Tag("hr");

        $this->assertSame( $tag, $tag->unsetAttr("rel") );

        $tag->setAttr("rel", "nofollow")
            ->setAttr("class", "title");

        $this->assertSame( array("rel" => "nofollow", "class" => "title"), $tag->getAttrs() );

        $this->assertSame( $tag, $tag->unsetAttr("rel") );

        $this->assertSame( array("class" => "title"), $tag->getAttrs() );

        try {
            $tag->unsetAttr("  ");
            $this->fail("An expected exception has not been thrown");
        }
        catch ( \h2o\Exception\Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }
    }

    public function testGetAttr ()
    {
        $tag = new \h2o\Tag("hr");

        $this->assertNull( $tag->getAttr("rel") );

        $tag->setAttr("rel", "nofollow");

        $this->assertSame( "nofollow", $tag->getAttr("rel") );

        try {
            $tag->getAttr("  ");
            $this->fail("An expected exception has not been thrown");
        }
        catch ( \h2o\Exception\Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }
    }

    public function testImportAttrs ()
    {
        $tag = new \h2o\Tag("hr");

        $this->assertSame( array(), $tag->getAttrs() );

        $this->assertSame(
                $tag,
                $tag->importAttrs( array( "rel" => "nofollow", "class" => "link" ) )
            );

        $this->assertSame( array( "rel" => "nofollow", "class" => "link" ), $tag->getAttrs() );

        $this->assertSame( $tag, $tag->importAttrs( array() ) );

        $this->assertSame( array( "rel" => "nofollow", "class" => "link" ), $tag->getAttrs() );

        $tag->clearAttrs();

        $this->assertSame(
                $tag,
                $tag->importAttrs( array( "rel" => "nofollow", "class" => "link" ) )
            );

        $this->assertSame( array( "rel" => "nofollow", "class" => "link" ), $tag->getAttrs() );

        try {
            $tag->importAttrs( array( "rel" => "nofollow", "  " => "link" ) );
            $this->fail("An expected exception has not been thrown");
        }
        catch ( \h2o\Exception\Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }
    }

    public function testClearAttrs ()
    {
        $tag = new \h2o\Tag("hr");

        $this->assertSame( array(), $tag->getAttrs() );

        $tag->importAttrs( array( "rel" => "nofollow", "class" => "link" ) );

        $this->assertSame( array( "rel" => "nofollow", "class" => "link" ), $tag->getAttrs() );

        $this->assertSame( $tag, $tag->clearAttrs() );

        $this->assertSame( array(), $tag->getAttrs() );

    }

    public function testSetAccessor ()
    {
        $tag = new \h2o\Tag("hr");

        $tag['Rel'] = "nofollow";
        $this->assertSame( array("rel" => "nofollow"), $tag->getAttrs() );

        $tag['class'] = "title";
        $this->assertSame( array("rel" => "nofollow", "class" => "title"), $tag->getAttrs() );

        $tag['class'] = "link";
        $this->assertSame( array("rel" => "nofollow", "class" => "link"), $tag->getAttrs() );

        try {
            $tag['  '] = "link";
            $this->fail("An expected exception has not been thrown");
        }
        catch ( \h2o\Exception\Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }

        try {
            $tag[] = "link";
            $this->fail("An expected exception has not been thrown");
        }
        catch ( \h2o\Exception\Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }
    }

    public function testExistsAccessor ()
    {
        $tag = new \h2o\Tag("hr");

        $this->assertFalse( isset($tag["class"]) );

        $tag->setAttr("class", "title");

        $this->assertTrue( isset($tag["class"]) );

        try {
            isset($tag["  "]);
            $this->fail("An expected exception has not been thrown");
        }
        catch ( \h2o\Exception\Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }
    }

    public function testUnsetAccessor ()
    {
        $tag = new \h2o\Tag("hr");

        unset( $tag['rel'] );

        $tag->setAttr("rel", "nofollow")
            ->setAttr("class", "title");

        $this->assertSame( array("rel" => "nofollow", "class" => "title"), $tag->getAttrs() );

        unset( $tag['rel'] );

        $this->assertSame( array("class" => "title"), $tag->getAttrs() );

        try {
            unset( $tag['  '] );
            $this->fail("An expected exception has not been thrown");
        }
        catch ( \h2o\Exception\Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }
    }

    public function testGetAcessor ()
    {
        $tag = new \h2o\Tag("hr");

        $this->assertNull( $tag["rel"] );

        $tag->setAttr("rel", "nofollow");

        $this->assertSame( "nofollow", $tag["rel"] );

        try {
            $tag["  "];
            $this->fail("An expected exception has not been thrown");
        }
        catch ( \h2o\Exception\Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }
    }

    public function testCallStatic ()
    {
        $tag = \h2o\Tag::div();
        $this->assertThat( $tag, $this->isInstanceOf("h2o\Tag") );
        $this->assertSame("div", $tag->getTag());
        $this->assertNull($tag->getContent());
        $this->assertSame(array(), $tag->getAttrs());

        $tag = \h2o\Tag::strong("words");
        $this->assertThat( $tag, $this->isInstanceOf("h2o\Tag") );
        $this->assertSame("strong", $tag->getTag());
        $this->assertSame("words", $tag->getContent());
        $this->assertSame(array(), $tag->getAttrs());

        $tag = \h2o\Tag::a("this is content", array("href" => "#"));
        $this->assertThat( $tag, $this->isInstanceOf("h2o\Tag") );
        $this->assertSame("a", $tag->getTag());
        $this->assertSame("this is content", $tag->getContent());
        $this->assertSame(array( "href" => "#" ), $tag->getAttrs());
    }

    public function testQuoteAttr ()
    {
        $this->assertSame( '"test"', \h2o\Tag::quoteAttr("test") );
        $this->assertSame( '"test &quot; quote"', \h2o\Tag::quoteAttr('test " quote') );
        $this->assertSame( '"test \' quote"', \h2o\Tag::quoteAttr("test ' quote") );
        $this->assertSame( '"test \' &quot; quotes"', \h2o\Tag::quoteAttr("test ' \" quotes") );
    }

    public function testGetAttrString ()
    {
        $tag = new \h2o\Tag("a");

        $this->assertSame( "", $tag->getAttrString() );

        $tag->setAttr("href", "?test=1&other=2");
        $this->assertSame( 'href="?test=1&amp;other=2"', $tag->getAttrString() );

        $tag->setAttr("selected");
        $this->assertSame( 'href="?test=1&amp;other=2" selected', $tag->getAttrString() );

        $tag->setAttr("Class", "link");
        $this->assertSame(
                'href="?test=1&amp;other=2" class="link" selected',
                $tag->getAttrString()
            );

        $tag->setAttr("TITLE", "has \" ' quotes");
        $this->assertSame(
                'href="?test=1&amp;other=2" class="link" title="has &quot; \' quotes" selected',
                $tag->getAttrString()
            );
    }

    public function testGetOpenTag()
    {
        $tag = new \h2o\Tag("a");

        $this->assertSame( "<a>", $tag->getOpenTag() );

        $tag->setTag("div");

        $this->assertSame( "<div>", $tag->getOpenTag() );

        $tag->setAttr("id", "myDiv");

        $this->assertSame( '<div id="myDiv">', $tag->getOpenTag() );

        $tag->setAttr("checked");

        $this->assertSame( '<div id="myDiv" checked>', $tag->getOpenTag() );

    }

    public function testGetCloseTag()
    {
        $tag = new \h2o\Tag("a");

        $this->assertSame( "</a>", $tag->getCloseTag() );

        $tag->setTag("div");

        $this->assertSame( "</div>", $tag->getCloseTag() );

        $tag->setAttr("id", "myDiv");

        $this->assertSame( "</div>", $tag->getCloseTag() );

        $tag->setAttr("checked");

        $this->assertSame( "</div>", $tag->getCloseTag() );

    }

    public function testGetEmptyTag()
    {
        $tag = new \h2o\Tag("a");

        $this->assertSame( "<a />", $tag->getEmptyTag() );

        $tag->setTag("div");

        $this->assertSame( "<div />", $tag->getEmptyTag() );

        $tag->setAttr("id", "myDiv");

        $this->assertSame( '<div id="myDiv" />', $tag->getEmptyTag() );

        $tag->setAttr("checked");

        $this->assertSame( '<div id="myDiv" checked />', $tag->getEmptyTag() );
    }

    public function testToString()
    {
        $tag = new \h2o\Tag("input");

        $this->assertSame( "<input />", $tag->__toString() );

        $tag->setTag("option");

        $this->assertSame( "<option></option>", $tag->__toString() );

        $tag->setAttr("value", "WA");

        $this->assertSame( '<option value="WA"></option>', $tag->__toString() );

        $tag->setAttr("selected");

        $this->assertSame( '<option value="WA" selected></option>', $tag->__toString() );

        $tag->setContent("Washington");

        $this->assertSame( '<option value="WA" selected>Washington</option>', $tag->__toString() );
    }
}

?>