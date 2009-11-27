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
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * unit tests
 */
class classes_Backtrace extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a test event object
     *
     * @return \r8\Backtrace\Event
     */
    public function getMockEvent ()
    {
        return $this->getMock(
        	'\r8\Backtrace\Event',
            array('visit'),
            array( '/path/example.php' )
        );
    }

    public function testAddEvent ()
    {
        $backtrace = new \r8\Backtrace;
        $this->assertSame( array(), $backtrace->getEvents() );

        $event1 = $this->getMockEvent();
        $this->assertSame( $backtrace, $backtrace->addEvent( $event1 ) );
        $this->assertSame( array($event1), $backtrace->getEvents() );

        $event2 = $this->getMockEvent();
        $this->assertSame( $backtrace, $backtrace->addEvent( $event2 ) );
        $this->assertSame( array($event1, $event2), $backtrace->getEvents() );

        $this->assertSame( $backtrace, $backtrace->addEvent( $event1 ) );
        $this->assertSame( array($event1, $event2), $backtrace->getEvents() );
    }

    public function testVisit ()
    {
        $backtrace = new \r8\Backtrace;

        $visitor = $this->getMock('\r8\iface\Backtrace\Visitor');

        $event1 = $this->getMockEvent();
        $event1->expects( $this->once() )
            ->method( "visit" )
            ->with( $this->equalTo($visitor) );
        $backtrace->addEvent( $event1 );

        $event2 = $this->getMockEvent();
        $event2->expects( $this->once() )
            ->method( "visit" )
            ->with( $this->equalTo($visitor) );
        $backtrace->addEvent( $event2 );

        $this->assertSame( $visitor, $backtrace->visit($visitor) );
    }

    public function testCount ()
    {
        $backtrace = new \r8\Backtrace;
        $this->assertSame( 0, count($backtrace) );

        $backtrace->addEvent( $this->getMockEvent() );
        $this->assertSame( 1, count($backtrace) );

        $backtrace->addEvent( $this->getMockEvent() );
        $this->assertSame( 2, count($backtrace) );

        $backtrace->addEvent( $this->getMockEvent() );
        $this->assertSame( 3, count($backtrace) );
    }

    public function testIterator ()
    {
        $backtrace = new \r8\Backtrace;

        $events = array(
            $this->getMockEvent(),
            $this->getMockEvent(),
            $this->getMockEvent()
        );

        $backtrace->addEvent( $events[0] );
        $backtrace->addEvent( $events[1] );
        $backtrace->addEvent( $events[2] );

        PHPUnit_Framework_Constraint_Iterator::assert( $events, $backtrace );
    }

    public function testFrom ()
    {
        $backtrace = \r8\Backtrace::from( array (
            array ( 'file' => '/tmp/backtrace.php', 'line' => 11,
    			'function' => '{closure}', 'args' => array () ),
            array ( 'file' => '/tmp/backtrace.php', 'line' => 16,
    			'function' => 'stat', 'class' => 'tmp', 'type' => '::',
    			'args' => array () ),
            array ( 'file' => '/tmp/backtrace.php', 'line' => 24,
    			'function' => 'meth', 'class' => 'tmp', 'type' => '->',
    			'args' => array () ),
            array ( 'file' => '/tmp/backtrace.php', 'line' => 27,
    			'function' => 'execute', 'args' => array () ),
        ) );

        $this->assertThat( $backtrace, $this->isInstanceOf('\r8\Backtrace') );

        $events = PHPUnit_Framework_Constraint_Iterator::iteratorToArray( 5, $backtrace );

        $this->assertArrayHasKey( 0, $events );
        $this->assertArrayHasKey( 1, $events );
        $this->assertArrayHasKey( 2, $events );
        $this->assertArrayHasKey( 3, $events );
        $this->assertArrayHasKey( 4, $events );

        $this->assertThat( $events[0], $this->isInstanceOf('\r8\Backtrace\Event\Closure') );
        $this->assertThat( $events[1], $this->isInstanceOf('\r8\Backtrace\Event\StaticMethod') );
        $this->assertThat( $events[2], $this->isInstanceOf('\r8\Backtrace\Event\Method') );
        $this->assertThat( $events[3], $this->isInstanceOf('\r8\Backtrace\Event\Func') );
        $this->assertThat( $events[4], $this->isInstanceOf('\r8\Backtrace\Event\Main') );
    }

}

?>