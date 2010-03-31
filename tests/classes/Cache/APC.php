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
class classes_Cache_APC extends PHPUnit_Framework_TestCase
{

    public function setUp ()
    {
        if ( !extension_loaded('apc') )
            $this->markTestSkipped("APC extension not loaded");

        if ( ini_get('apc.enable_cli') != 1 )
            $this->markTestSkipped("apc.enable_cli must be enabled");
    }

    public function getTestLink ()
    {
        return new \r8\Cache\APC;
    }

    public function testGet ()
    {
        $cache = $this->getTestLink();

        $this->assertSame( $cache, $cache->set("testGet", "Chunk of Data") );
        $this->assertSame( "Chunk of Data", $cache->get("testGet") );
    }

    public function testGet_notSet ()
    {
        $cache = $this->getTestLink();
        $this->assertNull( $cache->get("unitTest_notSet") );
    }

    public function testGet_Types ()
    {
        $cache = $this->getTestLink();

        $obj = new stdClass;
        $obj->key = "Data";

        $cache->set("null", NULL);
        $cache->set("false", FALSE);
        $cache->set("true", TRUE);
        $cache->set("int", 12345);
        $cache->set("flt", 3.1415);
        $cache->set("str", "Data");
        $cache->set("ary", array(1,2,3));
        $cache->set("obj", $obj);

        $this->assertEquals( NULL, $cache->get("null") );
        $this->assertEquals( FALSE, $cache->get("false") );
        $this->assertEquals( TRUE, $cache->get("true") );
        $this->assertEquals( 12345, $cache->get("int") );
        $this->assertEquals( 3.1415, $cache->get("flt") );
        $this->assertEquals( "Data", $cache->get("str") );
        $this->assertEquals( array(1,2,3), $cache->get("ary") );

        $this->assertEquals( $obj, $cache->get("obj") );
        $this->assertNotSame( $obj, $cache->get("obj") );
    }

    public function testDelete ()
    {
        $cache = $this->getTestLink();

        $cache->set("testDelete", "Initial Data");

        $this->assertSame( $cache, $cache->delete("testDelete") );

        $this->assertNull( $cache->get("testDelete") );
    }

    public function testAddReplace ()
    {
        $cache = $this->getTestLink();

        // Set up the first parts of the add/replace tests

        $this->assertSame( $cache, $cache->replace("testReplace", "value") );
        $this->assertNull( $cache->get("testReplace") );
        $cache->set("testReplace", "original");

        $this->assertSame( $cache, $cache->add("testAdd", "value") );
        $this->assertSame("value", $cache->get("testAdd"));

        // Now execute the second part of the add/replace tests

        $this->assertSame( $cache, $cache->replace("testReplace", "value") );
        $this->assertSame("value", $cache->get("testReplace"));

        $this->assertSame( $cache, $cache->add("testAdd", "new") );
        $this->assertSame("value", $cache->get("testAdd"));
    }

    public function testAdjust ()
    {
        $cache = $this->getTestLink();

        // Set up the precondition values in the cache
        $this->assertSame( $cache, $cache->append("testAppend", "value") );
        $this->assertSame( $cache, $cache->set("tesAppendErr", new stdClass) );
        $this->assertSame( $cache, $cache->prepend("testPrepend", "value") );
        $this->assertSame( $cache, $cache->set("testPrependErr", new stdClass) );
        $this->assertSame( $cache, $cache->set("testDecrement", 3) );
        $this->assertSame( $cache, $cache->set("testDecrementStr", "Some Value") );
        $this->assertSame( $cache, $cache->set("testIncrement", 3) );
        $this->assertSame( $cache, $cache->set("testIncrementStr", "Some Value") );

        // Now call the methods to adjust the cache values

        $this->assertEquals( $cache, $cache->append("testAppend", "suffix") );
        $this->assertEquals( "valuesuffix", $cache->get("testAppend") );

        $this->assertEquals( $cache, $cache->append("tesAppendErr", "suffix") );
        $this->assertEquals( "suffix", $cache->get("tesAppendErr") );

        $this->assertEquals( $cache, $cache->prepend("testPrepend", "prefix") );
        $this->assertEquals( "prefixvalue", $cache->get("testPrepend") );

        $this->assertEquals( $cache, $cache->prepend("testPrependErr", "suffix") );
        $this->assertEquals( "suffix", $cache->get("testPrependErr") );

        $this->assertEquals( $cache, $cache->decrement("testDecrement") );
        $this->assertEquals( 2, $cache->get("testDecrement") );

        $this->assertEquals( $cache, $cache->decrement("testDecrementStr") );
        $this->assertEquals( 0, $cache->get("testDecrementStr") );

        $this->assertEquals( $cache, $cache->increment("testIncrement") );
        $this->assertEquals( 4, $cache->get("testIncrement") );

        $this->assertEquals( $cache, $cache->increment("testIncrementStr") );
        $this->assertEquals( 0, $cache->get("testIncrementStr") );
    }

}

?>