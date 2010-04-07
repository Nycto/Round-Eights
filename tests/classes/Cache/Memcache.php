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
class classes_Cache_MemCache extends PHPUnit_Framework_TestCase
{

    public function setUp ()
    {
        if ( !extension_loaded('memcache') )
            $this->markTestSkipped("Memcache extension not loaded");

        // Ensure the proper configuration exists
        $config = new \r8\Test\Config( "MEMCACHE", array( "HOST", "PORT" ) );
        $config->test();

        // Test the connection
        $memcache = new Memcache;
        if ( !@$memcache->connect(MEMCACHE_HOST, MEMCACHE_PORT) )
            $this->markTestSkipped("Unable to connect to Memcached server");
    }

    public function getTestLink ()
    {
        return new \r8\Cache\Memcache(MEMCACHE_HOST, MEMCACHE_PORT);
    }

    public function testConnect_error ()
    {
        $memcache = new \r8\Cache\Memcache("Not a real host", 1234);
        try {
            $memcache->connect();
        }
        catch ( \r8\Exception\Memcache\Connection $err ) {}
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

        $obj = new stdClass;
        $obj->key = "Data";

        $memcache->set("null", NULL);
        $memcache->set("false", FALSE);
        $memcache->set("true", TRUE);
        $memcache->set("int", 12345);
        $memcache->set("flt", 3.1415);
        $memcache->set("str", "Data");
        $memcache->set("ary", array(1,2,3));
        $memcache->set("obj", $obj);

        $this->assertEquals( NULL, $memcache->get("null") );
        $this->assertEquals( FALSE, $memcache->get("false") );
        $this->assertEquals( TRUE, $memcache->get("true") );
        $this->assertEquals( 12345, $memcache->get("int") );
        $this->assertEquals( 3.1415, $memcache->get("flt") );
        $this->assertEquals( "Data", $memcache->get("str") );
        $this->assertEquals( array(1,2,3), $memcache->get("ary") );
        $this->assertEquals( $obj, $memcache->get("obj") );
    }

    public function testSet_Expire ()
    {
        $memcache = $this->getTestLink();

        $this->assertSame(
            $memcache,
            $memcache->set("unitTest_key", "Expiring Data", 1)
        );

        usleep(1250000);

        $this->assertNull( $memcache->get("unitTest_key") );
    }

    public function testDelete ()
    {
        $memcache = $this->getTestLink();

        $memcache->set("unitTest_key", "Initial Data");

        $this->assertSame( $memcache, $memcache->delete("unitTest_key") );

        $this->assertNull( $memcache->get("unitTest_key") );
    }

    public function testAdd ()
    {
        $memcache = $this->getTestLink();

        $memcache->delete("unitTest_key");
        $this->assertSame( $memcache, $memcache->add("unitTest_key", "value") );
        $this->assertSame("value", $memcache->get("unitTest_key"));

        $this->assertSame( $memcache, $memcache->add("unitTest_key", "new") );
        $this->assertSame("value", $memcache->get("unitTest_key"));
    }

    public function testReplace ()
    {
        $memcache = $this->getTestLink();

        $memcache->delete("unitTest_key");
        $this->assertSame(
            $memcache,
            $memcache->replace("unitTest_key", "value")
        );
        $this->assertNull( $memcache->get("unitTest_key") );

        $memcache->set("unitTest_key", "original");
        $this->assertSame(
            $memcache,
            $memcache->replace("unitTest_key", "value")
        );
        $this->assertSame("value", $memcache->get("unitTest_key"));
    }

    public function testIncrement ()
    {
        $memcache = $this->getTestLink();
        $memcache->set("unitTest_key", 1);

        $this->assertSame($memcache, $memcache->increment("unitTest_key"));
        $this->assertSame(2, $memcache->get("unitTest_key"));

        $this->assertSame($memcache, $memcache->increment("unitTest_key"));
        $this->assertSame(3, $memcache->get("unitTest_key"));
    }

    public function testDecrement ()
    {
        $memcache = $this->getTestLink();
        $memcache->set("unitTest_key", 3);

        $this->assertSame($memcache, $memcache->decrement("unitTest_key"));
        $this->assertSame(2, $memcache->get("unitTest_key"));

        $this->assertSame($memcache, $memcache->decrement("unitTest_key"));
        $this->assertSame(1, $memcache->get("unitTest_key"));
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