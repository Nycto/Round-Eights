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

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_xmlbuilder_wrap extends PHPUnit_Framework_TestCase
{

    public function testBuildNode ()
    {
        $doc = new \DOMDocument;

        $subNode = $doc->createElement("sub");
        $subBuilder = $this->getMock("r8\iface\XMLBuilder", array("buildNode"));
        $subBuilder->expects( $this->once() )
            ->method("buildNode")
            ->with( $this->isInstanceOf("DOMDocument") )
            ->will( $this->returnValue($subNode) );


        $builder = new \r8\XMLBuilder\Wrap( $subBuilder, "tag" );

        $built = $builder->buildNode( $doc );

        $this->assertSame( "tag", $built->tagName );
        $this->assertSame( $doc, $built->ownerDocument );

        $this->assertSame( $subNode, $built->firstChild );
        $this->assertSame( $built->firstChild, $built->lastChild );


        $this->assertSame(
                '<tag><sub/></tag>',
                $doc->saveXML( $built )
            );
    }

    public function testBuildNode_attrs ()
    {
        $doc = new \DOMDocument;

        $subNode = $doc->createElement("sub");
        $subBuilder = $this->getMock("r8\iface\XMLBuilder", array("buildNode"));
        $subBuilder->expects( $this->once() )
            ->method("buildNode")
            ->with( $this->isInstanceOf("DOMDocument") )
            ->will( $this->returnValue($subNode) );


        $builder = new \r8\XMLBuilder\Wrap( $subBuilder, "wrap" );

        $this->assertSame( array(), $builder->getAttributes() );
        $this->assertSame( $builder, $builder->setAttributes(array("one" => "1", "two" => 2)) );

        $this->assertSame(
                array("one" => "1", "two" => 2),
                $builder->getAttributes()
            );


        $built = $builder->buildNode( $doc );

        $this->assertSame( "wrap", $built->tagName );
        $this->assertSame( $doc, $built->ownerDocument );


        $this->assertSame( $subNode, $built->firstChild );
        $this->assertSame( $built->firstChild, $built->lastChild );

        $this->assertTrue( $built->hasAttribute("one") );
        $this->assertSame("1", $built->getAttribute("one"));

        $this->assertTrue( $built->hasAttribute("two") );
        $this->assertSame("2", $built->getAttribute("two"));


        $this->assertSame(
                '<wrap one="1" two="2"><sub/></wrap>',
                $doc->saveXML( $built )
            );
    }

    public function testBuildNode_constructAttrs ()
    {
        $doc = new \DOMDocument;

        $subNode = $doc->createElement("sub");
        $subBuilder = $this->getMock("r8\iface\XMLBuilder", array("buildNode"));
        $subBuilder->expects( $this->once() )
            ->method("buildNode")
            ->with( $this->isInstanceOf("DOMDocument") )
            ->will( $this->returnValue($subNode) );


        $builder = new \r8\XMLBuilder\Wrap(
                $subBuilder,
                "wrap",
                array("one" => "1", "two" => 2)
            );

        $this->assertSame(
                array("one" => "1", "two" => 2),
                $builder->getAttributes()
            );

        $built = $builder->buildNode( $doc );

        $this->assertSame( "wrap", $built->tagName );
        $this->assertSame( $doc, $built->ownerDocument );


        $this->assertSame( $subNode, $built->firstChild );
        $this->assertSame( $built->firstChild, $built->lastChild );

        $this->assertTrue( $built->hasAttribute("one") );
        $this->assertSame("1", $built->getAttribute("one"));

        $this->assertTrue( $built->hasAttribute("two") );
        $this->assertSame("2", $built->getAttribute("two"));


        $this->assertSame(
                '<wrap one="1" two="2"><sub/></wrap>',
                $doc->saveXML( $built )
            );
    }

}

?>