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
class classes_Soap_Node extends PHPUnit_Framework_TestCase
{

    public function testGetNode ()
    {
        $elem = new DOMElement("MessageName");
        $node = $this->getMock( "\h2o\Soap\Node", array('_mock'), array($elem) );

        $this->assertSame( $elem, $node->getElement() );
    }

    public function testGetTag_namespaced ()
    {
        $elem = new DOMElement("msg:MessageName", null, "unit:test");
        $node = $this->getMock( "\h2o\Soap\Node", array('_mock'), array($elem) );
        $this->assertSame( "MessageName", $node->getTag() );
    }

    public function testGetTag_NoNamespaced ()
    {
        $elem = new DOMElement("MessageName");
        $node = $this->getMock( "\h2o\Soap\Node", array('_mock'), array($elem) );
        $this->assertSame( "MessageName", $node->getTag() );
    }

    public function testGetPrefix_none ()
    {
        $elem = new DOMElement("MessageName");
        $node = $this->getMock( "\h2o\Soap\Node", array('_mock'), array($elem) );
        $this->assertNull( $node->getPrefix() );
    }

    public function testGetPrefix_empty ()
    {
        $elem = new DOMElement(":MessageName");
        $node = $this->getMock( "\h2o\Soap\Node", array('_mock'), array($elem) );
        $this->assertNull( $node->getPrefix() );
    }

    public function testGetPrefix_Namespaced ()
    {
        $elem = new DOMElement("msg:MessageName", null, "unit:test");
        $node = $this->getMock( "\h2o\Soap\Node", array('_mock'), array($elem) );
        $this->assertSame( "msg", $node->getPrefix() );
    }

    public function testGetNamespace_none ()
    {
        $elem = new DOMElement("MessageName");
        $node = $this->getMock( "\h2o\Soap\Node", array('_mock'), array($elem) );
        $this->assertNull( $node->getNamespace() );
    }

    public function testGetNamespace_namespaced ()
    {
        $elem = new DOMElement("msg:MessageName", null, "unit:test");
        $node = $this->getMock( "\h2o\Soap\Node", array('_mock'), array($elem) );
        $this->assertSame( "unit:test", $node->getNamespace() );
    }

    public function testGetNamespace_noPrefix ()
    {
        $doc = new DOMDocument;
        $elem = $doc->createElementNS("unit:test", "root");
        $doc->appendChild( $elem );

        $node = $this->getMock( "\h2o\Soap\Node", array('_mock'), array($elem) );
        $this->assertSame( "unit:test", $node->getNamespace() );
    }

    public function testGetNamespace_default ()
    {
        $doc = new DOMDocument;
        $root = $doc->createElementNS("unit:test", "root");
        $doc->appendChild( $root );

        $elem = $doc->createElement("child");
        $root->appendChild( $elem );

        $node = $this->getMock( "\h2o\Soap\Node", array('_mock'), array($elem) );
        $this->assertSame( "unit:test", $node->getNamespace() );
    }

}

?>