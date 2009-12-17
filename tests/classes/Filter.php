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

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * unit tests
 */
class classes_filter extends PHPUnit_Framework_TestCase
{

    public function testInvoke ()
    {

        $mock = $this->getMock("r8\Filter", array("filter"));

        $mock->expects($this->once())
            ->method('filter')
            ->with($this->equalTo('Input Value'))
            ->will($this->returnValue('Filtered Value'));

        $this->assertEquals( "Filtered Value", $mock('Input Value') );

    }

    public function testCallStatic ()
    {
        $filter = \r8\Filter::StandardEmpty();
        $this->assertThat( $filter, $this->isInstanceOf("r8\Filter\StandardEmpty") );
        $this->assertEquals( 0, $filter->getFlags() );
        $this->assertNull( $filter->getValue() );

        $filter = \r8\Filter::StandardEmpty( "Empty Value" );
        $this->assertThat( $filter, $this->isInstanceOf("r8\Filter\StandardEmpty") );
        $this->assertEquals( 0, $filter->getFlags() );
        $this->assertEquals( "Empty Value", $filter->getValue() );

        $filter = \r8\Filter::StandardEmpty( "Empty Value", 5 );
        $this->assertThat( $filter, $this->isInstanceOf("r8\Filter\StandardEmpty") );
        $this->assertEquals( 5, $filter->getFlags() );
        $this->assertEquals( "Empty Value", $filter->getValue() );
    }

}

?>