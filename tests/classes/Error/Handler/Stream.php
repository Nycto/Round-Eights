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

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_Error_Handler_Stream extends PHPUnit_Framework_TestCase
{

    public function testHandle ()
    {
        $error = $this->getMock('\r8\iface\Error');

        $formatter = $this->getMock('\r8\iface\Error\Formatter');
        $formatter->expects( $this->once() )
            ->method( "format" )
            ->with( $this->equalTo($error) )
            ->will( $this->returnValue( "Formatted Error" ) );

        $stream = $this->getMock('\r8\iface\Stream\Out');
        $stream->expects( $this->once() )
            ->method( "write" )
            ->with( $this->equalTo( "Formatted Error" ) );

        $handler = new \r8\Error\Handler\Stream( $formatter, $stream );

        $this->assertNull( $handler->handle( $error ) );
    }

}

