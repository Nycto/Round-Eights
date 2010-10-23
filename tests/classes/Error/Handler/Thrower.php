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
class classes_Error_Handler_Thrower extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a test Error
     *
     * @return \r8\iface\Error
     */
    public function getTestError ()
    {
        $error = $this->getMock('\r8\iface\Error');
        $error->expects( $this->once() )
            ->method( "getMessage" )
            ->will( $this->returnValue( "The Error Message" ) );
        $error->expects( $this->exactly(2) )
            ->method( "getCode" )
            ->will( $this->returnValue( 2020 ) );
        $error->expects( $this->once() )
            ->method( "getFile" )
            ->will( $this->returnValue( "/path/to/file.php" ) );
        $error->expects( $this->once() )
            ->method( "getLine" )
            ->will( $this->returnValue( 11235813 ) );

        return $error;
    }

    public function testHandle ()
    {
        $thrower = new \r8\Error\Handler\Thrower;

        $error = $this->getTestError();

        try {
            $thrower->handle( $error );
            $this->fail("An expected exception was not thrown");
        }
        catch ( ErrorException $err ) {
            $this->assertEquals( "The Error Message", $err->getMessage() );
            $this->assertEquals( 2020, $err->getCode() );
            $this->assertEquals( 2020, $err->getSeverity() );
            $this->assertEquals( "/path/to/file.php", $err->getFile() );
            $this->assertEquals( 11235813, $err->getLine() );
        }
    }

    public function testHandle_WithWrapper ()
    {
        $error = $this->getTestError();

        $wrapped = $this->getMock('\r8\iface\Error\Handler');
        $wrapped->expects( $this->once() )
            ->method( "handle" )
            ->with( $this->equalTo( $error ) );

        $thrower = new \r8\Error\Handler\Thrower( $wrapped );

        try {
            $thrower->handle( $error );
            $this->fail("An expected exception was not thrown");
        }
        catch ( ErrorException $err ) {
            $this->assertEquals( "The Error Message", $err->getMessage() );
            $this->assertEquals( 2020, $err->getCode() );
            $this->assertEquals( 2020, $err->getSeverity() );
            $this->assertEquals( "/path/to/file.php", $err->getFile() );
            $this->assertEquals( 11235813, $err->getLine() );
        }
    }

}

