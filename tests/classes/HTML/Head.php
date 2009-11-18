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

}

?>