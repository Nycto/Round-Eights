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
class classes_Filter_Chain extends PHPUnit_Framework_TestCase
{

    public function testAdd ()
    {
        $mock = $this->getMock("r8\iface\Filter", array("filter"));
        $mock2 = $this->getMock("r8\iface\Filter", array("filter"));

        $filter = new \r8\Filter\Chain;
        $this->assertSame( $filter, $filter->add( $mock ) );
        $this->assertSame( array($mock), $filter->getFilters() );

        $this->assertSame( $filter, $filter->add( $mock2 ) );
        $this->assertSame( array($mock, $mock2), $filter->getFilters() );
    }

    public function testConstruct ()
    {
        $mock = $this->getMock("r8\iface\Filter", array("filter"));
        $mock2 = $this->getMock("r8\iface\Filter", array("filter"));

        $filter = new \r8\Filter\Chain( $mock, $mock2 );
        $this->assertSame( array($mock, $mock2), $filter->getFilters() );
    }

    public function testClear ()
    {
        $mock = $this->getMock("r8\iface\Filter", array("filter"));
        $mock2 = $this->getMock("r8\iface\Filter", array("filter"));

        $filter = new \r8\Filter\Chain( $mock, $mock2 );

        $this->assertSame( array($mock, $mock2), $filter->getFilters() );

        $this->assertSame( $filter, $filter->clear() );
        $this->assertSame( array(), $filter->getFilters() );
    }

    public function testFilter ()
    {
        $mock = $this->getMock("r8\iface\Filter", array("filter"));

        $mock->expects($this->once())
            ->method('filter')
            ->with($this->equalTo('Input Value'))
            ->will($this->returnValue('Filtered Value'));

        $filter = new \r8\Filter\Chain( $mock );

        $this->assertEquals( "Filtered Value", $filter->filter('Input Value') );
    }

    public function testChaining ()
    {
        $mock = $this->getMock("r8\iface\Filter", array("filter"));

        $mock->expects($this->once())
            ->method('filter')
            ->with($this->equalTo('Input Value'))
            ->will($this->returnValue('Result From One'));


        $mock2 = $this->getMock("r8\iface\Filter", array("filter"));

        $mock2->expects($this->once())
            ->method('filter')
            ->with($this->equalTo('Result From One'))
            ->will($this->returnValue('Result From Two'));


        $mock3 = $this->getMock("r8\iface\Filter", array("filter"));

        $mock3->expects($this->once())
            ->method('filter')
            ->with($this->equalTo('Result From Two'))
            ->will($this->returnValue('Result From Three'));


        $filter = new \r8\Filter\Chain( $mock, $mock2, $mock3 );

        $this->assertEquals( 'Result From Three', $filter->filter('Input Value') );
    }

}

