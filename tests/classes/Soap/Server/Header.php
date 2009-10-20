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

    public function testConstruct ()
    {
        $soap = new \h2o\Soap\Server\Header;
        $this->assertSame(
            array(
                "http://www.w3.org/2003/05/soap-envelope/role/ultimateReceiver",
                "http://www.w3.org/2003/05/soap-envelope/role/next"
            ),
            $soap->getRoles()
        );

        $soap = new \h2o\Soap\Server\Header( "one", "two" );
        $this->assertSame( array( "one", "two" ), $soap->getRoles() );
    }

    public function testAddRole ()
    {
        $soap = new \h2o\Soap\Server\Header;
        $this->assertSame(
            array(
                "http://www.w3.org/2003/05/soap-envelope/role/ultimateReceiver",
                "http://www.w3.org/2003/05/soap-envelope/role/next"
            ),
            $soap->getRoles()
        );

        $this->assertSame( $soap, $soap->addRole( "  " ) );
        $this->assertSame(
            array(
                "http://www.w3.org/2003/05/soap-envelope/role/ultimateReceiver",
                "http://www.w3.org/2003/05/soap-envelope/role/next"
            ),
            $soap->getRoles()
        );

        $this->assertSame( $soap, $soap->addRole( "  test :  uri   " ) );
        $this->assertSame(
            array(
                "http://www.w3.org/2003/05/soap-envelope/role/ultimateReceiver",
                "http://www.w3.org/2003/05/soap-envelope/role/next",
                "test:uri"
            ),
            $soap->getRoles()
        );

        $this->assertSame( $soap, $soap->addRole( "test:uri" ) );
        $this->assertSame(
            array(
                "http://www.w3.org/2003/05/soap-envelope/role/ultimateReceiver",
                "http://www.w3.org/2003/05/soap-envelope/role/next",
                "test:uri"
            ),
            $soap->getRoles()
        );
    }

    public function testHasRole ()
    {
        $soap = new \h2o\Soap\Server\Header;
        $soap->addRole( "test:uri" );

        $this->assertTrue( $soap->hasRole("http://www.w3.org/2003/05/soap-envelope/role/ultimateReceiver") );
        $this->assertTrue( $soap->hasRole("http://www.w3.org/2003/05/soap-envelope/role/next") );
        $this->assertTrue( $soap->hasRole("test:uri") );
        $this->assertTrue( $soap->hasRole("  test : uri  ") );
        $this->assertTrue( $soap->hasRole(NULL) );
        $this->assertTrue( $soap->hasRole("") );
        $this->assertTrue( $soap->hasRole("   ") );

        $this->assertFalse( $soap->hasRole("return/false") );
    }

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

    public function testUnderstands ()
    {
        $soap = new \h2o\Soap\Server\Header;
        $this->assertFalse( $soap->understands("test:uri", "tag") );
        $this->assertFalse( $soap->understands("uri2", "other") );

        $soap->addHeader("test:uri", "tag", $this->getMock('\h2o\iface\Soap\Header'));
        $this->assertTrue( $soap->understands("test:uri", "tag") );
        $this->assertFalse( $soap->understands("uri2", "other") );

        $soap->addHeader("uri2", "other", $this->getMock('\h2o\iface\Soap\Header'));
        $this->assertTrue( $soap->understands("test:uri", "tag") );
        $this->assertTrue( $soap->understands("uri2", "other") );
    }

}

?>