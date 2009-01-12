<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of commonPHP.
 *
 * commonPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * commonPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with commonPHP. If not, see <http://www.commonphp.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@commonphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_encode_mime extends PHPUnit_Framework_TestCase
{

    public function testLineLengthAccessors ()
    {
        $mime = new \cPHP\Encode\MIME;
        $this->assertSame( 78, $mime->getLineLength() );

        $this->assertSame( $mime, $mime->setLineLength(999) );
        $this->assertSame( 999, $mime->getLineLength() );

        $this->assertSame( $mime, $mime->setLineLength("50") );
        $this->assertSame( 50, $mime->getLineLength() );

        $this->assertSame( $mime, $mime->setLineLength(500.5) );
        $this->assertSame( 500, $mime->getLineLength() );

        $this->assertSame( $mime, $mime->setLineLength(0) );
        $this->assertSame( 0, $mime->getLineLength() );

        $this->assertSame( $mime, $mime->setLineLength(-20) );
        $this->assertSame( 0, $mime->getLineLength() );

        $this->assertSame( $mime, $mime->setLineLength(null) );
        $this->assertSame( 0, $mime->getLineLength() );

        $this->assertSame( $mime, $mime->setLineLength(FALSE) );
        $this->assertSame( 0, $mime->getLineLength() );
    }

    public function testEncode ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testDecode ()
    {
        $this->markTestIncomplete("To be written");
    }

}

?>