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
class classes_Backtrace_Formatter extends PHPUnit_Framework_TestCase
{

    /**
     * Reeturns a test backtrace
     *
     * @return \r8\Backtrace
     */
    public function getTestBacktrace ()
    {
        $backtrace = new \r8\Backtrace;
        $backtrace->pushEvent( new \r8\Backtrace\Event\Closure(
            '/path/example.php', 11, array( "arg1", "arg2" )
        ));
        $backtrace->pushEvent( new \r8\Backtrace\Event\StaticMethod(
            'test', 'stat', '/path/example.php', 24, array()
        ));
        $backtrace->pushEvent( new \r8\Backtrace\Event\Method(
            'test', 'meth', '/path/example.php', 27, array()
        ));
        $backtrace->pushEvent( new \r8\Backtrace\Event\Func( 'array_map' ) );
        $backtrace->pushEvent( new \r8\Backtrace\Event\Main( '/path/example.php' ));

        return $backtrace;
    }

    /**
     * Returns a mock event
     *
     * @return \r8\Backtrace\Event
     */
    public function getMockEvent ( $resolved, array $args = array(), $file = NULL, $line = NULL )
    {
        $event = $this->getMock('\r8\Backtrace\Event');
        $event->expects( $this->any() )
            ->method( 'getResolvedName' )
            ->will( $this->returnValue($resolved) );
        $event->expects( $this->any() )
            ->method( 'getArgs' )
            ->will( $this->returnValue($args) );
        $event->expects( $this->any() )
            ->method( 'getFile' )
            ->will( $this->returnValue($file) );
        $event->expects( $this->any() )
            ->method( 'getLine' )
            ->will( $this->returnValue($line) );
        return $event;
    }

    /**
     * Returns a mock Main event
     *
     * @return \r8\Backtrace\Event\Main
     */
    public function getMockMain ( $file )
    {
        $event = $this->getMock('\r8\Backtrace\Event\Main');
        $event->expects( $this->any() )
            ->method( 'getFile' )
            ->will( $this->returnValue($file) );
        return $event;
    }

    public function testFormat ()
    {
        $each = $this->getMock('\r8\iface\Backtrace\Formatter');
        $each->expects( $this->at( 0 ) )
            ->method( 'prefix' )
            ->will( $this->returnValue("[prefix]") );
        $each->expects( $this->at( 1 ) )
            ->method( 'event' )
            ->with(
                $this->equalTo( 2 ), $this->equalTo("function"),
                $this->equalTo( array("arg") ), $this->equalTo("/example/file.php"),
                $this->equalTo( 5050 )
            )
            ->will( $this->returnValue("[event2]") );
        $each->expects( $this->at( 2 ) )
            ->method( 'event' )
            ->with(
                $this->equalTo( 1 ), $this->equalTo("method"),
                $this->equalTo( array( 1234 ) ), $this->equalTo("/example/other.php"),
                $this->equalTo( 1000 )
            )
            ->will( $this->returnValue("[event1]") );
        $each->expects( $this->at( 3 ) )
            ->method( 'main' )
            ->with( $this->equalTo( 0 ), $this->equalTo("/example/main.php") )
            ->will( $this->returnValue("[main]") );
        $each->expects( $this->at( 4 ) )
            ->method( 'suffix' )
            ->will( $this->returnValue("[suffix]") );


        $format = new \r8\Backtrace\Formatter( $each );


        $backtrace = new \r8\Backtrace;
        $backtrace->pushEvent( $this->getMockEvent(
            "function", array( "arg" ), "/example/file.php", 5050
        ));
        $backtrace->pushEvent( $this->getMockEvent(
            "method", array( 1234 ), "/example/other.php", 1000
        ));
        $backtrace->pushEvent(
            $this->getMockMain( "/example/main.php" )
        );


        $this->assertSame(
            "[prefix][event2][event1][main][suffix]",
            $format->format( $backtrace )
        );
    }

}

