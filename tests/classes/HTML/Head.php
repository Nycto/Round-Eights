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
class classes_HTML_Head extends PHPUnit_Framework_TestCase
{

    public function testDocType ()
    {
        $head = new \r8\HTML\Head;

        $this->assertEquals(
            \r8\HTML\DocType::NONE(),
            $head->getDocType()
        );

        $doctype = \r8\HTML\DocType::HTML5();
        $this->assertSame( $head, $head->setDocType($doctype) );
        $this->assertSame( $doctype, $head->getDocType() );
    }

    public function testTitle ()
    {
        $head = new \r8\HTML\Head;
        $this->assertNull( $head->getTitle() );

        $this->assertSame( $head, $head->setTitle("Page Title") );
        $this->assertSame( "Page Title", $head->getTitle() );

        $this->assertSame( $head, $head->appendTitle(" - SubTitle") );
        $this->assertSame( "Page Title - SubTitle", $head->getTitle() );
    }

    public function testMetaTags ()
    {
        $head = new \r8\HTML\Head;
        $this->assertSame( array(), $head->getMetaTags() );

        $meta1 = new \r8\HTML\MetaTag("name", "content");
        $this->assertSame( $head, $head->addMetaTag($meta1) );
        $this->assertSame( array($meta1), $head->getMetaTags() );

        $meta2 = new \r8\HTML\MetaTag("name", "content");
        $this->assertSame( $head, $head->addMetaTag($meta2) );
        $this->assertSame( array($meta1, $meta2), $head->getMetaTags() );

        $this->assertSame( $head, $head->clearMetaTags() );
        $this->assertSame( array(), $head->getMetaTags() );
    }

    public function testJavascript ()
    {
        $head = new \r8\HTML\Head;
        $this->assertSame( array(), $head->getJavascript() );

        $js1 = new \r8\HTML\Javascript("test.js");
        $this->assertSame( $head, $head->addJavascript($js1) );
        $this->assertSame( array($js1), $head->getJavascript() );

        $js2 = new \r8\HTML\Javascript("example.js");
        $this->assertSame( $head, $head->addJavascript($js2) );
        $this->assertSame( array($js1, $js2), $head->getJavascript() );

        $this->assertSame( $head, $head->clearJavascript() );
        $this->assertSame( array(), $head->getJavascript() );
    }

    public function testCSS ()
    {
        $head = new \r8\HTML\Head;
        $this->assertSame( array(), $head->getCSS() );

        $css1 = new \r8\HTML\CSS("test.css");
        $this->assertSame( $head, $head->addCSS($css1) );
        $this->assertSame( array($css1), $head->getCSS() );

        $css2 = new \r8\HTML\CSS("example.css");
        $this->assertSame( $head, $head->addCSS($css2) );
        $this->assertSame( array($css1, $css2), $head->getCSS() );

        $this->assertSame( $head, $head->clearCSS() );
        $this->assertSame( array(), $head->getCSS() );
    }

    public function testGetTag_Full ()
    {
        $head = new \r8\HTML\Head;
        $head->setDocType( \r8\HTML\DocType::HTML5() );
        $head->setTitle("Test");
        $head->addCSS( new \r8\HTML\CSS("test.css") );
        $head->addCSS( new \r8\HTML\CSS("example.css") );
        $head->addJavascript( new \r8\HTML\Javascript("test.js") );
        $head->addJavascript( new \r8\HTML\Javascript("example.js") );
        $head->addMetaTag( new \r8\HTML\MetaTag("name", "content") );
        $head->addMetaTag( new \r8\HTML\MetaTag("robots", "index, follow") );

        $tag = $head->getTag();
        $this->assertThat( $tag, $this->isInstanceOf('\r8\HTML\Tag') );
        $this->assertSame( 'head', $tag->getTag() );
        $this->assertSame(
            '<title>Test</title>' ."\n"
            .'<meta name="name" content="content" />' ."\n"
            .'<meta name="robots" content="index, follow" />' ."\n"
            .'<link rel="stylesheet" href="test.css" type="text/css" media="all" />' ."\n"
            .'<link rel="stylesheet" href="example.css" type="text/css" media="all" />' ."\n"
            .'<script type="text/javascript" src="test.js"></script>' ."\n"
            .'<script type="text/javascript" src="example.js"></script>',
            $tag->getContent()
        );
    }

}

?>