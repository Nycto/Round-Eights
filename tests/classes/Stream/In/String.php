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

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_stream_in_string extends PHPUnit_Framework_TestCase
{

    public function testRead_simple ()
    {
        $stream = new \cPHP\Stream\In\String("Test");

        $this->assertSame("T", $stream->read(1));
        $this->assertSame("e", $stream->read(1));
        $this->assertSame("s", $stream->read(1));
        $this->assertSame("t", $stream->read(1));
        $this->assertNull( $stream->read(1) );
        $this->assertNull( $stream->read(1) );
    }

    public function testRead_multiByte ()
    {
        $stream = new \cPHP\Stream\In\String(
                "This is a longer string to read"
            );

        $this->assertSame("This is a ", $stream->read(10));
        $this->assertSame("longer ", $stream->read(7));
        $this->assertSame("strin", $stream->read(5));
        $this->assertSame("g t", $stream->read(3));
        $this->assertSame("o", $stream->read(1));
        $this->assertSame(" re", $stream->read(3));
        $this->assertSame("ad", $stream->read(5));

        $this->assertNull( $stream->read(50) );
        $this->assertNull( $stream->read(20) );
        $this->assertNull( $stream->read(10) );
    }

    public function testRewind ()
    {
        $stream = new \cPHP\Stream\In\String( "This is a string" );

        $this->assertSame("This is a ", $stream->read(10));

        $this->assertSame( $stream, $stream->rewind() );

        $this->assertSame("This is a ", $stream->read(10));
    }

    public function testReadAll ()
    {
        $stream = new \cPHP\Stream\In\String( "This is a string" );

        $this->assertSame( "This is a string", $stream->readAll() );
        $this->assertNull( $stream->readAll() );

        $stream->rewind();
        $this->assertSame( "This is a string", $stream->readAll() );
        $this->assertNull( $stream->readAll() );

        $stream->rewind();
        $stream->read(5);
        $this->assertSame( "is a string", $stream->readAll() );
        $this->assertNull( $stream->readAll() );

    }

}

?>