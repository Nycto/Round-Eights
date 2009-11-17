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

}

?>