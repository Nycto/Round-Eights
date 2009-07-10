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
 * @author James Frasca <james@Raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * unit tests
 */
class classes_filter extends PHPUnit_Framework_TestCase
{

    public function testInvoke ()
    {

        $mock = $this->getMock("h2o\Filter", array("filter"));

        $mock->expects($this->once())
            ->method('filter')
            ->with($this->equalTo('Input Value'))
            ->will($this->returnValue('Filtered Value'));

        $this->assertEquals( "Filtered Value", $mock('Input Value') );

    }

    public function testCallStatic ()
    {
        $filter = \h2o\Filter::StandardEmpty();
        $this->assertThat( $filter, $this->isInstanceOf("h2o\Filter\StandardEmpty") );
        $this->assertEquals( 0, $filter->getFlags() );
        $this->assertNull( $filter->getValue() );

        $filter = \h2o\Filter::StandardEmpty( "Empty Value" );
        $this->assertThat( $filter, $this->isInstanceOf("h2o\Filter\StandardEmpty") );
        $this->assertEquals( 0, $filter->getFlags() );
        $this->assertEquals( "Empty Value", $filter->getValue() );

        $filter = \h2o\Filter::StandardEmpty( "Empty Value", 5 );
        $this->assertThat( $filter, $this->isInstanceOf("h2o\Filter\StandardEmpty") );
        $this->assertEquals( 5, $filter->getFlags() );
        $this->assertEquals( "Empty Value", $filter->getValue() );
    }

}

?>