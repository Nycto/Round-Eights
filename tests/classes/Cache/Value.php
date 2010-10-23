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
class classes_Cache_Value extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a test cache that expects a single method call
     *
     * @return \r8\iface\Cache
     */
    public function getTestValueSimple ( $method, $result )
    {
        $cache = $this->getMock('\r8\iface\Cache');
        $cache->expects( $this->once() )->method( $method )
            ->with( $this->equalTo( "key" ) )
            ->will( $this->returnValue( $result) );
        return new \r8\Cache\Value("key", $cache);
    }

    /**
     * Returns a test cache that expects a single method call
     *
     * @return \r8\iface\Cache
     */
    public function getTestValueComplex ( $method )
    {
        $cache = $this->getMock('\r8\iface\Cache');
        $cache->expects( $this->once() )->method( $method )
            ->with(
                $this->equalTo( "key" ),
                $this->equalTo( "value" ),
                $this->equalTo( 1234 )
            );
        return new \r8\Cache\Value("key", $cache);
    }

    public function testSet ()
    {
        $value = $this->getTestValueComplex( "set" );
        $this->assertSame( $value, $value->set("value", 1234) );
    }

    public function testGet ()
    {
        $value = $this->getTestValueSimple( "get", "value" );
        $this->assertSame( "value", $value->get() );
    }

    public function testDelete ()
    {
        $value = $this->getTestValueSimple( "delete", NULL );
        $this->assertSame( $value, $value->delete() );
    }

    public function testAdd ()
    {
        $value = $this->getTestValueComplex( "add" );
        $this->assertSame( $value, $value->add("value", 1234) );
    }

    public function testReplace ()
    {
        $value = $this->getTestValueComplex( "replace" );
        $this->assertSame( $value, $value->replace("value", 1234) );
    }

    public function testAppend ()
    {
        $value = $this->getTestValueComplex( "append" );
        $this->assertSame( $value, $value->append("value", 1234) );
    }

    public function testPrepend ()
    {
        $value = $this->getTestValueComplex( "prepend" );
        $this->assertSame( $value, $value->prepend("value", 1234) );
    }

    public function testIncrement ()
    {
        $value = $this->getTestValueSimple( "increment", NULL );
        $this->assertSame( $value, $value->increment() );
    }

    public function testDecrement ()
    {
        $value = $this->getTestValueSimple( "decrement", NULL );
        $this->assertSame( $value, $value->decrement() );
    }

}

