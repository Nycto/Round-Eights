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
class classes_XMLBuilder_Quick_Values extends PHPUnit_Framework_TestCase
{

    public function testBuildNode_NULL ()
    {
        $builder = new \h2o\XMLBuilder\Quick\Values( NULL );

        $doc = new DOMDocument;
        $result = $builder->buildNode( $doc );

        $this->assertThat( $result, $this->isInstanceOf("\DOMText") );
        $this->assertSame( "", $result->wholeText );
    }

    public function testBuildNode_String ()
    {
        $builder = new \h2o\XMLBuilder\Quick\Values( "test" );

        $doc = new DOMDocument;
        $result = $builder->buildNode( $doc );

        $this->assertThat( $result, $this->isInstanceOf("\DOMText") );
        $this->assertSame( "test", $result->wholeText );
    }

    public function testBuildNode_Integer ()
    {
        $builder = new \h2o\XMLBuilder\Quick\Values( 1234 );

        $doc = new DOMDocument;
        $result = $builder->buildNode( $doc );

        $this->assertThat( $result, $this->isInstanceOf("\DOMText") );
        $this->assertSame( "1234", $result->wholeText );
    }

    public function testBuildNode_Float ()
    {
        $builder = new \h2o\XMLBuilder\Quick\Values( 12.34 );

        $doc = new DOMDocument;
        $result = $builder->buildNode( $doc );

        $this->assertThat( $result, $this->isInstanceOf("\DOMText") );
        $this->assertSame( "12.34", $result->wholeText );
    }

    public function testBuildNode_Boolean ()
    {
        $builder = new \h2o\XMLBuilder\Quick\Values( TRUE );

        $doc = new DOMDocument;
        $result = $builder->buildNode( $doc );

        $this->assertThat( $result, $this->isInstanceOf("\DOMText") );
        $this->assertSame( "1", $result->wholeText );
    }

    public function testBuildNode_ArrayBasic ()
    {
        $builder = new \h2o\XMLBuilder\Quick\Values(
            array( "key" => "value", "key2" => "value2" )
        );

        $doc = new DOMDocument;
        $result = $builder->buildNode( $doc );

        $this->assertThat( $result, $this->isInstanceOf("\DOMDocumentFragment") );

        $doc->appendChild( $result );

        $this->assertSame(
            '<?xml version="1.0"?>' ."\n"
    		.'<key>value</key>' ."\n"
    		.'<key2>value2</key2>' ."\n",
            $doc->saveXML()
         );
    }

    public function testBuildNode_ArrayDepth ()
    {
        $builder = new \h2o\XMLBuilder\Quick\Values(
            array( "key" => array( "sub" => array( "child" => "data", "stuff" => "info" ) ) )
        );

        $doc = new DOMDocument;
        $result = $builder->buildNode( $doc );

        $this->assertThat( $result, $this->isInstanceOf("\DOMElement") );

        $doc->appendChild( $result );

        $this->assertSame(
            '<?xml version="1.0"?>' ."\n"
    		.'<key><sub><child>data</child><stuff>info</stuff></sub></key>' ."\n",
            $doc->saveXML()
         );
    }

    public function testBuildNode_Iterators ()
    {
        $builder = new \h2o\XMLBuilder\Quick\Values(
            new ArrayIterator(array(
            	"key" => new ArrayIterator(array(
            		"sub" => new ArrayIterator(array(
            			"child" => "data",
            			"stuff" => "info"
                    ))
                ))
            ))
        );

        $doc = new DOMDocument;
        $result = $builder->buildNode( $doc );

        $this->assertThat( $result, $this->isInstanceOf("\DOMElement") );

        $doc->appendChild( $result );

        $this->assertSame(
            '<?xml version="1.0"?>' ."\n"
    		.'<key><sub><child>data</child><stuff>info</stuff></sub></key>' ."\n",
            $doc->saveXML()
         );
    }

    public function testBuildNode_Empty ()
    {
        $builder = new \h2o\XMLBuilder\Quick\Values( array() );

        $doc = new DOMDocument;
        $result = $builder->buildNode( $doc );

        $this->assertThat( $result, $this->isInstanceOf("\DOMText") );

        $doc->appendChild( $result );

        $this->assertSame(
            '<?xml version="1.0"?>' ."\n\n",
            $doc->saveXML()
        );
    }

    public function testBuildNode_XMLBuilder ()
    {
        $doc = new DOMDocument;
        $node = $doc->createElement("test");

        $subBuilder = $this->getMock('h2o\iface\XMLBuilder');
        $subBuilder->expects( $this->once() )
            ->method( "buildNode" )
            ->with( $this->isInstanceOf("DOMDocument") )
            ->will( $this->returnValue($node) );

        $builder = new \h2o\XMLBuilder\Quick\Values( $subBuilder );

        $this->assertSame( $node, $builder->buildNode($doc) );
    }

    public function testBuildNode_nestedXMLBuilder ()
    {
        $doc = new DOMDocument;
        $node = $doc->createElement("test");

        $subBuilder = $this->getMock('\h2o\iface\XMLBuilder');
        $subBuilder->expects( $this->once() )
            ->method( "buildNode" )
            ->with( $this->isInstanceOf("DOMDocument") )
            ->will( $this->returnValue($node) );

        $builder = new \h2o\XMLBuilder\Quick\Values( array("parent" => $subBuilder) );

        $result = $builder->buildNode( $doc );

        $this->assertThat( $result, $this->isInstanceOf("\DOMElement") );

        $doc->appendChild( $result );

        $this->assertSame(
            '<?xml version="1.0"?>' ."\n"
    		.'<parent><test/></parent>' ."\n",
            $doc->saveXML()
         );
    }

    public function testBuildNode_ObjectProps ()
    {
        $obj = new stdClass;
        $obj->key = new stdClass;
        $obj->key->sub = new stdClass;
        $obj->key->sub->child = "data";
        $obj->key->sub->stuff = "info";
        $obj->two = "blah";

        $builder = new \h2o\XMLBuilder\Quick\Values( $obj );

        $doc = new DOMDocument;
        $result = $builder->buildNode( $doc );

        $this->assertThat( $result, $this->isInstanceOf("\DOMDocumentFragment") );

        $doc->appendChild( $result );

        $this->assertSame(
            '<?xml version="1.0"?>' ."\n"
    		.'<key><sub><child>data</child><stuff>info</stuff></sub></key>' ."\n"
    		.'<two>blah</two>' ."\n",
            $doc->saveXML()
         );
    }

}

?>