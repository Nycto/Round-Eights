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
class classes_Error extends PHPUnit_Framework_TestCase
{

    public function testGetInstance ()
    {
        $error = \r8\Error::getInstance();
        $this->assertThat( $error, $this->isInstanceOf('\r8\Error') );
        $this->assertSame( $error, \r8\Error::getInstance() );
        $this->assertSame( $error, \r8\Error::getInstance() );
    }

    public function testRegister ()
    {
        $error = new \r8\Error;
        $this->assertSame( array(), $error->getHandlers() );

        $handler1 = $this->getMock('\r8\iface\Error\Handler');
        $error->register( $handler1 );
        $this->assertSame( array( $handler1 ), $error->getHandlers() );

        $handler2 = $this->getMock('\r8\iface\Error\Handler');
        $error->register( $handler2 );
        $this->assertSame( array( $handler1, $handler2 ), $error->getHandlers() );

        $error->register( $handler1 );
        $this->assertSame( array( $handler1, $handler2 ), $error->getHandlers() );
    }

    public function testHandle ()
    {
        $error = $this->getMock('\r8\iface\Error');

        $handler1 = $this->getMock('\r8\iface\Error\Handler');
        $handler1->expects( $this->once() )
            ->method( "handle" )
            ->with( $this->equalTo( $error ) );

        $handler2 = $this->getMock('\r8\iface\Error\Handler');
        $handler2->expects( $this->once() )
            ->method( "handle" )
            ->with( $this->equalTo( $error ) );

        $registry = new \r8\Error;
        $registry->register( $handler1 );
        $registry->register( $handler2 );

        $this->assertTrue( $registry->handle( $error ) );
    }

}

