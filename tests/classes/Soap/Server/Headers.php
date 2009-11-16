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

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_Soap_Server_Headers extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a test Header object
     *
     * @return \r8\Soap\Node\Header
     */
    public function getTestHeader ( $tag, $namespace, $understand = FALSE, $role = null )
    {
        $header = $this->getMock('r8\Soap\Node\Header', array(), array(), '', FALSE);
        $header->expects( $this->any() )
            ->method( "getRole" )
            ->will( $this->returnValue($role) );
        $header->expects( $this->any() )
            ->method( "getTag" )
            ->will( $this->returnValue($tag) );
        $header->expects( $this->any() )
            ->method( "getNamespace" )
            ->will( $this->returnValue($namespace) );
        $header->expects( $this->any() )
            ->method( "mustUnderstand" )
            ->will( $this->returnValue($understand) );
        return $header;
    }

    /**
     * Returns a test processor
     *
     * @return \r8\iface\Soap\Header
     */
    public function getTestProcessor ( $result = NULL )
    {
        $hdr = $this->getMock('r8\iface\Soap\Header', array(), array(), '', FALSE);

        if ( empty($result) ) {
            $hdr->expects( $this->never() )
                ->method( "process" );
        }
        else {
            $hdr->expects( $this->any() )
                ->method( "process" )
                ->will( $this->returnValue($result) );
        }

        return $hdr;
    }

    public function testConstruct ()
    {
        $soap = new \r8\Soap\Server\Headers;
        $this->assertSame(
            array(
                "http://www.w3.org/2003/05/soap-envelope/role/ultimateReceiver",
                "http://www.w3.org/2003/05/soap-envelope/role/next"
            ),
            $soap->getRoles()
        );

        $soap = new \r8\Soap\Server\Headers( "one", "two" );
        $this->assertSame( array( "one", "two" ), $soap->getRoles() );
    }

    public function testAddRole ()
    {
        $soap = new \r8\Soap\Server\Headers;
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
        $soap = new \r8\Soap\Server\Headers;
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
        $soap = new \r8\Soap\Server\Headers;
        $this->assertSame( array(), $soap->getHeaders() );

        $cmd = $this->getMock('\r8\iface\Soap\Header');
        $this->assertSame( $soap, $soap->addHeader("test:uri", "one", $cmd) );
        $this->assertSame(
            array( "test:uri" => array("one" => $cmd) ),
            $soap->getHeaders()
        );

        $cmd2 = $this->getMock('\r8\iface\Soap\Header');
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
        $soap = new \r8\Soap\Server\Headers;

        try {
            $soap->addHeader("  ", "test", $this->getMock('\r8\iface\Soap\Header'));
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }

        try {
            $soap->addHeader("uri", " ", $this->getMock('\r8\iface\Soap\Header'));
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }
    }

    public function testUnderstands ()
    {
        $soap = new \r8\Soap\Server\Headers;
        $this->assertFalse( $soap->understands("test:uri", "tag") );
        $this->assertFalse( $soap->understands("uri2", "other") );

        $soap->addHeader("test:uri", "tag", $this->getMock('\r8\iface\Soap\Header'));
        $this->assertTrue( $soap->understands("test:uri", "tag") );
        $this->assertFalse( $soap->understands("uri2", "other") );

        $soap->addHeader("uri2", "other", $this->getMock('\r8\iface\Soap\Header'));
        $this->assertTrue( $soap->understands("test:uri", "tag") );
        $this->assertTrue( $soap->understands("uri2", "other") );
    }

    public function testProcess_UnregisteredRole ()
    {
        $parser = $this->getMock('r8\Soap\Parser', array(), array(), '', FALSE);
        $parser->expects( $this->once() )
            ->method( "getHeaders" )
            ->will( $this->returnValue(array(
                $this->getTestHeader( 'tag', 'uri:ns', TRUE, 'uri:role' )
            )) );

        $soap = new \r8\Soap\Server\Headers;
        $soap->process( $parser );
    }

    public function testProcess_empty ()
    {
        $parser = $this->getMock('r8\Soap\Parser', array(), array(), '', FALSE);
        $parser->expects( $this->once() )
            ->method( "getHeaders" )
            ->will( $this->returnValue(array()) );

        $soap = new \r8\Soap\Server\Headers;

        $result = $soap->process( $parser );

        $this->assertThat( $result, $this->isInstanceOf("r8\XMLBuilder\Series") );

        $this->assertFalse( $result->hasChildren() );
    }

    public function testProcess_wrongRole ()
    {
        $parser = $this->getMock('r8\Soap\Parser', array(), array(), '', FALSE);
        $parser->expects( $this->once() )
            ->method( "getHeaders" )
            ->will( $this->returnValue(array(
                $this->getTestHeader( 'tag', 'uri:ns', TRUE, "uri:role" )
            )) );

        $soap = new \r8\Soap\Server\Headers;
        $result = $soap->process( $parser );

        $this->assertThat( $result, $this->isInstanceOf("r8\XMLBuilder\Series") );
        $this->assertFalse( $result->hasChildren() );
    }

    public function testProcess_notUnderstood ()
    {
        $parser = $this->getMock('r8\Soap\Parser', array(), array(), '', FALSE);
        $parser->expects( $this->once() )
            ->method( "getHeaders" )
            ->will( $this->returnValue(array(
                $this->getTestHeader( 'tag', 'uri:ns', TRUE )
            )) );

        $soap = new \r8\Soap\Server\Headers;

        try {
            $soap->process( $parser );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Soap\Fault $err ) {
            $this->assertSame( "Mandatory Soap Header is not understood", $err->getMessage() );
            $this->assertSame( "MustUnderstand", $err->getPrimeCode() );
            $this->assertSame( array(), $err->getSubCodes() );
            $this->assertNull( $err->getRole() );
            $this->assertSame(
                array(
                    "NotUnderstood" => array(
                    	"Header" => "tag",
                    	"Namespace" => "uri:ns"
                    )
                ),
                $err->getDetails()
            );

        }
    }

    public function testProcess_complete ()
    {
        $parser = $this->getMock('r8\Soap\Parser', array(), array(), '', FALSE);
        $parser->expects( $this->once() )
            ->method( "getHeaders" )
            ->will( $this->returnValue(array(
                $this->getTestHeader( 'none', 'uri:test', TRUE, "uri:role" ),
                $this->getTestHeader( 'one', 'uri:test', TRUE ),
                $this->getTestHeader( 'two', 'uri:test' ),
                $this->getTestHeader( 'three', 'uri:test' )
            )) );

        $soap = new \r8\Soap\Server\Headers;

        $one = $this->getMock('r8\iface\XMLBuilder');
        $two = $this->getMock('r8\iface\XMLBuilder');

        $soap->addHeader( "uri:test", "one", $this->getTestProcessor( $one ) );
        $soap->addHeader( "other:uri", "one", $this->getTestProcessor() );
        $soap->addHeader( "uri:test", "two", $this->getTestProcessor( $two ) );

        $result = $soap->process( $parser );

        $this->assertThat( $result, $this->isInstanceOf("r8\XMLBuilder\Series") );
        $this->assertSame( array($one, $two), $result->getChildren() );
    }

}

?>