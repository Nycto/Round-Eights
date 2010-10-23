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
class classes_XMLBuilder_Soap_Envelope extends PHPUnit_Framework_TestCase
{

    public function testBuildNode ()
    {
        $doc = new \DOMDocument;

        $node = $doc->createElement("tag");
        $subBuilder = $this->getMock("r8\iface\XMLBuilder", array("buildNode"));
        $subBuilder->expects( $this->once() )
            ->method("buildNode")
            ->with( $this->isInstanceOf("DOMDocument") )
            ->will( $this->returnValue($node) );


        $builder = new \r8\XMLBuilder\Soap\Envelope( $subBuilder );

        $builtNode = $builder->buildNode( $doc );
        $this->assertThat( $builtNode, $this->isInstanceOf("DOMElement") );

        $this->assertSame(
                '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope">'
                    .'<soap:Body><tag/></soap:Body>'
                .'</soap:Envelope>',
                $doc->saveXML( $builtNode )
            );
    }

    public function testBuildNode_header ()
    {
        $doc = new \DOMDocument;

        $node = $doc->createElement("tag");
        $subBuilder = $this->getMock("r8\iface\XMLBuilder", array("buildNode"));
        $subBuilder->expects( $this->once() )
            ->method("buildNode")
            ->with( $this->isInstanceOf("DOMDocument") )
            ->will( $this->returnValue($node) );


        $head = $doc->createElement("head");
        $headBuilder = $this->getMock("r8\iface\XMLBuilder", array("buildNode"));
        $headBuilder->expects( $this->once() )
            ->method("buildNode")
            ->with( $this->isInstanceOf("DOMDocument") )
            ->will( $this->returnValue($head) );


        $builder = new \r8\XMLBuilder\Soap\Envelope( $subBuilder, $headBuilder );

        $builtNode = $builder->buildNode( $doc );
        $this->assertThat( $builtNode, $this->isInstanceOf("DOMElement") );

        $this->assertSame(
                '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope">'
                    .'<soap:Header><head/></soap:Header>'
                    .'<soap:Body><tag/></soap:Body>'
                .'</soap:Envelope>',
                $doc->saveXML( $builtNode )
            );
    }

    public function testBuildNode_namespace ()
    {
        $doc = new \DOMDocument;

        $node = $doc->createElement("tag");
        $subBuilder = $this->getMock("r8\iface\XMLBuilder", array("buildNode"));
        $subBuilder->expects( $this->once() )
            ->method("buildNode")
            ->with( $this->isInstanceOf("DOMDocument") )
            ->will( $this->returnValue($node) );


        $builder = new \r8\XMLBuilder\Soap\Envelope( $subBuilder, null, "test:uri" );

        $builtNode = $builder->buildNode( $doc );
        $this->assertThat( $builtNode, $this->isInstanceOf("DOMElement") );

        $this->assertSame(
                '<soap:Envelope xmlns:soap="test:uri">'
                    .'<soap:Body><tag/></soap:Body>'
                .'</soap:Envelope>',
                $doc->saveXML( $builtNode )
            );
    }

}

