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
class classes_HTML_MetaTag extends PHPUnit_Framework_TestCase
{

    public function testAccessors ()
    {
        $tag = new \r8\HTML\MetaTag( "name", "content" );
        $this->assertSame( "name", $tag->getName() );
        $this->assertSame( "content", $tag->getContent() );

        $this->assertSame( $tag, $tag->setName("robots") );
        $this->assertSame( $tag, $tag->setContent("index, follow") );
        $this->assertSame( "robots", $tag->getName() );
        $this->assertSame( "index, follow", $tag->getContent() );
    }

    public function testGetTag ()
    {
        $meta = new \r8\HTML\MetaTag("robots", "index, follow");

        $tag = $meta->getTag();
        $this->assertThat( $tag, $this->isInstanceOf('\r8\HTML\Tag') );
        $this->assertSame(
        	'<meta name="robots" content="index, follow" />',
            $tag->__toString()
        );

        $meta->setName("keywords")
            ->setContent("blah, stuff");

        $tag = $meta->getTag();
        $this->assertThat( $tag, $this->isInstanceOf('\r8\HTML\Tag') );
        $this->assertSame(
        	'<meta name="keywords" content="blah, stuff" />',
            $tag->__toString()
        );
    }

}

?>