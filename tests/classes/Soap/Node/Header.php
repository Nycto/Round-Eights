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
class classes_Soap_Node_Header extends PHPUnit_Framework_TestCase
{

    public function testGetRole_none ()
    {
        $elem = new DOMElement("MessageName");
        $node = new \h2o\Soap\Node\Header( $elem, "soap:uri" );

        $this->assertNull( $node->getRole() );
    }

    public function testGetRole_nsMismatch ()
    {
        $doc = new DOMDocument;
        $elem = $doc->createElement("MessageName");
        $doc->appendChild( $elem );
        $elem->setAttributeNS( "not:soap", "sp:role", "role:uri" );

        $node = new \h2o\Soap\Node\Header( $elem, "soap:uri" );

        $this->assertNull( $node->getRole() );
    }

    public function testGetRole_found ()
    {
        $doc = new DOMDocument;
        $elem = $doc->createElement("MessageName");
        $doc->appendChild( $elem );
        $elem->setAttributeNS( "soap:uri", "sp:role", "role:uri" );

        $node = new \h2o\Soap\Node\Header( $elem, "soap:uri" );

        $this->assertSame( "role:uri", $node->getRole() );
    }

    public function testMustUnderstand_none ()
    {
        $elem = new DOMElement("MessageName");
        $node = new \h2o\Soap\Node\Header( $elem, "soap:uri" );

        $this->assertFalse( $node->mustUnderstand() );
    }

    public function testMustUnderstand_nsMismatch ()
    {
        $doc = new DOMDocument;
        $elem = $doc->createElement("MessageName");
        $doc->appendChild( $elem );
        $elem->setAttributeNS( "not:soap", "sp:mustUnderstand", "true" );

        $node = new \h2o\Soap\Node\Header( $elem, "soap:uri" );

        $this->assertFalse( $node->mustUnderstand() );
    }

    public function testMustUnderstand_true ()
    {
        $doc = new DOMDocument;
        $elem = $doc->createElement("MessageName");
        $doc->appendChild( $elem );
        $elem->setAttributeNS( "soap:uri", "sp:mustUnderstand", "true" );

        $node = new \h2o\Soap\Node\Header( $elem, "soap:uri" );

        $this->assertTrue( $node->mustUnderstand() );
    }

    public function testMustUnderstand_false ()
    {
        $doc = new DOMDocument;
        $elem = $doc->createElement("MessageName");
        $doc->appendChild( $elem );
        $elem->setAttributeNS( "soap:uri", "sp:mustUnderstand", "false" );

        $node = new \h2o\Soap\Node\Header( $elem, "soap:uri" );

        $this->assertFalse( $node->mustUnderstand() );
    }

}

?>