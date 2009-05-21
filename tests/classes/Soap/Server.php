<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of commonPHP.
 *
 * commonPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * commonPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with commonPHP. If not, see <http://www.commonphp.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@commonphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_soap_server extends PHPUnit_Framework_TestCase
{

    public function testRegister ()
    {
        $soap = new \cPHP\Soap\Server( "uri://example.com" );
        $this->assertSame( array(), $soap->getOperations() );

        $cmd = $this->getMock('\cPHP\iface\Soap\Command');
        $this->assertSame( $soap, $soap->register("one", $cmd) );
        $this->assertSame( array("one" => $cmd), $soap->getOperations() );

        $cmd2 = $this->getMock('\cPHP\iface\Soap\Command');
        $this->assertSame( $soap, $soap->register("two", $cmd2) );
        $this->assertSame(
                array("one" => $cmd, "two" => $cmd2),
                $soap->getOperations()
            );

        $this->assertSame( $soap, $soap->register("one", $cmd2) );
        $this->assertSame(
                array("one" => $cmd2, "two" => $cmd2),
                $soap->getOperations()
            );
    }

    public function testRegister_err ()
    {
        $soap = new \cPHP\Soap\Server( "uri://example.com" );
        $this->assertSame( array(), $soap->getOperations() );

        $cmd = $this->getMock('\cPHP\iface\Soap\Command');

        try {
            $soap->register("  ", $cmd);
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }
    }

    public function testProcess_emptyDoc ()
    {
        $soap = new \cPHP\Soap\Server( "uri://example.com" );

        $result = $soap->process( new DOMDocument );

        $this->assertEquals(
                new \cPHP\XMLBuilder\Soap\Fault(
                		1000,
                		"Empty XML Document"
            		),
                $result
            );
    }

    public function testProcess_noEnvelope ()
    {
        $doc = new DOMDocument;
        $doc->loadXML('<tag />');

        $soap = new \cPHP\Soap\Server( "uri://example.com" );
        $result = $soap->process( $doc );

        $this->assertEquals(
                new \cPHP\XMLBuilder\Soap\Fault(
                		1001,
                		"Could not find soap envelope"
            		),
                $result
            );
    }

    public function testProcess_noBody ()
    {
        $doc = new DOMDocument;
        $doc->loadXML('<soap:Envelope xmlns:soap="http://www.w3.org/2001/12/soap-envelope" />');

        $soap = new \cPHP\Soap\Server( "uri://example.com" );
        $result = $soap->process( $doc );

        $this->assertEquals(
                new \cPHP\XMLBuilder\Soap\Fault(
                        1002,
                        "Could not find soap body"
                    ),
                $result
            );
    }

    public function testProcess_multiBody ()
    {
        $doc = new DOMDocument;
        $doc->loadXML(
        	'<soap:Envelope xmlns:soap="http://www.w3.org/2001/12/soap-envelope">'
        		.'<soap:Body />'
        		.'<soap:Body />'
    		.'</soap:Envelope>'
        );

        $soap = new \cPHP\Soap\Server( "uri://example.com" );
        $result = $soap->process( $doc );

        $this->assertEquals(
                new \cPHP\XMLBuilder\Soap\Fault(
                		1003,
                		"Multiple soap body elements found"
                    ),
                $result
            );
    }

    public function testProcess_noCommand ()
    {
        $doc = new DOMDocument;
        $doc->loadXML(
        	'<soap:Envelope xmlns:soap="http://www.w3.org/2001/12/soap-envelope">'
        		.'<soap:Body />'
    		.'</soap:Envelope>'
        );

        $soap = new \cPHP\Soap\Server( "uri://example.com" );
        $result = $soap->process( $doc );

        $this->assertEquals(
                new \cPHP\XMLBuilder\Soap\Fault(
                		1004,
                		"Could not find soap operation element"
                    ),
                $result
            );
    }

    public function testProcess_multiCommand ()
    {
        $doc = new DOMDocument;
        $doc->loadXML(
        	'<soap:Envelope xmlns:soap="http://www.w3.org/2001/12/soap-envelope">'
        		.'<soap:Body>'
        			.'<m:Action xmlns:m="uri://example.com" />'
        			.'<m:Action2 xmlns:m="uri://example.com" />'
        		.'</soap:Body>'
    		.'</soap:Envelope>'
        );

        $soap = new \cPHP\Soap\Server( "uri://example.com" );
        $result = $soap->process( $doc );

        $this->assertEquals(
                new \cPHP\XMLBuilder\Soap\Fault(
                    	1005,
                    	"Multiple soap operation elements found"
                    ),
                $result
            );
    }

    public function testProcess_invalidOperation ()
    {
        $doc = new DOMDocument;
        $doc->loadXML(
        	'<soap:Envelope xmlns:soap="http://www.w3.org/2001/12/soap-envelope">'
        		.'<soap:Body>'
        			.'<m:Action xmlns:m="uri://example.com" />'
        		.'</soap:Body>'
    		.'</soap:Envelope>'
        );

        $soap = new \cPHP\Soap\Server( "uri://example.com" );
        $result = $soap->process( $doc );

        $this->assertEquals(
                new \cPHP\XMLBuilder\Soap\Fault(
                    	1006,
                    	"Invalid soap operation"
                    ),
                $result
            );
    }

    public function testProcess_success ()
    {
        $doc = new DOMDocument;
        $doc->loadXML(
        	'<soap:Envelope xmlns:soap="http://www.w3.org/2001/12/soap-envelope">'
        		.'<soap:Body>'
        			.'<m:Action xmlns:m="uri://example.com" />'
        		.'</soap:Body>'
    		.'</soap:Envelope>'
        );

        $soap = new \cPHP\Soap\Server( "uri://example.com" );

        $response = $this->getMock('\cPHP\iface\XMLBuilder');

        $cmd = $this->getMock('\cPHP\iface\Soap\Command');
        $cmd->expects( $this->once() )
            ->method( 'getResponseBuilder' )
            ->with(
                    $this->equalTo($doc),
                    $this->isInstanceOf("DOMElement")
                )
            ->will( $this->returnValue($response) );


        $soap->register( "Action", $cmd );

        $this->assertSame( $response, $soap->process( $doc ) );
    }

}

?>