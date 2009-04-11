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
class classes_xmlbuilder_wrap extends PHPUnit_Framework_TestCase
{

    public function testBuildNode ()
    {
        $doc = new \DOMDocument;

        $rootNode = $doc->createElement("root");

        $subNode = $doc->createElement("sub");
        $subBuilder = $this->getMock("cPHP\iface\XMLBuilder", array("buildNode"));
        $subBuilder->expects( $this->once() )
            ->method("buildNode")
            ->with( $this->isInstanceOf("DOMDocument") )
            ->will( $this->returnValue($subNode) );


        $builder = new \cPHP\XMLBuilder\Wrap( $subBuilder, $rootNode );

        $this->assertSame( $rootNode, $builder->buildNode( $doc ) );

        $this->assertSame( $subNode, $rootNode->firstChild );
        $this->assertSame( $rootNode->firstChild, $rootNode->lastChild );
    }

    public function testBuildNode_attrs ()
    {
        $doc = new \DOMDocument;

        $rootNode = $doc->createElement("root");

        $subNode = $doc->createElement("sub");
        $subBuilder = $this->getMock("cPHP\iface\XMLBuilder", array("buildNode"));
        $subBuilder->expects( $this->once() )
            ->method("buildNode")
            ->with( $this->isInstanceOf("DOMDocument") )
            ->will( $this->returnValue($subNode) );


        $builder = new \cPHP\XMLBuilder\Wrap( $subBuilder, $rootNode );

        $this->assertSame( array(), $builder->getAttributes() );
        $this->assertSame( $builder, $builder->setAttributes(array("one" => "1", "two" => 2)) );

        $this->assertSame( $rootNode, $builder->buildNode( $doc ) );

        $this->assertSame( $subNode, $rootNode->firstChild );
        $this->assertSame( $rootNode->firstChild, $rootNode->lastChild );

        $this->assertTrue( $rootNode->hasAttribute("one") );
        $this->assertSame("1", $rootNode->getAttribute("one"));

        $this->assertTrue( $rootNode->hasAttribute("two") );
        $this->assertSame("2", $rootNode->getAttribute("two"));
    }

}

?>