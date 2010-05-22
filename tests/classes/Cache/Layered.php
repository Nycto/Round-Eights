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
class classes_Cache_Layered extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a test cache that expects a single method call
     *
     * @return \r8\iface\Cache
     */
    public function getTestCacheSimple ( $method, $result )
    {
        $cache = $this->getMock('\r8\iface\Cache');
        $cache->expects( $this->once() )->method( $method )
            ->with( $this->equalTo( "key" ) )
            ->will( $this->returnValue( $result) );
        return $cache;
    }

    /**
     * Returns a test cache that expects a single method call
     *
     * @return \r8\iface\Cache
     */
    public function getTestCacheComplex ( $method )
    {
        $cache = $this->getMock('\r8\iface\Cache');
        $cache->expects( $this->once() )->method( $method )
            ->with(
                $this->equalTo( "key" ),
                $this->equalTo( "value" ),
                $this->equalTo( 1234 )
            );
        return $cache;
    }

    public function testSet ()
    {
        $cache = new \r8\Cache\Layered(
            $this->getTestCacheComplex("set"),
            $this->getTestCacheComplex("set")
        );

        $this->assertSame( $cache, $cache->set("key", "value", 1234) );
    }

    public function testGet_FromPrimary ()
    {
        $primary = $this->getTestCacheSimple( "get", "Value" );

        $secondary = $this->getMock('\r8\iface\Cache');
        $secondary->expects( $this->never() )->method( "set" );

        $cache = new \r8\Cache\Layered( $primary, $secondary );

        $this->assertSame( "Value", $cache->get("key") );
    }

    public function testGet_FromSecondary ()
    {
        $cache = new \r8\Cache\Layered(
            $this->getTestCacheSimple( "get", NULL ),
            $this->getTestCacheSimple( "get", "Value" )
        );

        $this->assertSame( "Value", $cache->get("key") );
    }

    public function testDelete ()
    {
        $cache = new \r8\Cache\Layered(
            $this->getTestCacheSimple( "delete", NULL ),
            $this->getTestCacheSimple( "delete", NULL )
        );

        $this->assertSame( $cache, $cache->delete("key") );
    }

    public function testAdd ()
    {
        $cache = new \r8\Cache\Layered(
            $this->getTestCacheComplex("add"),
            $this->getTestCacheComplex("add")
        );

        $this->assertSame( $cache, $cache->add("key", "value", 1234) );
    }

    public function testReplace ()
    {
        $cache = new \r8\Cache\Layered(
            $this->getTestCacheComplex("replace"),
            $this->getTestCacheComplex("replace")
        );

        $this->assertSame( $cache, $cache->replace("key", "value", 1234) );
    }

    public function testAppend ()
    {
        $cache = new \r8\Cache\Layered(
            $this->getTestCacheComplex("append"),
            $this->getTestCacheComplex("append")
        );

        $this->assertSame( $cache, $cache->append("key", "value", 1234) );
    }

    public function testPrepend ()
    {
        $cache = new \r8\Cache\Layered(
            $this->getTestCacheComplex("prepend"),
            $this->getTestCacheComplex("prepend")
        );

        $this->assertSame( $cache, $cache->prepend("key", "value", 1234) );
    }

    public function testIncrement ()
    {
        $cache = new \r8\Cache\Layered(
            $this->getTestCacheSimple("increment", NULL),
            $this->getTestCacheSimple("increment", NULL)
        );

        $this->assertSame( $cache, $cache->increment("key") );
    }

    public function testDecrement ()
    {
        $cache = new \r8\Cache\Layered(
            $this->getTestCacheSimple("decrement", NULL),
            $this->getTestCacheSimple("decrement", NULL)
        );

        $this->assertSame( $cache, $cache->decrement("key") );
    }

    public function testFlush ()
    {
        $primary = $this->getMock('\r8\iface\Cache');
        $primary->expects( $this->once() )->method( "flush" );

        $secondary = $this->getMock('\r8\iface\Cache');
        $secondary->expects( $this->once() )->method( "flush" );

        $cache = new \r8\Cache\Layered( $primary, $secondary );

        $this->assertSame( $cache, $cache->flush() );
    }

}

?>