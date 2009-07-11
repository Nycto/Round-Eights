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
class classes_xmlbuilder_append extends PHPUnit_Framework_TestCase
{

    public function testBuildNode ()
    {
        $doc = new \DOMDocument;

        $parent = $doc->createElement("parent");
        $parentBuilder = $this->getMock("h2o\iface\XMLBuilder", array("buildNode"));
        $parentBuilder->expects( $this->once() )
            ->method("buildNode")
            ->with( $this->isInstanceOf("DOMDocument") )
            ->will( $this->returnValue($parent) );

        $builder = new \h2o\XMLBuilder\Append( $parentBuilder );

        $built = $builder->buildNode( $doc );

        $this->assertSame( $parent, $built );

        $this->assertSame(
                '<parent/>',
                $doc->saveXML( $built )
            );
    }

    public function testBuildNode_oneChild ()
    {
        $doc = new \DOMDocument;

        $parent = $doc->createElement("parent");
        $parentBuilder = $this->getMock("h2o\iface\XMLBuilder", array("buildNode"));
        $parentBuilder->expects( $this->once() )
            ->method("buildNode")
            ->with( $this->isInstanceOf("DOMDocument") )
            ->will( $this->returnValue($parent) );

        $builder = new \h2o\XMLBuilder\Append( $parentBuilder );


        $child = $doc->createElement("child");
        $childBuilder = $this->getMock("h2o\iface\XMLBuilder", array("buildNode"));
        $childBuilder->expects( $this->once() )
            ->method("buildNode")
            ->with( $this->isInstanceOf("DOMDocument") )
            ->will( $this->returnValue($child) );

        $this->assertSame( $builder, $builder->addChild( $childBuilder ) );


        $built = $builder->buildNode( $doc );

        $this->assertSame( $parent, $built );

        $this->assertSame(
                '<parent><child/></parent>',
                $doc->saveXML( $built )
            );
    }

    public function testBuildNode_multiChild ()
    {
        $doc = new \DOMDocument;

        $parent = $doc->createElement("parent");
        $parentBuilder = $this->getMock("h2o\iface\XMLBuilder", array("buildNode"));
        $parentBuilder->expects( $this->once() )
            ->method("buildNode")
            ->with( $this->isInstanceOf("DOMDocument") )
            ->will( $this->returnValue($parent) );

        $builder = new \h2o\XMLBuilder\Append( $parentBuilder );


        $child1 = $doc->createElement("child1");
        $child1Builder = $this->getMock("h2o\iface\XMLBuilder", array("buildNode"));
        $child1Builder->expects( $this->once() )
            ->method("buildNode")
            ->with( $this->isInstanceOf("DOMDocument") )
            ->will( $this->returnValue($child1) );

        $this->assertSame( $builder, $builder->addChild( $child1Builder ) );


        $child2 = $doc->createElement("child2");
        $child2Builder = $this->getMock("h2o\iface\XMLBuilder", array("buildNode"));
        $child2Builder->expects( $this->once() )
            ->method("buildNode")
            ->with( $this->isInstanceOf("DOMDocument") )
            ->will( $this->returnValue($child2) );

        $this->assertSame( $builder, $builder->addChild( $child2Builder ) );


        $child3 = $doc->createElement("child3");
        $child3Builder = $this->getMock("h2o\iface\XMLBuilder", array("buildNode"));
        $child3Builder->expects( $this->once() )
            ->method("buildNode")
            ->with( $this->isInstanceOf("DOMDocument") )
            ->will( $this->returnValue($child3) );

        $this->assertSame( $builder, $builder->addChild( $child3Builder ) );


        $built = $builder->buildNode( $doc );

        $this->assertSame( $parent, $built );

        $this->assertSame(
                '<parent><child1/><child2/><child3/></parent>',
                $doc->saveXML( $built )
            );
    }

}

?>