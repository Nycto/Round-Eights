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
class classes_SysV_SharedMem extends PHPUnit_Framework_TestCase
{

    /**
     * A SharedMem object that can be used for testing
     *
     * @var \r8\SysV\SharedMem;
     */
    private $shm;

    /**
     * Sets up the environment for this test
     *
     * @return NULL
     */
    public function setUp ()
    {
        if ( !extension_loaded( 'sysvshm' ) )
            $this->markTestSkipped( "SysVShm Extension is not loaded" );
    }

    /**
     * Tears down the environment after this test is run
     *
     * @return NULL
     */
    public function tearDown ()
    {
        if ( isset($this->shm) )
            $this->shm->expunge();
    }

    /**
     * Returns a mock semaphore object
     *
     * @return \r8\SysV\Semaphore
     */
    public function getTestSharedMem ()
    {
        if ( !isset($this->shm) ) {
            $this->shm = new \r8\SysV\SharedMem(
                "UnitTests",
                50000
            );
        }
        return $this->shm;
    }

    public function testAccess ()
    {
        $mem = $this->getTestSharedMem();

        $this->assertFalse( $mem->exists("one") );
        $this->assertNull( $mem->get("one") );

        $this->assertSame( $mem, $mem->set("one", "Chunk of Data") );
        $this->assertTrue( $mem->exists("one") );
        $this->assertSame( "Chunk of Data", $mem->get("one") );

        $this->assertSame( $mem, $mem->clear("one") );
        $this->assertFalse( $mem->exists("one") );
        $this->assertNull( $mem->get("one") );
    }

    public function testAccess_Types ()
    {
        $mem = $this->getTestSharedMem();

        $obj = new stdClass;
        $obj->one = "first";
        $obj->two = "twice";

        $this->assertSame( $mem, $mem->set("null", NULL) );
        $this->assertSame( $mem, $mem->set("true", TRUE) );
        $this->assertSame( $mem, $mem->set("false", FALSE) );
        $this->assertSame( $mem, $mem->set("int", 1234) );
        $this->assertSame( $mem, $mem->set("flt", 1.234) );
        $this->assertSame( $mem, $mem->set("str", "Data") );
        $this->assertSame( $mem, $mem->set("ary", array(1,2,3)) );
        $this->assertSame( $mem, $mem->set("obj", $obj) );

        $this->assertNull( $mem->get("null") );
        $this->assertTrue( $mem->get("true") );
        $this->assertFalse( $mem->get("false") );
        $this->assertSame( 1234, $mem->get("int") );
        $this->assertSame( 1.234, $mem->get("flt") );
        $this->assertSame( "Data", $mem->get("str") );
        $this->assertSame( array(1,2,3), $mem->get("ary") );
        $this->assertEquals( $obj, $mem->get("obj") );
    }

    public function testExpunge ()
    {
        $mem = $this->getTestSharedMem();
        $this->assertSame( $mem, $mem->set("one", "Chunk of Data") );
        $this->assertSame( $mem, $mem->set("two", "More Data") );

        $this->assertSame( $mem, $mem->expunge() );

        $this->assertFalse( $mem->exists("one") );
        $this->assertFalse( $mem->exists("two") );
    }

    public function testSerialize ()
    {
        $mem = $this->getTestSharedMem();

        // Set a value to ensure the resource is open
        $mem->set("data", "value");

        $serial = serialize($mem);
        $this->assertNotContains("resource", $serial);

        $copy = unserialize( $serial );
        $this->assertThat( $copy, $this->isInstanceOf( '\r8\SysV\SharedMem' ) );
        $this->assertSame( $mem->getKey(), $copy->getKey() );
        $this->assertSame( $mem->getSize(), $copy->getSize() );
    }

}

