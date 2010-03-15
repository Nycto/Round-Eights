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

    public function testConstruct ()
    {
        $sem = new \r8\SysV\Semaphore( "Test" );
        $this->assertSame( 231240019, $sem->getKey() );
        $this->assertSame( 1, $sem->getMax() );


        $sem = new \r8\SysV\Semaphore( new \r8\Seed("Test") );
        $this->assertSame( 1271194237, $sem->getKey() );
        $this->assertSame( 1, $sem->getMax() );


        $sem = new \r8\SysV\Semaphore( 11235813, 20 );
        $this->assertSame( 11235813, $sem->getKey() );
        $this->assertSame( 20, $sem->getMax() );
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

}

?>