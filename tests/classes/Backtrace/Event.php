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
class classes_Backtrace_Event extends PHPUnit_Framework_TestCase
{

    public function testFrom_Closure ()
    {
        $result = \r8\Backtrace\Event::from( array(
            'function' => '{closure}', 'file' => '/path.php',
            'line' => 145, 'args' => array( "arg" )
        ));

        $this->assertThat( $result, $this->isInstanceOf('\r8\Backtrace\Event\Closure') );
        $this->assertSame( '/path.php', $result->getFile() );
        $this->assertSame( 145, $result->getLine() );
        $this->assertSame( array( "arg" ), $result->getArgs() );
    }

    public function testFrom_Function ()
    {
        $result = \r8\Backtrace\Event::from( array(
            'function' => 'func_name', 'file' => '/path.php',
            'line' => 145, 'args' => array( "arg" )
        ));

        $this->assertThat( $result, $this->isInstanceOf('\r8\Backtrace\Event\Func') );
        $this->assertSame( 'func_name', $result->getName() );
        $this->assertSame( '/path.php', $result->getFile() );
        $this->assertSame( 145, $result->getLine() );
        $this->assertSame( array( "arg" ), $result->getArgs() );
    }

    public function testFrom_StaticMethod ()
    {
        $result = \r8\Backtrace\Event::from( array(
            'function' => 'func_name', 'file' => '/path.php', 'type' => '::',
            'line' => 145, 'args' => array( "arg" ), 'class' => 'example'
        ));

        $this->assertThat( $result, $this->isInstanceOf('\r8\Backtrace\Event\StaticMethod') );
        $this->assertSame( 'example', $result->getClass() );
        $this->assertSame( 'func_name', $result->getName() );
        $this->assertSame( '/path.php', $result->getFile() );
        $this->assertSame( 145, $result->getLine() );
        $this->assertSame( array( "arg" ), $result->getArgs() );
    }

    public function testFrom_Method ()
    {
        $result = \r8\Backtrace\Event::from( array(
            'function' => 'func_name', 'file' => '/path.php', 'type' => '->',
            'line' => 145, 'args' => array( "arg" ), 'class' => 'example'
        ));

        $this->assertThat( $result, $this->isInstanceOf('\r8\Backtrace\Event\Method') );
        $this->assertSame( 'example', $result->getClass() );
        $this->assertSame( 'func_name', $result->getName() );
        $this->assertSame( '/path.php', $result->getFile() );
        $this->assertSame( 145, $result->getLine() );
        $this->assertSame( array( "arg" ), $result->getArgs() );
    }

    public function testFrom_Invalid ()
    {
        try {
            \r8\Backtrace\Event::from( array( 'class' => "blah" ) );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "Invalid event format", $err->getMessage() );
        }
    }

    public function testConstruct_Empty ()
    {
        $event = $this->getMockForAbstractClass('\r8\Backtrace\Event');
        $this->assertNull( $event->getFile() );
        $this->assertNull( $event->getLine() );
        $this->assertSame( null, $event->getClass() );
        $this->assertSame( null, $event->getName() );
        $this->assertSame( array(), $event->getArgs() );
    }

    public function testConstruct ()
    {
        $event = $this->getMockForAbstractClass(
            '\r8\Backtrace\Event',
            array('/path/example.php', 1423)
        );

        $this->assertSame( "/path/example.php", $event->getFile() );
        $this->assertSame( 1423, $event->getLine() );
        $this->assertSame( null, $event->getClass() );
        $this->assertSame( null, $event->getName() );
        $this->assertSame( array(), $event->getArgs() );
    }

    public function testGetArg_NoArgs ()
    {
        $event = $this->getMockForAbstractClass(
            '\r8\Backtrace\Event',
            array('/path/example.php', 1423)
        );

        $this->assertNull( $event->getArg(0) );
    }

}

