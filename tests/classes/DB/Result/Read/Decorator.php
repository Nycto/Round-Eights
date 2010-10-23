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

require_once rtrim( __DIR__, "/" ) ."/../../../../general.php";

/**
 * unit tests
 */
class classes_DB_Result_Read_Decorator extends PHPUnit_Framework_TestCase
{

    /**
     * Returns an instance of the Read Decorator
     *
     * @return \r8\DB\Result\Read\Decorator
     */
    public function getTestDecorator ( $decorated )
    {
        return $this->getMock(
            'r8\DB\Result\Read\Decorator',
            array( "_mock" ),
            array( $decorated )
        );
    }

    public function testGetDecorated ()
    {
        $result = $this->getMock('r8\iface\DB\Result\Read');

        $dec = $this->getTestDecorator( $result );

        $this->assertSame( $result, $dec->getDecorated() );
    }

    public function testHasResult ()
    {
        $result = $this->getMock('r8\iface\DB\Result\Read');
        $result->expects( $this->once() )
            ->method('hasResult')
            ->will( $this->returnValue(TRUE) );

        $dec = $this->getTestDecorator( $result );

        $this->assertTrue( $dec->hasResult() );
    }

    public function testCount ()
    {
        $result = $this->getMock('r8\iface\DB\Result\Read');
        $result->expects( $this->exactly( 2 ) )
            ->method('count')
            ->will( $this->returnValue(50) );

        $dec = $this->getTestDecorator( $result );

        $this->assertSame( 50, $dec->count() );
        $this->assertSame( 50, count( $dec ) );
    }

    public function testGetFields ()
    {
        $result = $this->getMock('r8\iface\DB\Result\Read');
        $result->expects( $this->once() )
            ->method('getFields')
            ->will( $this->returnValue( array("field") ) );

        $dec = $this->getTestDecorator( $result );

        $this->assertSame( array("field"), $dec->getFields() );
    }

    public function testIsField ()
    {
        $result = $this->getMock('r8\iface\DB\Result\Read');
        $result->expects( $this->once() )
            ->method('isField')
            ->with( $this->equalTo("fldName") )
            ->will( $this->returnValue( TRUE ) );

        $dec = $this->getTestDecorator( $result );

        $this->assertTrue( $dec->isField( "fldName" ) );
    }

    public function testFieldCount ()
    {
        $result = $this->getMock('r8\iface\DB\Result\Read');
        $result->expects( $this->once() )
            ->method('fieldCount')
            ->will( $this->returnValue(50) );

        $dec = $this->getTestDecorator( $result );

        $this->assertSame( 50, $dec->fieldCount() );
    }

    public function testCurrent ()
    {
        $result = $this->getMock('r8\iface\DB\Result\Read');
        $result->expects( $this->once() )
            ->method('current')
            ->will( $this->returnValue( array("results") ) );

        $dec = $this->getTestDecorator( $result );

        $this->assertSame( array("results"), $dec->current() );
    }

    public function testValid ()
    {
        $result = $this->getMock('r8\iface\DB\Result\Read');
        $result->expects( $this->once() )
            ->method('valid')
            ->will( $this->returnValue( TRUE ) );

        $dec = $this->getTestDecorator( $result );

        $this->assertTrue( $dec->valid() );
    }

    public function testNext ()
    {
        $result = $this->getMock('r8\iface\DB\Result\Read');
        $result->expects( $this->once() )
            ->method('next');

        $dec = $this->getTestDecorator( $result );

        $this->assertSame( $dec, $dec->next() );
    }

    public function testKey ()
    {
        $result = $this->getMock('r8\iface\DB\Result\Read');
        $result->expects( $this->once() )
            ->method('key')
            ->will( $this->returnValue(50) );

        $dec = $this->getTestDecorator( $result );

        $this->assertSame( 50, $dec->key() );
    }

    public function testRewind ()
    {
        $result = $this->getMock('r8\iface\DB\Result\Read');
        $result->expects( $this->once() )
            ->method('rewind');

        $dec = $this->getTestDecorator( $result );

        $this->assertSame( $dec, $dec->rewind() );
    }

    public function testSeek ()
    {
        $result = $this->getMock('r8\iface\DB\Result\Read');
        $result->expects( $this->once() )
            ->method('seek')
            ->with(
                $this->equalTo(20),
                $this->equalTo( \r8\num\OFFSET_RESTRICT )
            );

        $dec = $this->getTestDecorator( $result );

        $this->assertSame( $dec, $dec->seek( 20, \r8\num\OFFSET_RESTRICT ) );
    }

    public function testFree ()
    {
        $result = $this->getMock('r8\iface\DB\Result\Read');
        $result->expects( $this->once() )
            ->method('free');

        $dec = $this->getTestDecorator( $result );

        $this->assertSame( $dec, $dec->free() );
    }

    public function testGetQuery ()
    {
        $result = $this->getMock('r8\iface\DB\Result\Read');
        $result->expects( $this->once() )
            ->method('getQuery')
            ->will( $this->returnValue( "SELECT 1 + 1" ) );

        $dec = $this->getTestDecorator( $result );

        \r8\Test\Constraint\SQL::assert(
            "SELECT 1 + 1",
            $result->getQuery()
        );
    }

}

