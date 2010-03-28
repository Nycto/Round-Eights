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
class classes_Cache_Local extends PHPUnit_Framework_TestCase
{

    public function getTestLink ()
    {
        return new \r8\Cache\Local;
    }

    public function testGet ()
    {
        $cache = $this->getTestLink();

        $this->assertSame(
            $cache,
            $cache->set("unitTest_key", "Chunk of Data")
        );

        $this->assertSame( "Chunk of Data", $cache->get("unitTest_key") );


        $this->assertSame(
            $cache,
            $cache->set("unitTest_key", "New Data")
        );

        $this->assertSame( "New Data", $cache->get("unitTest_key") );
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

    public function testSet_Expire ()
    {
        $cache = $this->getTestLink();

        $this->assertSame(
            $cache,
            $cache->set("unitTest_key", "Expiring Data", 1)
        );

        usleep(1250000);

        $this->assertNull( $cache->get("unitTest_key") );
    }

    public function testDelete ()
    {
        $cache = $this->getTestLink();

        $cache->set("unitTest_key", "Initial Data");

        $this->assertSame( $cache, $cache->delete("unitTest_key") );

        $this->assertNull( $cache->get("unitTest_key") );
    }

    public function testAdd ()
    {
        $cache = $this->getTestLink();

        $cache->delete("unitTest_key");
        $this->assertSame( $cache, $cache->add("unitTest_key", "value") );
        $this->assertSame("value", $cache->get("unitTest_key"));

        $this->assertSame( $cache, $cache->add("unitTest_key", "new") );
        $this->assertSame("value", $cache->get("unitTest_key"));
    }

    public function testReplace ()
    {
        $cache = $this->getTestLink();

        $cache->delete("unitTest_key");
        $this->assertSame(
            $cache,
            $cache->replace("unitTest_key", "value")
        );
        $this->assertNull( $cache->get("unitTest_key") );

        $cache->set("unitTest_key", "original");
        $this->assertSame(
            $cache,
            $cache->replace("unitTest_key", "value")
        );
        $this->assertSame("value", $cache->get("unitTest_key"));
    }

    public function testIncrement ()
    {
        $cache = $this->getTestLink();
        $cache->set("unitTest_key", 1);

        $this->assertSame($cache, $cache->increment("unitTest_key"));
        $this->assertSame(2, $cache->get("unitTest_key"));

        $this->assertSame($cache, $cache->increment("unitTest_key"));
        $this->assertSame(3, $cache->get("unitTest_key"));
    }

    public function testIncrement_nonNumber ()
    {
        $cache = $this->getTestLink();
        $cache->set("unitTest_key", "Some Value");

        $this->assertSame($cache, $cache->increment("unitTest_key"));
        $this->assertSame(0, $cache->get("unitTest_key"));
    }

    public function testDecrement ()
    {
        $cache = $this->getTestLink();
        $cache->set("unitTest_key", 3);

        $this->assertSame($cache, $cache->decrement("unitTest_key"));
        $this->assertSame(2, $cache->get("unitTest_key"));

        $this->assertSame($cache, $cache->decrement("unitTest_key"));
        $this->assertSame(1, $cache->get("unitTest_key"));
    }

    public function testDecrement_nonNumber ()
    {
        $cache = $this->getTestLink();
        $cache->set("unitTest_key", "Some Value");

        $this->assertSame($cache, $cache->decrement("unitTest_key"));
        $this->assertSame(0, $cache->get("unitTest_key"));
    }

    public function testAppend ()
    {
        $cache = $this->getTestLink();
        $cache->delete("unitTest_key");

        $cache->append("unitTest_key", "first");
        $this->assertSame("first", $cache->get("unitTest_key"));

        $cache->append("unitTest_key", "second");
        $this->assertSame("firstsecond", $cache->get("unitTest_key"));

        $cache->append("unitTest_key", "third");
        $this->assertSame("firstsecondthird", $cache->get("unitTest_key"));
    }

    public function testAppend_NonPrimitives ()
    {
        $cache = $this->getTestLink();

        $cache->set("unitTest_key", new stdClass);
        $cache->append("unitTest_key", "suffix");
        $this->assertSame("suffix", $cache->get("unitTest_key"));

        $cache->set("unitTest_key", array(1,2,3));
        $cache->append("unitTest_key", "suffix");
        $this->assertSame("suffix", $cache->get("unitTest_key"));
    }

    public function testPrepend ()
    {
        $cache = $this->getTestLink();
        $cache->delete("unitTest_key");

        $cache->prepend("unitTest_key", "first");
        $this->assertSame("first", $cache->get("unitTest_key"));

        $cache->prepend("unitTest_key", "second");
        $this->assertSame("secondfirst", $cache->get("unitTest_key"));

        $cache->prepend("unitTest_key", "third");
        $this->assertSame("thirdsecondfirst", $cache->get("unitTest_key"));
    }

    public function testPrepend_NonPrimitives ()
    {
        $cache = $this->getTestLink();

        $cache->set("unitTest_key", new stdClass);
        $cache->prepend("unitTest_key", "suffix");
        $this->assertSame("suffix", $cache->get("unitTest_key"));

        $cache->set("unitTest_key", array(1,2,3));
        $cache->prepend("unitTest_key", "suffix");
        $this->assertSame("suffix", $cache->get("unitTest_key"));
    }

    public function testFlush ()
    {
        $cache = $this->getTestLink();

        $cache->set("unitTest_key", "Value");

        $this->assertSame( $cache, $cache->flush() );

        $this->assertNull( $cache->get("unitTest_key") );
    }

    public function testGetForUpdate ()
    {
        $cache = $this->getTestLink();
        $cache->set("unitTest_key", "Value");

        $forUpdate = $cache->getForUpdate("unitTest_key");
        $this->assertThat( $forUpdate, $this->isInstanceOf( '\r8\Cache\Result' ) );
        $this->assertSame( "Value", $forUpdate->getValue() );

        $forUpdate->setIfSame("New Value");

        $this->assertSame("New Value", $cache->get("unitTest_key"));
    }

    public function testGetForUpdate_Changed ()
    {
        $cache = $this->getTestLink();
        $cache->set("unitTest_key", "Value");

        $forUpdate = $cache->getForUpdate("unitTest_key");
        $this->assertThat( $forUpdate, $this->isInstanceOf( '\r8\Cache\Result' ) );
        $this->assertSame( "Value", $forUpdate->getValue() );

        $cache->set("unitTest_key", "Changed");

        $forUpdate->setIfSame("New Value");
        $this->assertSame("Changed", $cache->get("unitTest_key"));
    }

}

?>