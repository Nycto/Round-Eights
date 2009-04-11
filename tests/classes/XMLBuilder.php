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

        $newNode = \cPHP\XMLBuilder::importNode( $doc, $node );

        $this->assertNotSame( $newNode, $node );
        $this->assertSame( $doc, $newNode->ownerDocument );
        $this->assertSame( "tag", $newNode->tagName );
    }

    public function testImportNode_unchanged ()
    {
        $doc = new DOMDocument;

        $node = $doc->createElement("tag");

        $newNode = \cPHP\XMLBuilder::importNode( $doc, $node );

        $this->assertSame( $newNode, $node );
    }

    public function testBuildDoc ()
    {
        $doc = new \DOMDocument;

        $node = $doc->createElement("tag");

        $subBuilder = $this->getMock("cPHP\iface\XMLBuilder", array("buildNode"));
        $subBuilder->expects( $this->once() )
            ->method("buildNode")
            ->with( $this->isInstanceOf("DOMDocument") )
            ->will( $this->returnValue($node) );

        $builder = new \cPHP\XMLBuilder($doc, $subBuilder);

        $this->assertSame( $doc, $builder->buildDoc() );

        $this->assertSame( $node, $doc->firstChild );
        $this->assertSame( $doc->firstChild, $doc->lastChild );
    }

    public function testBuildDoc_import ()
    {
        $doc = new \DOMDocument;

        $node = new \DOMElement("tag");

        $subBuilder = $this->getMock("cPHP\iface\XMLBuilder", array("buildNode"));
        $subBuilder->expects( $this->once() )
            ->method("buildNode")
            ->with( $this->isInstanceOf("DOMDocument") )
            ->will( $this->returnValue($node) );

        $builder = new \cPHP\XMLBuilder($doc, $subBuilder);

        $this->assertSame( $doc, $builder->buildDoc() );

        $this->assertNotSame( $node, $doc->firstChild );
        $this->assertSame( $doc->firstChild, $doc->lastChild );

        $this->assertThat( $node, $this->isInstanceOf("DOMElement") );
        $this->assertSame( "tag", $node->tagName );
    }

}

?>