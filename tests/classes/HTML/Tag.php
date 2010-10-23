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
class classes_HTML_Tag extends PHPUnit_Framework_TestCase
{

    public function testConstruct ()
    {
        $tag = new \r8\HTML\Tag("A");

        $this->assertSame( "a", $tag->getTag() );
        $this->assertNull($tag->getContent());

        try {
            $tag = new \r8\HTML\Tag("  ");
            $this->fail("An expected exception has not been thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }


        $tag = new \r8\HTML\Tag("a", "a snip of content");
        $this->assertSame("a", $tag->getTag());
        $this->assertSame("a snip of content", $tag->getContent());


        $tag = new \r8\HTML\Tag("a", "a snip of content", array("href" => "#"));
        $this->assertSame("a", $tag->getTag());
        $this->assertSame("a snip of content", $tag->getContent());
        $this->assertSame(array("href" => "#"), $tag->getAttrs());
    }

    public function testSetTag ()
    {
        $tag = new \r8\HTML\Tag("a");

        $this->assertSame( $tag, $tag->setTag("div") );
        $this->assertSame( "div", $tag->getTag() );

        $this->assertSame( $tag, $tag->setTag("  Sp !@#$ a n") );
        $this->assertSame( "span", $tag->getTag() );

        try {
            $tag->setTag("");
            $this->fail("An expected exception has not been thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }
    }

    public function testIsEmpty ()
    {
        $tag = new \r8\HTML\Tag("hr");
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
        $tag = new \r8\HTML\Tag("hr");
        $this->assertTrue( $tag->isEmpty() );

        $this->assertSame( $tag, $tag->setEmpty(FALSE) );
        $this->assertFalse( $tag->isEmpty() );

        $this->assertSame( $tag, $tag->setEmpty(TRUE) );
        $this->assertTrue( $tag->isEmpty() );


        $tag = new \r8\HTML\Tag("a");
        $this->assertFalse( $tag->isEmpty() );

        $this->assertSame( $tag, $tag->setEmpty(TRUE) );
        $this->assertTrue( $tag->isEmpty() );

        $this->assertSame( $tag, $tag->setEmpty(FALSE) );
        $this->assertFalse( $tag->isEmpty() );
    }

    public function testClearEmpty ()
    {
        $tag = new \r8\HTML\Tag("hr");
        $this->assertTrue( $tag->isEmpty() );

        $this->assertSame( $tag, $tag->setEmpty(FALSE) );
        $this->assertFalse( $tag->isEmpty() );

        $this->assertSame( $tag, $tag->clearEmpty() );
        $this->assertTrue( $tag->isEmpty() );


        $tag = new \r8\HTML\Tag("a");
        $this->assertFalse( $tag->isEmpty() );

        $this->assertSame( $tag, $tag->setEmpty(TRUE) );
        $this->assertTrue( $tag->isEmpty() );

        $this->assertSame( $tag, $tag->clearEmpty() );
        $this->assertFalse( $tag->isEmpty() );
    }

    public function testNormalizeAttrName ()
    {
        $this->assertSame( "attr", \r8\HTML\Tag::normalizeAttrName("Attr") );
        $this->assertSame( "attr", \r8\HTML\Tag::normalizeAttrName(" a!@#t tr ") );

        try {
            \r8\HTML\Tag::normalizeAttrName("  ");
            $this->fail("An expected exception has not been thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }
    }

    public function testGetAttrs ()
    {
        $tag = new \r8\HTML\Tag("hr");

        $tag->setAttr("Rel", "nofollow");

        $this->assertSame(
                array("rel" => "nofollow"),
                $tag->getAttrs()
            );
    }

    public function testHasAttrs ()
    {
        $tag = new \r8\HTML\Tag("hr");

        $this->assertFalse( $tag->hasAttrs() );

        $tag->setAttr("Rel", "nofollow");

        $this->assertTrue( $tag->hasAttrs() );
    }

    public function testSetAttr ()
    {
        $tag = new \r8\HTML\Tag("hr");

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
        catch ( \r8\Exception\Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }

        try {
            $tag->setAttr("  ");
            $this->fail("An expected exception has not been thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }
    }

    public function testAttrExists ()
    {
        $tag = new \r8\HTML\Tag("hr");

        $this->assertFalse( $tag->attrExists("class") );

        $tag->setAttr("class", "title");

        $this->assertTrue( $tag->attrExists("class") );

        try {
            $tag->attrExists("  ");
            $this->fail("An expected exception has not been thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }
    }

    public function testUnsetAttr ()
    {
        $tag = new \r8\HTML\Tag("hr");

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
        catch ( \r8\Exception\Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }
    }

    public function testGetAttr ()
    {
        $tag = new \r8\HTML\Tag("hr");

        $this->assertNull( $tag->getAttr("rel") );

        $tag->setAttr("rel", "nofollow");

        $this->assertSame( "nofollow", $tag->getAttr("rel") );

        try {
            $tag->getAttr("  ");
            $this->fail("An expected exception has not been thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }
    }

    public function testImportAttrs ()
    {
        $tag = new \r8\HTML\Tag("hr");

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
        catch ( \r8\Exception\Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }
    }

    public function testClearAttrs ()
    {
        $tag = new \r8\HTML\Tag("hr");

        $this->assertSame( array(), $tag->getAttrs() );

        $tag->importAttrs( array( "rel" => "nofollow", "class" => "link" ) );

        $this->assertSame( array( "rel" => "nofollow", "class" => "link" ), $tag->getAttrs() );

        $this->assertSame( $tag, $tag->clearAttrs() );

        $this->assertSame( array(), $tag->getAttrs() );

    }

    public function testSetAccessor ()
    {
        $tag = new \r8\HTML\Tag("hr");

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
        catch ( \r8\Exception\Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }

        try {
            $tag[] = "link";
            $this->fail("An expected exception has not been thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }
    }

    public function testExistsAccessor ()
    {
        $tag = new \r8\HTML\Tag("hr");

        $this->assertFalse( isset($tag["class"]) );

        $tag->setAttr("class", "title");

        $this->assertTrue( isset($tag["class"]) );

        try {
            isset($tag["  "]);
            $this->fail("An expected exception has not been thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }
    }

    public function testUnsetAccessor ()
    {
        $tag = new \r8\HTML\Tag("hr");

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
        catch ( \r8\Exception\Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }
    }

    public function testGetAcessor ()
    {
        $tag = new \r8\HTML\Tag("hr");

        $this->assertNull( $tag["rel"] );

        $tag->setAttr("rel", "nofollow");

        $this->assertSame( "nofollow", $tag["rel"] );

        try {
            $tag["  "];
            $this->fail("An expected exception has not been thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }
    }

    public function testCallStatic ()
    {
        $tag = \r8\HTML\Tag::div();
        $this->assertThat( $tag, $this->isInstanceOf("r8\HTML\Tag") );
        $this->assertSame("div", $tag->getTag());
        $this->assertNull($tag->getContent());
        $this->assertSame(array(), $tag->getAttrs());

        $tag = \r8\HTML\Tag::strong("words");
        $this->assertThat( $tag, $this->isInstanceOf("r8\HTML\Tag") );
        $this->assertSame("strong", $tag->getTag());
        $this->assertSame("words", $tag->getContent());
        $this->assertSame(array(), $tag->getAttrs());

        $tag = \r8\HTML\Tag::a("this is content", array("href" => "#"));
        $this->assertThat( $tag, $this->isInstanceOf("r8\HTML\Tag") );
        $this->assertSame("a", $tag->getTag());
        $this->assertSame("this is content", $tag->getContent());
        $this->assertSame(array( "href" => "#" ), $tag->getAttrs());
    }

    public function testQuoteAttr ()
    {
        $this->assertSame( '"test"', \r8\HTML\Tag::quoteAttr("test") );
        $this->assertSame( '"test &quot; quote"', \r8\HTML\Tag::quoteAttr('test " quote') );
        $this->assertSame( '"test \' quote"', \r8\HTML\Tag::quoteAttr("test ' quote") );
        $this->assertSame( '"test \' &quot; quotes"', \r8\HTML\Tag::quoteAttr("test ' \" quotes") );
    }

    public function testGetAttrString ()
    {
        $tag = new \r8\HTML\Tag("a");

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
        $tag = new \r8\HTML\Tag("a");

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
        $tag = new \r8\HTML\Tag("a");

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
        $tag = new \r8\HTML\Tag("a");

        $this->assertSame( "<a />", $tag->getEmptyTag() );

        $tag->setTag("div");

        $this->assertSame( "<div />", $tag->getEmptyTag() );

        $tag->setAttr("id", "myDiv");

        $this->assertSame( '<div id="myDiv" />', $tag->getEmptyTag() );

        $tag->setAttr("checked");

        $this->assertSame( '<div id="myDiv" checked />', $tag->getEmptyTag() );
    }

    public function testRender ()
    {
        $tag = new \r8\HTML\Tag("input");

        $this->assertSame( "<input />", $tag->render() );

        $tag->setTag("option");

        $this->assertSame( "<option></option>", $tag->render() );

        $tag->setAttr("value", "WA");

        $this->assertSame( '<option value="WA"></option>', $tag->render() );

        $tag->setAttr("selected");

        $this->assertSame( '<option value="WA" selected></option>', $tag->render() );

        $tag->setContent("Washington");

        $this->assertSame( '<option value="WA" selected>Washington</option>', $tag->render() );
    }

}

