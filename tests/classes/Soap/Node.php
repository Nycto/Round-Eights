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

}

?>