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
class classes_SysV_Semaphore extends PHPUnit_Framework_TestCase
{

    public function testMakeKey ()
    {
        $this->assertSame( 231240019, \r8\SysV\Semaphore::makeKey("Test") );
        $this->assertSame( 1271194237, \r8\SysV\Semaphore::makeKey(new \r8\Seed("Test")) );
        $this->assertSame( 11235813, \r8\SysV\Semaphore::makeKey(11235813) );
    }

    public function testLock ()
    {
        $sem = new \r8\SysV\Semaphore( "UnitTest", 20 );
        $this->assertFalse( $sem->isLocked() );

        $this->assertSame( $sem, $sem->lock() );
        $this->assertTrue( $sem->isLocked() );

        $this->assertSame( $sem, $sem->lock() );
        $this->assertTrue( $sem->isLocked() );

        $this->assertSame( $sem, $sem->unlock() );
        $this->assertFalse( $sem->isLocked() );

        $this->assertSame( $sem, $sem->unlock() );
        $this->assertFalse( $sem->isLocked() );
    }

    public function testSerialize ()
    {
        $sem = new \r8\SysV\Semaphore( "UnitTest", 20 );

        $serial = serialize( $sem );

        $this->assertNotContains( "resource", $serial );
        $this->assertNotContains( "locked", $serial );

        $unserial = unserialize( $serial );

        $this->assertThat( $unserial, $this->isInstanceOf( '\r8\SysV\Semaphore' ) );
        $this->assertFalse( $sem->isLocked() );
        $this->assertSame( 2097006524, $sem->getKey() );
        $this->assertSame( 20, $sem->getMax() );
    }

    public function testSynchronize ()
    {
        $sem = new \r8\SysV\Semaphore( "UnitTest", 20 );

        $obj = $this->getMock('stdClass', array('__invoke'));
        $obj->expects( $this->once() )->method( "__invoke" );

        $this->assertSame( $sem, $sem->synchronize($obj) );
        $this->assertFalse( $sem->isLocked() );
    }

    public function testSynchronize_PreLocked ()
    {
        $sem = new \r8\SysV\Semaphore( "UnitTest", 20 );

        $obj = $this->getMock('stdClass', array('__invoke'));
        $obj->expects( $this->once() )->method( "__invoke" );

        $sem->lock();
        $this->assertSame( $sem, $sem->synchronize($obj) );
        $this->assertTrue( $sem->isLocked() );
    }

    public function testSynchronize_Uncallable ()
    {
        $sem = new \r8\SysV\Semaphore( "UnitTest", 20 );

        try {
            $sem->synchronize( "Not a callable method" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {}
    }

    public function testSynchronize_Exception ()
    {
        $sem = new \r8\SysV\Semaphore( "UnitTest", 20 );

        try {
            $sem->synchronize(function () {
                throw new Exception("Error!");
            });
            $this->fail("An expected exception was not thrown");
        }
        catch ( Exception $err ) {
            $this->assertSame( "Error!", $err->getMessage() );
        }

        $this->assertFalse( $sem->isLocked() );
    }

}

?>