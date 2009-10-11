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

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_soap_parser extends PHPUnit_Framework_TestCase
{

    public function testCountMessages_none ()
    {
        $parser = new \h2o\Soap\Parser( new DOMDocument );
        $this->assertSame( 0, $parser->countMessages() );
    }

    public function testCountMessages_found ()
    {
        $doc = new DOMDocument;
        $doc->loadXML(
        	'<soap:Envelope xmlns:soap="http://www.w3.org/2001/12/soap-envelope">'
        		.'<soap:Body>'
        		    .'<msg:Message xmlns:msg="test" />'
        		    .'<!-- Comment -->'
        		    .'<msg2:Message xmlns:msg2="test2" />'
        		    .' Stray Text  '
        		    .'<msg:Message xmlns:msg="test" />'
    		    .'</soap:Body>'
    		.'</soap:Envelope>'
        );
        $parser = new \h2o\Soap\Parser( $doc );
        $this->assertSame( 3, $parser->countMessages() );
    }

    public function testEnsureBasics_empty ()
    {
        $parser = new \h2o\Soap\Parser( new DOMDocument );

        try {
            $parser->ensureBasics();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Soap\Fault $err ) {
            $this->assertSame( "Document is Empty", $err->getMessage() );
            $this->assertSame( "Sender", $err->getPrimeCode() );
            $this->assertSame( array("Parser", "EmptyDoc"), $err->getSubCodes() );
        }
    }

    public function testEnsureBasics_noEnvelope ()
    {
        $doc = new DOMDocument;
        $doc->loadXML("<notEmpty />");
        $parser = new \h2o\Soap\Parser( $doc );

        try {
            $parser->ensureBasics();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Soap\Fault $err ) {
            $this->assertSame( "Could not find a SOAP Envelope node", $err->getMessage() );
            $this->assertSame( "Sender", $err->getPrimeCode() );
            $this->assertSame( array("Parser", "MissingEnvelope"), $err->getSubCodes() );
        }
    }

    public function testEnsureBasics_noBody ()
    {
        $doc = new DOMDocument;
        $doc->loadXML('<soap:Envelope xmlns:soap="http://www.w3.org/2001/12/soap-envelope" />');
        $parser = new \h2o\Soap\Parser( $doc );

        try {
            $parser->ensureBasics();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Soap\Fault $err ) {
            $this->assertSame( "Could not find a SOAP Body node", $err->getMessage() );
            $this->assertSame( "Sender", $err->getPrimeCode() );
            $this->assertSame( array("Parser", "MissingBody"), $err->getSubCodes() );
        }
    }

    public function testEnsureBasics_multiBody ()
    {
        $doc = new DOMDocument;
        $doc->loadXML(
        	'<soap:Envelope xmlns:soap="http://www.w3.org/2001/12/soap-envelope">'
        		.'<soap:Body />'
        		.'<soap:Body />'
    		.'</soap:Envelope>'
        );
        $parser = new \h2o\Soap\Parser( $doc );

        try {
            $parser->ensureBasics();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Soap\Fault $err ) {
            $this->assertSame( "Multiple SOAP Body nodes found", $err->getMessage() );
            $this->assertSame( "Sender", $err->getPrimeCode() );
            $this->assertSame( array("Parser", "MultiBody"), $err->getSubCodes() );
        }
    }

    public function testEnsureBasics_noMessage ()
    {
        $doc = new DOMDocument;
        $doc->loadXML(
        	'<soap:Envelope xmlns:soap="http://www.w3.org/2001/12/soap-envelope">'
        		.'<soap:Body />'
    		.'</soap:Envelope>'
        );
        $parser = new \h2o\Soap\Parser( $doc );

        try {
            $parser->ensureBasics();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Soap\Fault $err ) {
            $this->assertSame( "No Message Nodes found", $err->getMessage() );
            $this->assertSame( "Sender", $err->getPrimeCode() );
            $this->assertSame( array("Parser", "NoMessage"), $err->getSubCodes() );
        }
    }

    public function testEnsureBasics_multiHeader ()
    {
        $doc = new DOMDocument;
        $doc->loadXML(
        	'<soap:Envelope xmlns:soap="http://www.w3.org/2001/12/soap-envelope">'
        		.'<soap:Header />'
        		.'<soap:Header />'
        		.'<soap:Body>'
        		    .'<msg:Message xmlns:msg="test" />'
    		    .'</soap:Body>'
    		.'</soap:Envelope>'
        );
        $parser = new \h2o\Soap\Parser( $doc );

        try {
            $parser->ensureBasics();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Soap\Fault $err ) {
            $this->assertSame( "Multiple SOAP Header nodes found", $err->getMessage() );
            $this->assertSame( "Sender", $err->getPrimeCode() );
            $this->assertSame( array("Parser", "MultiHeader"), $err->getSubCodes() );
        }
    }

    public function testEnsureBasics_valid ()
    {
        $doc = new DOMDocument;
        $doc->loadXML(
        	'<soap:Envelope xmlns:soap="http://www.w3.org/2001/12/soap-envelope">'
        		.'<soap:Header />'
        		.'<soap:Body>'
        		    .'<msg:Message xmlns:msg="test" />'
    		    .'</soap:Body>'
    		.'</soap:Envelope>'
        );
        $parser = new \h2o\Soap\Parser( $doc );

        $this->assertNull( $parser->ensureBasics() );
    }

    public function testGetHeaders_Empty ()
    {
        $doc = new DOMDocument;
        $doc->loadXML(
        	'<soap:Envelope xmlns:soap="http://www.w3.org/2001/12/soap-envelope">'
        		.'<soap:Header />'
        		.'<soap:Body>'
        		    .'<msg:Message xmlns:msg="test" />'
    		    .'</soap:Body>'
    		.'</soap:Envelope>'
        );
        $parser = new \h2o\Soap\Parser( $doc );

        $result = $parser->getHeaders();
        $this->assertThat( $result, $this->isInstanceOf("\h2o\Iterator\DOMNodeList") );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array(),
            $result
        );
    }

    public function testGetHeaders_None ()
    {
        $doc = new DOMDocument;
        $doc->loadXML(
        	'<soap:Envelope xmlns:soap="http://www.w3.org/2001/12/soap-envelope">'
        		.'<soap:Body>'
        		    .'<msg:Message xmlns:msg="test" />'
    		    .'</soap:Body>'
    		.'</soap:Envelope>'
        );
        $parser = new \h2o\Soap\Parser( $doc );

        $result = $parser->getHeaders();
        $this->assertThat( $result, $this->isInstanceOf("\h2o\Iterator\DOMNodeList") );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array(),
            $result
        );
    }

    public function testGetHeaders ()
    {
        $doc = new DOMDocument;
        $doc->loadXML(
        	'<soap:Envelope xmlns:soap="http://www.w3.org/2001/12/soap-envelope">'
        		.'<soap:Header>'
        		    .'<msg:First xmlns:msg="test" />'
        		    .' Stray Text   '
        		    .'<msg2:Second xmlns:msg2="test2" />'
        		    .'   <!-- Comment -->  '
        		.'</soap:Header>'
        		.'<soap:Body>'
        		    .'<msg:Message xmlns:msg="test" />'
    		    .'</soap:Body>'
    		.'</soap:Envelope>'
        );
        $parser = new \h2o\Soap\Parser( $doc );

        $iterator = $parser->getHeaders();
        $this->assertThat( $iterator, $this->isInstanceOf("\h2o\Iterator\DOMNodeList") );

        $result = PHPUnit_Framework_Constraint_Iterator::iteratorToArray(
            10,
            $iterator
        );

        $this->assertSame( 2, count($result) );

        $this->assertArrayHasKey( 0, $result );
        $this->assertArrayHasKey( 1, $result );

        $this->assertThat( $result[0], $this->isInstanceOf("DOMElement") );
        $this->assertThat( $result[1], $this->isInstanceOf("DOMElement") );

        $this->assertSame( "msg:First", $result[0]->tagName );
        $this->assertSame( "msg2:Second", $result[1]->tagName );
    }

}

?>