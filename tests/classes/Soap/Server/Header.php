<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of RaindropPHP.
 *
 * RaindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * RaindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with RaindropPHP. If not, see <http://www.RaindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <James@RaindropPHP.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_Soap_Server_Header extends PHPUnit_Framework_TestCase
{

    public function testAddHeader ()
    {
        $soap = new \h2o\Soap\Server\Header;
        $this->assertSame( array(), $soap->getHeaders() );

        $cmd = $this->getMock('\h2o\iface\Soap\Header');
        $this->assertSame( $soap, $soap->addHeader("test:uri", "one", $cmd) );
        $this->assertSame(
            array( "test:uri" => array("one" => $cmd) ),
            $soap->getHeaders()
        );

        $cmd2 = $this->getMock('\h2o\iface\Soap\Header');
        $this->assertSame( $soap, $soap->addHeader("test:uri", "two", $cmd2) );
        $this->assertSame(
            array( "test:uri" => array("one" => $cmd, "two" => $cmd2) ),
            $soap->getHeaders()
        );

        $this->assertSame( $soap, $soap->addHeader("other:uri", "one", $cmd2) );
        $this->assertSame(
            array(
            	"test:uri" => array("one" => $cmd, "two" => $cmd2 ),
                "other:uri" => array("one" => $cmd2 )
            ),
            $soap->getHeaders()
        );
    }

    public function testAddHeader_err ()
    {
        $soap = new \h2o\Soap\Server\Header;

        try {
            $soap->addHeader("  ", "test", $this->getMock('\h2o\iface\Soap\Header'));
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\Argument $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }

        try {
            $soap->addHeader("uri", " ", $this->getMock('\h2o\iface\Soap\Header'));
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\Argument $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }
    }

}

?>