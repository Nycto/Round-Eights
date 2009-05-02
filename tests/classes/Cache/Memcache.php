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

use cPHP\Cache;
require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_cache_memcache extends PHPUnit_Framework_TestCase
{

    public function setUp ()
    {
        if ( !extension_loaded('memcache') )
            $this->markTestSkipped("Memcache extension not loaded");

        // Ensure the proper configuration exists
        $config = new cPHP_Test_Config(
                "MEMCACHE",
                array( "HOST", "PORT" )
            );
        $config->test();

        // Test the connection
        $memcache = new Memcache;
        if ( !$memcache->connect(MEMCACHE_HOST, MEMCACHE_PORT) )
            $this->markTestSkipped("Unable to connect to Memcached server");
    }

    public function getTestLink ()
    {
        return new \cPHP\Cache\Memcache(MEMCACHE_HOST, MEMCACHE_PORT);
    }

    public function testConnect_error ()
    {
        $memcache = new \cPHP\Cache\Memcache("Not a real host", 1234);
        try {
            $memcache->connect();
        }
        catch ( \cPHP\Exception\Memcache\Connection $err ) {}
    }

    public function testIsConnected ()
    {
        $memcache = $this->getTestLink();

        $this->assertFalse( $memcache->isConnected() );

        $this->assertSame( $memcache, $memcache->connect() );

        $this->assertTrue( $memcache->isConnected() );

        $this->assertSame( $memcache, $memcache->disconnect() );

        $this->assertFalse( $memcache->isConnected() );
    }

    public function testGet ()
    {
        $memcache = $this->getTestLink();

        $this->assertSame(
                $memcache,
                $memcache->set("unitTest_key", "Chunk of Data")
            );

        $this->assertSame( "Chunk of Data", $memcache->get("unitTest_key") );


        $this->assertSame(
                $memcache,
                $memcache->set("unitTest_key", "New Data")
            );

        $this->assertSame( "New Data", $memcache->get("unitTest_key") );
    }

    public function testGet_notSet ()
    {
        $memcache = $this->getTestLink();
        $this->assertNull( $memcache->get("unitTest_notSet") );
    }

    public function testGet_BooleanFalse ()
    {
        $memcache = $this->getTestLink();
        $memcache->set("unitTest_key", FALSE);
        $this->assertFalse( $memcache->get("unitTest_key") );
    }

    public function testGetForUpdate ()
    {
        $memcache = $this->getTestLink();

        $memcache->set("unitTest_key", "Chunk of Data");

        $result = $memcache->getForUpdate("unitTest_key");

        $this->assertThat( $result, $this->isInstanceOf('cPHP\Cache\Result') );

        $this->assertSame( $memcache, $result->getCache() );
        $this->assertSame( "unitTest_key", $result->getKey() );
        $this->assertSame( "Chunk of Data", $result->getValue() );
        $this->assertNull( $result->getHash() );
    }

    public function testSetIfSame ()
    {
        $memcache = $this->getTestLink();

        $memcache->set("unitTest_key", "Initial Data");

        $result = $memcache->getForUpdate("unitTest_key");
        $this->assertThat( $result, $this->isInstanceOf('cPHP\Cache\Result') );

        $memcache->setIfSame( $result, "New Value");
        $this->assertSame( "New Value", $memcache->get("unitTest_key") );
    }

    public function testDelete ()
    {
        $memcache = $this->getTestLink();

        $memcache->set("unitTest_key", "Initial Data");

        $this->assertSame( $memcache, $memcache->delete("unitTest_key") );

        $this->assertNull( $memcache->get("unitTest_key") );
    }

    public function testAppend ()
    {
        $memcache = $this->getTestLink();
        $memcache->delete("unitTest_key");

        $memcache->append("unitTest_key", "first");
        $this->assertSame("first", $memcache->get("unitTest_key"));

        $memcache->append("unitTest_key", "second");
        $this->assertSame("firstsecond", $memcache->get("unitTest_key"));

        $memcache->append("unitTest_key", "third");
        $this->assertSame("firstsecondthird", $memcache->get("unitTest_key"));
    }

    public function testPrepend ()
    {
        $memcache = $this->getTestLink();
        $memcache->delete("unitTest_key");

        $memcache->prepend("unitTest_key", "first");
        $this->assertSame("first", $memcache->get("unitTest_key"));

        $memcache->prepend("unitTest_key", "second");
        $this->assertSame("secondfirst", $memcache->get("unitTest_key"));

        $memcache->prepend("unitTest_key", "third");
        $this->assertSame("thirdsecondfirst", $memcache->get("unitTest_key"));
    }

    public function testFlush ()
    {
        $memcache = $this->getTestLink();

        $memcache->set("unitTest_key", "Value");

        $this->assertSame( $memcache, $memcache->flush() );

        $this->assertNull( $memcache->get("unitTest_key") );
    }

}

?>