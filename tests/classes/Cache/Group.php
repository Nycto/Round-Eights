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
class classes_Cache_Group extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a test cache that expects a single method call
     *
     * @return \r8\Cache\Group
     */
    public function getTestCacheSimple ( $method, $result = NULL )
    {
        $cache = $this->getMock('\r8\iface\Cache');
        $cache->expects( $this->once() )->method( "get" )
            ->with( $this->equalTo( "group_GroupValue" ) )
            ->will( $this->returnValue( "abc123" ) );
        $cache->expects( $this->once() )->method( $method )
            ->with( $this->equalTo( "group_abc123_key" ) )
            ->will( $this->returnValue( $result ) );
        return new \r8\Cache\Group("group", $cache);
    }

    /**
     * Returns a test cache that expects a single method call
     *
     * @return \r8\Cache\Group
     */
    public function getTestCacheComplex ( $method )
    {
        $cache = $this->getMock('\r8\iface\Cache');
        $cache->expects( $this->once() )->method( "get" )
            ->with( $this->equalTo( "group_GroupValue" ) )
            ->will( $this->returnValue( "abc123" ) );
        $cache->expects( $this->once() )->method( $method )
            ->with(
                $this->equalTo( "group_abc123_key" ),
                $this->equalTo( "value" ),
                $this->equalTo( 1234 )
            );
        return new \r8\Cache\Group("group", $cache);
    }

    public function testSet ()
    {
        $cache = $this->getTestCacheComplex("set");
        $this->assertSame( $cache, $cache->set("key", "value", 1234) );
    }

    public function testGet ()
    {
        $inner = $this->getMock('\r8\iface\Cache');
        $inner->expects( $this->at(0) )->method( "get" )
            ->with( $this->equalTo( "group_GroupValue" ) )
            ->will( $this->returnValue( "abc123" ) );
        $inner->expects( $this->at(1) )->method( "get" )
            ->with( $this->equalTo( "group_abc123_key" ) )
            ->will( $this->returnValue( "value" ) );

        $cache = new \r8\Cache\Group("group", $inner);

        $this->assertSame( "value", $cache->get("key") );
    }

    public function testGet_NoGroupValue ()
    {
        $inner = $this->getMock('\r8\iface\Cache');
        $inner->expects( $this->at(0) )->method( "get" )
            ->with( $this->equalTo( "group_GroupValue" ) )
            ->will( $this->returnValue( NULL ) );
        $inner->expects( $this->once() )->method( "set" )
            ->with(
                $this->equalTo( "group_GroupValue" ),
                $this->matchesRegularExpression( '/^[0-9a-z]+$/i' ),
                $this->equalTo( 0 )
            );
        $inner->expects( $this->at(2) )->method( "get" )
            ->with( $this->matchesRegularExpression( '/^group_[0-9a-zA-Z]+_key$/' ) )
            ->will( $this->returnValue( "value" ) );

        $cache = new \r8\Cache\Group("group", $inner);

        $this->assertSame( "value", $cache->get("key") );
    }

    public function testDelete ()
    {
        $cache = $this->getTestCacheSimple("delete");
        $this->assertSame( $cache, $cache->delete("key") );
    }

    public function testAdd ()
    {
        $cache = $this->getTestCacheComplex("add");
        $this->assertSame( $cache, $cache->add("key", "value", 1234) );
    }

    public function testReplace ()
    {
        $cache = $this->getTestCacheComplex("replace");
        $this->assertSame( $cache, $cache->replace("key", "value", 1234) );
    }

    public function testAppend ()
    {
        $cache = $this->getTestCacheComplex("append");
        $this->assertSame( $cache, $cache->append("key", "value", 1234) );
    }

    public function testPrepend ()
    {
        $cache = $this->getTestCacheComplex("prepend");
        $this->assertSame( $cache, $cache->prepend("key", "value", 1234) );
    }

    public function testIncrement ()
    {
        $cache = $this->getTestCacheSimple("increment");
        $this->assertSame( $cache, $cache->increment("key") );
    }

    public function testDecrement ()
    {
        $cache = $this->getTestCacheSimple("decrement");
        $this->assertSame( $cache, $cache->decrement("key") );
    }

    public function testFlush ()
    {
        $inner = $this->getMock('\r8\iface\Cache');
        $inner->expects( $this->once() )->method( "set" )
            ->with(
                $this->equalTo( "group_GroupValue" ),
                $this->matchesRegularExpression( '/^[0-9a-z]+$/i' ),
                $this->equalTo( 0 )
            );
        $inner->expects( $this->once() )->method( "get" )
            ->with( $this->matchesRegularExpression( '/^group_[0-9a-zA-Z]+_key$/' ) )
            ->will( $this->returnValue( "value" ) );

        $cache = new \r8\Cache\Group("group", $inner);

        $this->assertSame( $cache, $cache->flush() );
        $this->assertSame( "value", $cache->get("key") );
    }

    public function testGetForUpdate_NonUpdatable ()
    {
        $inner = $this->getMock('\r8\iface\Cache');
        $cache = new \r8\Cache\Group("group", $inner);

        try {
            $cache->getForUpdate("key");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Data $err ) {}
    }

    public function testGetForUpdate ()
    {
        $inner = $this->getMock('\r8\iface\Cache\Updatable');
        $inner->expects( $this->once() )->method( "get" )
            ->with( $this->equalTo( "group_GroupValue" ) )
            ->will( $this->returnValue( "abc123" ) );
        $inner->expects( $this->once() )->method( "getForUpdate" )
            ->with( $this->equalTo( "group_abc123_key" ) )
            ->will( $this->returnValue(
                new \r8\Cache\Result(
                    $inner, "group_abc123_key", "xyz789", "value"
                )
            ) );

        $cache = new \r8\Cache\Group("group", $inner);

        $this->assertEquals(
            new \r8\Cache\Result( $cache, "key", "xyz789", "value" ),
            $cache->getForUpdate('key')
        );
    }

    public function testSetIfSame_NonUpdatable ()
    {
        $inner = $this->getMock('\r8\iface\Cache');
        $cache = new \r8\Cache\Group("group", $inner);

        try {
            $cache->setIfSame(
                new \r8\Cache\Result($cache, "key", "xyz789", "value"),
                "new value",
                1234
            );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Data $err ) {}
    }

    public function testSetIfSame ()
    {
        $inner = $this->getMock('\r8\iface\Cache\Updatable');
        $inner->expects( $this->once() )->method( "get" )
            ->with( $this->equalTo( "group_GroupValue" ) )
            ->will( $this->returnValue( "abc123" ) );
        $inner->expects( $this->once() )->method( "setIfSame" )
            ->with(
                $this->equalTo(
                    new \r8\Cache\Result(
                        $inner, "group_abc123_key", "xyz789", "value"
                    )
                ),
                $this->equalTo( "new value" ),
                $this->equalTo( 1234 )
            );

        $cache = new \r8\Cache\Group("group", $inner);

        $this->assertSame(
            $cache,
            $cache->setIfSame(
                new \r8\Cache\Result( $cache, "key", "xyz789", "value" ),
                "new value",
                1234
            )
        );
    }

}

