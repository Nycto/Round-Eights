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
class classes_Cache_Base extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a test Cache object
     *
     * @return \r8\Cache\Base
     */
    public function getTestCache ()
    {
        return $this->getMock(
            '\r8\Cache\Base',
            array('get', 'set', 'add', 'replace', 'append', 'prepend',
                'increment', 'decrement', 'delete', 'flush')
        );
    }

    public function testYield_inCache ()
    {
        $cache = $this->getTestCache();
        $cache->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo( "unitTest_key" ) )
            ->will( $this->returnValue( "Cached Value" ) );
        $cache->expects( $this->never() )->method('set');

        $callback = $this->getMock('stdClass', array('__invoke'));
        $callback->expects( $this->never() )
            ->method("__invoke");

        $this->assertSame(
            "Cached Value",
            $cache->yield("unitTest_key", 0, $callback)
        );
    }

    public function testYield_notCached ()
    {
        $cache = $this->getTestCache();
        $cache->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo( "unitTest_key" ) )
            ->will( $this->returnValue( NULL ) );
        $cache->expects( $this->once() )
            ->method( "set" )
            ->with(
                $this->equalTo( "unitTest_key" ),
                $this->equalTo( "Not Cached" ),
                $this->equalTo( 1234 )
            );

        $callback = $this->getMock('stdClass', array('__invoke'));
        $callback->expects( $this->once() )
            ->method("__invoke")
            ->will( $this->returnValue("Not Cached") );

        $this->assertSame(
            "Not Cached",
            $cache->yield("unitTest_key", 1234, $callback)
        );
    }

    public function testYield_invalidArg ()
    {
        $cache = $this->getTestCache();
        $cache->expects( $this->never() )->method('get');
        $cache->expects( $this->never() )->method('set');

        try {
            $cache->yield("unitTest_key", 0, "This isnt callable");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "Must be callable", $err->getMessage() );
        }
    }

}

?>