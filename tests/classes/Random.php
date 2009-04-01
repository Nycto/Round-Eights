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

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * unit tests
 */
class classes_random extends PHPUnit_Framework_TestCase
{

    public function testNextFloat ()
    {
        $random = $this->getMock('cPHP\Random', array('nextInteger'));

        $random->expects( $this->at(0) )
            ->method('nextInteger')
            ->will( $this->returnValue(1975807251) );

        $random->expects( $this->at(1) )
            ->method('nextInteger')
            ->will( $this->returnValue(0) );

        $random->expects( $this->at(2) )
            ->method('nextInteger')
            ->will( $this->returnValue(0x7fffffff) );

        $this->assertSame( 0.92005694840106, $random->nextFloat() );
        $this->assertSame( 0.0, $random->nextFloat() );
        $this->assertSame( 1.0, $random->nextFloat() );
    }

    public function testNextString ()
    {
        $random = $this->getMock('cPHP\Random', array('nextInteger'));

        $random->expects( $this->at(0) )
            ->method('nextInteger')
            ->will( $this->returnValue(1975807251) );

        $random->expects( $this->at(1) )
            ->method('nextInteger')
            ->will( $this->returnValue(0) );

        $random->expects( $this->at(2) )
            ->method('nextInteger')
            ->will( $this->returnValue(0x7fffffff) );

        $this->assertSame( "7208e3163c84d34018b1fd6d6384a363aef549b6", $random->nextString() );
        $this->assertSame( "b6589fc6ab0dc82cf12099d1c2d40ab994e8410c", $random->nextString() );
        $this->assertSame( "75878664309b1e12165b0823315376b0d27e4f80", $random->nextString() );
    }

    public function testNextRange ()
    {
        $random = $this->getMock('cPHP\Random', array('nextInteger'));

        $random->expects( $this->at(0) )
            ->method('nextInteger')
            ->will( $this->returnValue(1975807251) );

        $random->expects( $this->at(1) )
            ->method('nextInteger')
            ->will( $this->returnValue(0) );

        $random->expects( $this->at(2) )
            ->method('nextInteger')
            ->will( $this->returnValue(0x7fffffff) );

        $this->assertSame( 12, $random->nextRange(0, 20) );
        $this->assertSame( 6, $random->nextRange(5, 10) );
        $this->assertSame( 134, $random->nextRange(100, 200) );
    }

}

?>