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
class classes_HTML_Javascript extends PHPUnit_Framework_TestCase
{

    public function testSource ()
    {
        $js = new \r8\HTML\Javascript("/example.js");
        $this->assertSame( "/example.js", $js->getSource() );

        $this->assertSame( $js, $js->setSource("/Other/File.js") );
        $this->assertSame( "/Other/File.js", $js->getSource() );

        try {
            $js->setSource("   ");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Data $err ) {
            $this->assertSame( "URL must not contain spaces", $err->getMessage() );
        }
    }

    public function testGetTag ()
    {
        $js = new \r8\HTML\Javascript("/example.js");

        $tag = $js->getTag();
        $this->assertThat( $tag, $this->isInstanceOf('\r8\HTML\Tag') );
        $this->assertSame(
            '<script type="text/javascript" src="/example.js"></script>',
            $tag->__toString()
        );

        $js->setSource("/test.js");

        $tag = $js->getTag();
        $this->assertThat( $tag, $this->isInstanceOf('\r8\HTML\Tag') );
        $this->assertSame(
            '<script type="text/javascript" src="/test.js"></script>',
            $tag->__toString()
        );
    }

}

?>