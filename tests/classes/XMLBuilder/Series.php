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

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_xmlbuilder_series extends PHPUnit_Framework_TestCase
{

    public function testChildren ()
    {
        $list = new \r8\XMLBuilder\Series;
        $this->assertFalse( $list->hasChildren() );
        $this->assertSame( array(), $list->getChildren() );

        $one = $this->getMock('r8\iface\XMLBuilder');
        $this->assertSame( $list, $list->addChild( $one ) );
        $this->assertSame( array($one), $list->getChildren() );
        $this->assertTrue( $list->hasChildren() );

        $two = $this->getMock('r8\iface\XMLBuilder');
        $this->assertSame( $list, $list->addChild( $two ) );
        $this->assertSame( array($one, $two), $list->getChildren() );
        $this->assertTrue( $list->hasChildren() );
    }

    public function testBuildNode_empty ()
    {
        $list = new \r8\XMLBuilder\Series;

        $doc = new DOMDocument;

        $node = $list->buildNode( $doc );
        $this->assertThat( $node, $this->isInstanceOf("DOMText") );

        $doc->appendChild( $node );

        $this->assertSame(
        	'<?xml version="1.0"?>' ."\n\n",
            $doc->saveXML()
        );
    }

    public function testBuildNode_one ()
    {
        $list = new \r8\XMLBuilder\Series;
        $list->addChild( new \r8\XMLBuilder\Node('one') );
        $list->addChild( new \r8\XMLBuilder\Node('two') );
        $list->addChild( new \r8\XMLBuilder\Node('three') );

        $doc = new DOMDocument;

        $node = $list->buildNode( $doc );
        $this->assertThat( $node, $this->isInstanceOf("DOMDocumentFragment") );

        $doc->appendChild( $node );

        $this->assertSame(
        	'<?xml version="1.0"?>' ."\n"
            .'<one/>' ."\n"
            .'<two/>' ."\n"
            .'<three/>' ."\n",
            $doc->saveXML()
        );
    }

}

?>