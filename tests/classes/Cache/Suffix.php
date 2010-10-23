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
class classes_Cache_Suffix extends PHPUnit_Framework_TestCase
{

    public function testSet ()
    {
        $inner = $this->getMock('\r8\iface\Cache');
        $inner->expects( $this->once() )
            ->method( "set" )
            ->with(
                $this->equalTo( "key_suffix" ),
                $this->equalTo( "Data" ),
                $this->equalTo( 1234 )
            );

        $cache = new \r8\Cache\Suffix("_suffix", $inner);
        $this->assertSame( $cache, $cache->set("key", "Data", 1234) );
    }

    public function testGet ()
    {
        $inner = $this->getMock('\r8\iface\Cache');
        $inner->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo( "key_suffix" ) )
            ->will( $this->returnValue( "Data" ) );

        $cache = new \r8\Cache\Suffix("_suffix", $inner);
        $this->assertSame( "Data", $cache->get("key") );
    }

    public function testDelete ()
    {
        $inner = $this->getMock('\r8\iface\Cache');
        $inner->expects( $this->once() )
            ->method( "delete" )
            ->with( $this->equalTo( "key_suffix" ) );

        $cache = new \r8\Cache\Suffix("_suffix", $inner);
        $this->assertSame( $cache, $cache->delete("key") );
    }

    public function testAdd ()
    {
        $inner = $this->getMock('\r8\iface\Cache');
        $inner->expects( $this->once() )
            ->method( "add" )
            ->with(
                $this->equalTo( "key_suffix" ),
                $this->equalTo( "Data" ),
                $this->equalTo( 1234 )
            );

        $cache = new \r8\Cache\Suffix("_suffix", $inner);
        $this->assertSame( $cache, $cache->add("key", "Data", 1234) );
    }

    public function testReplace ()
    {
        $inner = $this->getMock('\r8\iface\Cache');
        $inner->expects( $this->once() )
            ->method( "replace" )
            ->with(
                $this->equalTo( "key_suffix" ),
                $this->equalTo( "Data" ),
                $this->equalTo( 1234 )
            );

        $cache = new \r8\Cache\Suffix("_suffix", $inner);
        $this->assertSame( $cache, $cache->replace("key", "Data", 1234) );
    }

    public function testAppend ()
    {
        $inner = $this->getMock('\r8\iface\Cache');
        $inner->expects( $this->once() )
            ->method( "append" )
            ->with(
                $this->equalTo( "key_suffix" ),
                $this->equalTo( "Data" ),
                $this->equalTo( 1234 )
            );

        $cache = new \r8\Cache\Suffix("_suffix", $inner);
        $this->assertSame( $cache, $cache->append("key", "Data", 1234) );
    }

    public function testPrepend ()
    {
        $inner = $this->getMock('\r8\iface\Cache');
        $inner->expects( $this->once() )
            ->method( "prepend" )
            ->with(
                $this->equalTo( "key_suffix" ),
                $this->equalTo( "Data" ),
                $this->equalTo( 1234 )
            );

        $cache = new \r8\Cache\Suffix("_suffix", $inner);
        $this->assertSame( $cache, $cache->prepend("key", "Data", 1234) );
    }

    public function testIncrement ()
    {
        $inner = $this->getMock('\r8\iface\Cache');
        $inner->expects( $this->once() )
            ->method( "increment" )
            ->with( $this->equalTo( "key_suffix" ) );

        $cache = new \r8\Cache\Suffix("_suffix", $inner);
        $this->assertSame( $cache, $cache->increment("key") );
    }

    public function testDecrement ()
    {
        $inner = $this->getMock('\r8\iface\Cache');
        $inner->expects( $this->once() )
            ->method( "decrement" )
            ->with( $this->equalTo( "key_suffix" ) );

        $cache = new \r8\Cache\Suffix("_suffix", $inner);
        $this->assertSame( $cache, $cache->decrement("key") );
    }

    public function testFlush ()
    {
        $inner = $this->getMock('\r8\iface\Cache');
        $inner->expects( $this->once() )->method( "flush" );

        $cache = new \r8\Cache\Suffix("_suffix", $inner);
        $this->assertSame( $cache, $cache->flush() );
    }

}

