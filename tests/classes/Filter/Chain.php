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

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_filter_chain extends PHPUnit_Framework_TestCase
{

    public function testAdd ()
    {
        $mock = $this->getMock("cPHP\iface\Filter", array("filter"));
        $mock2 = $this->getMock("cPHP\iface\Filter", array("filter"));

        $filter = new \cPHP\Filter\Chain;
        $this->assertSame( $filter, $filter->add( $mock ) );

        $list = $filter->get();
        $this->assertThat( $list, $this->isInstanceOf("cPHP\Ary") );
        $this->assertEquals( 1, count($list) );
        $this->assertSame( $mock, $list[0] );

        $this->assertSame( $filter, $filter->add( $mock2 ) );

        $list = $filter->get();
        $this->assertThat( $list, $this->isInstanceOf("cPHP\Ary") );
        $this->assertEquals( 2, count($list) );
        $this->assertSame( array($mock, $mock2), $list->get() );
    }

    public function testConstruct ()
    {
        $mock = $this->getMock("cPHP\iface\Filter", array("filter"));
        $mock2 = $this->getMock("cPHP\iface\Filter", array("filter"));

        $filter = new \cPHP\Filter\Chain( $mock, $mock2 );

        $list = $filter->get();
        $this->assertThat( $list, $this->isInstanceOf("cPHP\Ary") );
        $this->assertEquals( 2, count($list) );
        $this->assertSame( array($mock, $mock2), $list->get() );
    }

    public function testClear ()
    {
        $mock = $this->getMock("cPHP\iface\Filter", array("filter"));
        $mock2 = $this->getMock("cPHP\iface\Filter", array("filter"));

        $filter = new \cPHP\Filter\Chain( $mock, $mock2 );

        $list = $filter->get();
        $this->assertThat( $list, $this->isInstanceOf("cPHP\Ary") );
        $this->assertEquals( 2, count($list) );
        $this->assertSame( array($mock, $mock2), $list->get() );

        $this->assertSame( $filter, $filter->clear() );

        $list = $filter->get();
        $this->assertThat( $list, $this->isInstanceOf("cPHP\Ary") );
        $this->assertEquals( 0, count($list) );
    }

    public function testFilter ()
    {
        $mock = $this->getMock("cPHP\iface\Filter", array("filter"));

        $mock->expects($this->once())
            ->method('filter')
            ->with($this->equalTo('Input Value'))
            ->will($this->returnValue('Filtered Value'));

        $filter = new \cPHP\Filter\Chain( $mock );

        $this->assertEquals( "Filtered Value", $filter->filter('Input Value') );
    }

    public function testChaining ()
    {
        $mock = $this->getMock("cPHP\iface\Filter", array("filter"));

        $mock->expects($this->once())
            ->method('filter')
            ->with($this->equalTo('Input Value'))
            ->will($this->returnValue('Result From One'));


        $mock2 = $this->getMock("cPHP\iface\Filter", array("filter"));

        $mock2->expects($this->once())
            ->method('filter')
            ->with($this->equalTo('Result From One'))
            ->will($this->returnValue('Result From Two'));


        $mock3 = $this->getMock("cPHP\iface\Filter", array("filter"));

        $mock3->expects($this->once())
            ->method('filter')
            ->with($this->equalTo('Result From Two'))
            ->will($this->returnValue('Result From Three'));


        $filter = new \cPHP\Filter\Chain( $mock, $mock2, $mock3 );

        $this->assertEquals( 'Result From Three', $filter->filter('Input Value') );
    }

}

?>