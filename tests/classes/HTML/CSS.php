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
class classes_HTML_CSS extends PHPUnit_Framework_TestCase
{

    public function testSource ()
    {
        $css = new \r8\HTML\CSS("/example.css");
        $this->assertSame( "/example.css", $css->getSource() );

        $this->assertSame( $css, $css->setSource("/Other/File.css") );
        $this->assertSame( "/Other/File.css", $css->getSource() );

        try {
            $css->setSource("   ");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Data $err ) {
            $this->assertSame( "URL must not contain spaces", $err->getMessage() );
        }
    }

    public function testMedia ()
    {
        $css = new \r8\HTML\CSS("/example.css");
        $this->assertSame( "all", $css->getMedia() );

        $this->assertSame( $css, $css->setMedia("screen, print") );
        $this->assertSame( "screen, print", $css->getMedia() );

        $this->assertSame( $css, $css->setMedia("   ") );
        $this->assertSame( "all", $css->getMedia() );
    }

    public function testConditionAccessors ()
    {
        $css = new \r8\HTML\CSS("/example.css");
        $this->assertNull( $css->getCondition() );

        $condition = new \r8\HTML\Conditional('lte IE7');
        $this->assertSame( $css, $css->setCondition( $condition ) );
        $this->assertSame( $condition, $css->getCondition() );

        $this->assertSame( $css, $css->clearCondition() );
        $this->assertNull( $css->getCondition() );
    }

    public function testGetTag ()
    {
        $css = new \r8\HTML\CSS("/example.css");

        $tag = $css->getTag();
        $this->assertThat( $tag, $this->isInstanceOf('\r8\HTML\Tag') );
        $this->assertSame(
            '<link rel="stylesheet" href="/example.css" type="text/css" media="all" />',
            $tag->__toString()
        );

        $css->setSource("/test.css")
            ->setMedia("print");

        $tag = $css->getTag();
        $this->assertThat( $tag, $this->isInstanceOf('\r8\HTML\Tag') );
        $this->assertSame(
            '<link rel="stylesheet" href="/test.css" type="text/css" media="print" />',
            $tag->__toString()
        );
    }

    public function testGetTag_Condition ()
    {
        $css = new \r8\HTML\CSS("/example.css");
        $condition = new \r8\HTML\Conditional('lte IE7');
        $css->setCondition( $condition );

        $tag = $css->getTag();
        $this->assertSame( $condition, $tag );

        $this->assertSame(
            '<!--[if lte IE7]>'
            .'<link rel="stylesheet" href="/example.css" type="text/css" media="all" />'
            .'<![endif]-->',
            $tag->__toString()
        );
    }

}

?>