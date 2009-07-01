<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of raindropPHP.
 *
 * raindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * raindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with raindropPHP. If not, see <http://www.raindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * unit tests
 */
class classes_xmlbuilder extends PHPUnit_Framework_TestCase
{

    public function testImportNode_import ()
    {
        $doc = new DOMDocument;

        $node = new DOMElement("tag");

        $newNode = \h2o\XMLBuilder::importNode( $doc, $node );

        $this->assertNotSame( $newNode, $node );
        $this->assertSame( $doc, $newNode->ownerDocument );
        $this->assertSame( "tag", $newNode->tagName );
    }

    public function testImportNode_unchanged ()
    {
        $doc = new DOMDocument;

        $node = $doc->createElement("tag");

        $newNode = \h2o\XMLBuilder::importNode( $doc, $node );

        $this->assertSame( $newNode, $node );
    }

    public function testBuildNode_standard ()
    {
        $doc = new \DOMDocument;

        $node = $doc->createElement("tag");

        $builder = $this->getMock("h2o\iface\XMLBuilder", array("buildNode"));
        $builder->expects( $this->once() )
            ->method("buildNode")
            ->with( $this->isInstanceOf("DOMDocument") )
            ->will( $this->returnValue($node) );

        $built = \h2o\XMLBuilder::buildNode( $builder, $doc );

        $this->assertSame( $node, $built );
    }

    public function testBuildNode_import ()
    {
        $doc = new \DOMDocument;

        $node = new \DOMElement("tag");

        $builder = $this->getMock("h2o\iface\XMLBuilder", array("buildNode"));
        $builder->expects( $this->once() )
            ->method("buildNode")
            ->with( $this->isInstanceOf("DOMDocument") )
            ->will( $this->returnValue($node) );

        $built = \h2o\XMLBuilder::buildNode( $builder, $doc );

        $this->assertNotSame( $node, $built );
        $this->assertThat( $built, $this->isInstanceOf("DOMElement") );
        $this->assertSame( "tag", $node->tagName );
    }

    public function testBuildNode_error ()
    {
        $doc = new \DOMDocument;

        $builder = $this->getMock("h2o\iface\XMLBuilder", array("buildNode"));
        $builder->expects( $this->once() )
            ->method("buildNode")
            ->with( $this->isInstanceOf("DOMDocument") )
            ->will( $this->returnValue("invalid result") );

        try {
            \h2o\XMLBuilder::buildNode( $builder, $doc );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\Interaction $err ) {
            $this->assertSame(
                    "XMLBuilder did not return a DOMNode object",
                    $err->getMessage()
                );
        }
    }

    public function testBuildDoc ()
    {
        $doc = new \DOMDocument;

        $node = $doc->createElement("tag");

        $subBuilder = $this->getMock("h2o\iface\XMLBuilder", array("buildNode"));
        $subBuilder->expects( $this->once() )
            ->method("buildNode")
            ->with( $this->isInstanceOf("DOMDocument") )
            ->will( $this->returnValue($node) );

        $builder = new \h2o\XMLBuilder($doc, $subBuilder);

        $this->assertSame( $doc, $builder->buildDoc() );

        $this->assertSame( $node, $doc->firstChild );
        $this->assertSame( $doc->firstChild, $doc->lastChild );
    }

}

?>