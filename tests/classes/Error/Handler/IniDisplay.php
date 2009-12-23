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
class classes_Error_Handler_IniDisplay extends PHPUnit_Framework_TestCase
{

    public function testHandle_Disabled ()
    {
        $this->iniSet( "display_errors", 0 );

        $error = $this->getMock('\r8\iface\Error');

        $wrapped = $this->getMock('\r8\iface\Error\Handler');
        $wrapped->expects( $this->never() )->method( "handle" );

        $ini = new \r8\Error\Handler\IniDisplay( $wrapped );

        $this->assertNull( $ini->handle( $error ) );
    }

    public function testHandle_Enabled ()
    {
        $this->iniSet( "display_errors", 1 );

        $error = $this->getMock('\r8\iface\Error');

        $wrapped = $this->getMock('\r8\iface\Error\Handler');
        $wrapped->expects( $this->once() )
            ->method( "handle" )
            ->with( $this->equalTo( $error ) );

        $ini = new \r8\Error\Handler\IniDisplay( $wrapped );

        $this->assertNull( $ini->handle( $error ) );
    }

}

?>