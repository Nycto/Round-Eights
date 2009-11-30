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
class classes_Autoload extends PHPUnit_Framework_TestCase
{

    public function testAutoload ()
    {
        $auto = \r8\Autoload::getInstance();
        $this->assertThat( $auto, $this->isInstanceOf('\r8\Autoload') );

        $this->assertSame( $auto, \r8\Autoload::getInstance() );
        $this->assertSame( $auto, \r8\Autoload::getInstance() );
        $this->assertSame( $auto, \r8\Autoload::getInstance() );
    }

    public function testRegister ()
    {
        $auto = new \r8\Autoload;
        $this->assertSame( array(), $auto->getRegistered() );

        $this->assertSame( $auto, $auto->register( '/r8/', "/dir" ) );
        $this->assertSame(
            array( '/r8/' => '/dir' ),
            $auto->getRegistered()
        );

        $this->assertSame( $auto, $auto->register( 'r8/iface', "/dir/iface" ) );
        $this->assertSame(
            array( '/r8/' => '/dir', '/r8/iface/' => '/dir/iface' ),
            $auto->getRegistered()
        );

        $this->assertSame( $auto, $auto->register( 'r8', "/new" ) );
        $this->assertSame(
            array( '/r8/' => '/new', '/r8/iface/' => '/dir/iface' ),
            $auto->getRegistered()
        );
    }

    public function testRegister_Error ()
    {
        $auto = new \r8\Autoload;

        try {
            $auto->register( '', "/new" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }

        try {
            $auto->register( 'r8', "   " );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }
    }

}

?>