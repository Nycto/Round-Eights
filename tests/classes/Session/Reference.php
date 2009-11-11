<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of RaindropPHP.
 *
 * RaindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * RaindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with RaindropPHP. If not, see <http://www.RaindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <James@RaindropPHP.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_Session_Reference extends PHPUnit_Framework_TestCase
{

    public function testGet ()
    {
        $obj = new stdClass;
        $data = array( "key" => "Data", "key2" => $obj );
        $sess = new \h2o\Session\Reference( $data );

        $this->assertSame( "Data", $sess->get( "key" ) );
        $this->assertSame( $obj, $sess->get( "key2" ) );
        $this->assertNull( $sess->get("none") );
    }

    public function testSet ()
    {
        $data = array();
        $sess = new \h2o\Session\Reference( $data );

        $this->assertSame( $sess, $sess->set("key", "data") );
        $this->assertSame( array( "key" => "data" ), $data );

        $obj = new stdClass;
        $this->assertSame( $sess, $sess->set("key2", $obj) );
        $this->assertSame( array( "key" => "data", "key2" => $obj ), $data );
    }

    public function testExists ()
    {
        $data = array( "key" => "Data", "key2" => NULL );
        $sess = new \h2o\Session\Reference( $data );

        $this->assertTrue( $sess->exists( "key" ) );
        $this->assertFalse( $sess->exists( "key2" ) );
        $this->assertFalse( $sess->exists("none") );
    }

    public function testClear ()
    {
        $data = array( "key" => "Data", "key2" => NULL );
        $sess = new \h2o\Session\Reference( $data );

        $this->assertSame( $sess, $sess->clear( "key2" ) );
        $this->assertSame( array( "key" => "Data" ), $data );

        $this->assertSame( $sess, $sess->clear( "key2" ) );
        $this->assertSame( array( "key" => "Data" ), $data );

        $this->assertSame( $sess, $sess->clear( "key" ) );
        $this->assertSame( array(), $data );
    }

    public function testClearAll ()
    {
        $data = array( "key" => "Data", "key2" => NULL );
        $sess = new \h2o\Session\Reference( $data );

        $this->assertSame( $sess, $sess->clearAll() );
        $this->assertSame( array(), $data );

        $this->assertSame( $sess, $sess->set("key", "data") );
        $this->assertSame( array( "key" => "data" ), $data );
    }

    public function testGetAll ()
    {
        $data = array( "key" => "Data" );
        $sess = new \h2o\Session\Reference( $data );

        $this->assertSame( array( "key" => "Data" ), $sess->getAll() );
    }

}

?>