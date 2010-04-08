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
class classes_DB_Result_Read_AutoFree extends PHPUnit_Framework_TestCase
{

    public function testValid_True ()
    {
        $wrapped = $this->getMock('\r8\iface\DB\Result\Read');
        $wrapped->expects( $this->once() )
            ->method( "valid" )
            ->will( $this->returnValue( TRUE ) );
        $wrapped->expects( $this->never() )
            ->method( "free" );

        $auto = new \r8\DB\Result\Read\AutoFree( $wrapped );

        $this->assertTrue( $auto->valid() );
    }

    public function testValid_False ()
    {
        $wrapped = $this->getMock('\r8\iface\DB\Result\Read');
        $wrapped->expects( $this->once() )
            ->method( "valid" )
            ->will( $this->returnValue( FALSE ) );
        $wrapped->expects( $this->once() )
            ->method( "free" );

        $auto = new \r8\DB\Result\Read\AutoFree( $wrapped );

        $this->assertFalse( $auto->valid() );
    }

    public function testIterate ()
    {
        $result = new \r8\DB\Result\Read\AutoFree(
            new \r8\DB\Result\Read(
                new \r8\DB\BlackHole\Result(
                    array( 1, 2 ),
                    array( 3, 4 )
                ),
                "SELECT 1"
            )
        );

        \r8\Test\Constraint\Iterator::assert(
            array( array( 1, 2 ), array( 3, 4 ) ),
            $result
        );

        \r8\Test\Constraint\Iterator::assert( array(), $result );
    }

}

?>