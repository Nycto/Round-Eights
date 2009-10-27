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
class classes_xmlbuilder_series extends PHPUnit_Framework_TestCase
{

    public function testHasChildren ()
    {
        $list = new \h2o\XMLBuilder\Series;
        $this->assertFalse( $list->hasChildren() );

        $list->addChild( new \h2o\XMLBuilder\Node('one') );
        $this->assertTrue( $list->hasChildren() );

        $list->addChild( new \h2o\XMLBuilder\Node('one') );
        $this->assertTrue( $list->hasChildren() );
    }

    public function testBuildNode_empty ()
    {
        $list = new \h2o\XMLBuilder\Series;

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
        $list = new \h2o\XMLBuilder\Series;
        $list->addChild( new \h2o\XMLBuilder\Node('one') );
        $list->addChild( new \h2o\XMLBuilder\Node('two') );
        $list->addChild( new \h2o\XMLBuilder\Node('three') );

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