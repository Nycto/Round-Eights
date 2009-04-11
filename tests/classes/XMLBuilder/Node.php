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
class classes_xmlbuilder_node extends PHPUnit_Framework_TestCase
{

    public function testBuildNode ()
    {
        $doc = new \DOMDocument;

        $builder = new \cPHP\XMLBuilder\Node( "tag" );

        $built = $builder->buildNode( $doc );

        $this->assertSame( "tag", $built->tagName );
        $this->assertSame( $doc, $built->ownerDocument );

        $this->assertSame(
                '<tag/>',
                $doc->saveXML( $built )
            );
    }

    public function testBuildNode_attrs ()
    {
        $doc = new \DOMDocument;

        $builder = new \cPHP\XMLBuilder\Node( "node" );

        $this->assertSame( array(), $builder->getAttributes() );
        $this->assertSame( $builder, $builder->setAttributes(array("one" => "1", "two" => 2)) );

        $this->assertSame(
                array("one" => "1", "two" => 2),
                $builder->getAttributes()
            );


        $built = $builder->buildNode( $doc );

        $this->assertSame( "node", $built->tagName );
        $this->assertSame( $doc, $built->ownerDocument );

        $this->assertTrue( $built->hasAttribute("one") );
        $this->assertSame("1", $built->getAttribute("one"));

        $this->assertTrue( $built->hasAttribute("two") );
        $this->assertSame("2", $built->getAttribute("two"));


        $this->assertSame(
                '<node one="1" two="2"/>',
                $doc->saveXML( $built )
            );
    }

    public function testBuildNode_constructAttrs ()
    {
        $doc = new \DOMDocument;

        $builder = new \cPHP\XMLBuilder\Node(
                "node",
                array("one" => "1", "two" => 2)
            );

        $this->assertSame(
                array("one" => "1", "two" => 2),
                $builder->getAttributes()
            );


        $built = $builder->buildNode( $doc );

        $this->assertSame( "node", $built->tagName );
        $this->assertSame( $doc, $built->ownerDocument );

        $this->assertTrue( $built->hasAttribute("one") );
        $this->assertSame("1", $built->getAttribute("one"));

        $this->assertTrue( $built->hasAttribute("two") );
        $this->assertSame("2", $built->getAttribute("two"));


        $this->assertSame(
                '<node one="1" two="2"/>',
                $doc->saveXML( $built )
            );
    }

}

?>