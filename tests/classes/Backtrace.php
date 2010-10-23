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
            array('visit', 'getResolvedName'),
            array( '/path/example.php' )
        );
    }

    public function testPushEvent ()
    {
        $backtrace = new \r8\Backtrace;
        $this->assertSame( array(), $backtrace->getEvents() );

        $event1 = $this->getMockEvent();
        $this->assertSame( $backtrace, $backtrace->pushEvent( $event1 ) );
        $this->assertSame( array($event1), $backtrace->getEvents() );

        $event2 = $this->getMockEvent();
        $this->assertSame( $backtrace, $backtrace->pushEvent( $event2 ) );
        $this->assertSame( array($event1, $event2), $backtrace->getEvents() );

        $this->assertSame( $backtrace, $backtrace->pushEvent( $event1 ) );
        $this->assertSame( array($event1, $event2), $backtrace->getEvents() );
    }

    public function testUnshiftEvent ()
    {
        $backtrace = new \r8\Backtrace;
        $this->assertSame( array(), $backtrace->getEvents() );

        $event1 = $this->getMockEvent();
        $this->assertSame( $backtrace, $backtrace->unshiftEvent( $event1 ) );
        $this->assertSame( array($event1), $backtrace->getEvents() );

        $event2 = $this->getMockEvent();
        $this->assertSame( $backtrace, $backtrace->unshiftEvent( $event2 ) );
        $this->assertSame( array($event2, $event1), $backtrace->getEvents() );

        $this->assertSame( $backtrace, $backtrace->unshiftEvent( $event1 ) );
        $this->assertSame( array($event2, $event1), $backtrace->getEvents() );
    }

    public function testVisit ()
    {
        $backtrace = new \r8\Backtrace;

        $visitor = $this->getMock('\r8\iface\Backtrace\Visitor');

        $event1 = $this->getMockEvent();
        $event1->expects( $this->once() )
            ->method( "visit" )
            ->with( $this->equalTo($visitor) );
        $backtrace->pushEvent( $event1 );

        $event2 = $this->getMockEvent();
        $event2->expects( $this->once() )
            ->method( "visit" )
            ->with( $this->equalTo($visitor) );
        $backtrace->pushEvent( $event2 );

        $this->assertSame( $visitor, $backtrace->visit($visitor) );
    }

    public function testCount ()
    {
        $backtrace = new \r8\Backtrace;
        $this->assertSame( 0, count($backtrace) );

        $backtrace->pushEvent( $this->getMockEvent() );
        $this->assertSame( 1, count($backtrace) );

        $backtrace->pushEvent( $this->getMockEvent() );
        $this->assertSame( 2, count($backtrace) );

        $backtrace->pushEvent( $this->getMockEvent() );
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

        $backtrace->pushEvent( $events[0] );
        $backtrace->pushEvent( $events[1] );
        $backtrace->pushEvent( $events[2] );

        \r8\Test\Constraint\Iterator::assert( $events, $backtrace );
    }

    public function testGetEvent ()
    {
        $backtrace = new \r8\Backtrace;

        $events = array(
            $this->getMockEvent(),
            $this->getMockEvent(),
            $this->getMockEvent()
        );

        $backtrace->pushEvent( $events[0] );
        $backtrace->pushEvent( $events[1] );
        $backtrace->pushEvent( $events[2] );

        $this->assertSame( $events[0], $backtrace->getEvent(0) );
        $this->assertSame( $events[1], $backtrace->getEvent(1) );
        $this->assertSame( $events[2], $backtrace->getEvent(2) );
        $this->assertSame( $events[2], $backtrace->getEvent(-1) );
    }

    public function testPopEvent ()
    {
        $backtrace = new \r8\Backtrace;

        $events = array(
            $this->getMockEvent(),
            $this->getMockEvent(),
            $this->getMockEvent()
        );

        $backtrace->pushEvent( $events[0] );
        $backtrace->pushEvent( $events[1] );
        $backtrace->pushEvent( $events[2] );

        $this->assertSame( 3, $backtrace->count() );
        $this->assertSame( $backtrace, $backtrace->popEvent() );

        $this->assertSame(
            array( $events[1], $events[2] ),
            $backtrace->getEvents()
        );
    }

    public function testFrom ()
    {
        $backtrace = \r8\Backtrace::from( array (
            array ( 'file' => '/tmp/backtrace.php', 'line' => 11,
                'function' => '{closure}', 'args' => array () ),
            array ( 'function' => 'stat', 'class' => 'tmp',
                'type' => '::', 'args' => array () ),
            array ( 'file' => '/tmp/backtrace.php', 'line' => 24,
                'function' => 'meth', 'class' => 'tmp', 'type' => '->',
                'args' => array () ),
            array ( 'file' => '/tmp/backtrace.php', 'line' => 27,
                'function' => 'execute', 'args' => array () ),
        ) );

        $this->assertThat( $backtrace, $this->isInstanceOf('\r8\Backtrace') );

        $this->assertThat(
            $backtrace->getEvent(0),
            $this->isInstanceOf('\r8\Backtrace\Event\Closure')
        );

        $this->assertThat(
            $backtrace->getEvent(1),
            $this->isInstanceOf('\r8\Backtrace\Event\StaticMethod')
        );

        $this->assertThat(
            $backtrace->getEvent(2),
            $this->isInstanceOf('\r8\Backtrace\Event\Method')
        );

        $this->assertThat(
            $backtrace->getEvent(3),
            $this->isInstanceOf('\r8\Backtrace\Event\Func')
        );

        $this->assertThat(
            $backtrace->getEvent(4),
            $this->isInstanceOf('\r8\Backtrace\Event\Main')
        );
    }

    public function testCreate ()
    {
        $trace = \r8\Backtrace::create();

        $this->assertThat( $trace, $this->isInstanceOf('\r8\Backtrace') );
        $this->assertGreaterThan( 1, count($trace) );

        $this->assertThat(
            $trace->getEvent(-1),
            $this->isInstanceOf('\r8\Backtrace\Event\Main')
        );

        $first = $trace->getEvent(0);
        $this->assertThat( $first, $this->isInstanceOf('r8\Backtrace\Event\Method') );
        $this->assertSame( __CLASS__, $first->getClass() );
        $this->assertSame( __FUNCTION__, $first->getName() );
    }

    public function testDump ()
    {
        ob_start();
        \r8\Backtrace::dump();
        $result = ob_get_clean();

        $this->assertGreaterThan( 0, strlen($result) );
        $this->assertContains("testDump", $result);
        $this->assertNotContains('\r8\Backtrace', $result);
    }

}

