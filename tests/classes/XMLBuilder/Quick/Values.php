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
        $builder = new \h2o\XMLBuilder\Quick\Values( "null",  NULL );

        $doc = new DOMDocument;
        $result = $builder->buildNode( $doc );

        $this->assertThat( $result, $this->isInstanceOf("\DOMElement") );

        $doc->appendChild( $result );

        $this->assertSame(
            '<?xml version="1.0"?>' ."\n"
    		.'<null/>' ."\n",
            $doc->saveXML()
         );
    }

    public function testBuildNode_String ()
    {
        $builder = new \h2o\XMLBuilder\Quick\Values( "str",  "test" );

        $doc = new DOMDocument;
        $result = $builder->buildNode( $doc );

        $this->assertThat( $result, $this->isInstanceOf("\DOMElement") );

        $doc->appendChild( $result );

        $this->assertSame(
            '<?xml version="1.0"?>' ."\n"
    		.'<str>test</str>' ."\n",
            $doc->saveXML()
         );
    }

    public function testBuildNode_Integer ()
    {
        $builder = new \h2o\XMLBuilder\Quick\Values( "int",  1234 );

        $doc = new DOMDocument;
        $result = $builder->buildNode( $doc );

        $this->assertThat( $result, $this->isInstanceOf("\DOMElement") );

        $doc->appendChild( $result );

        $this->assertSame(
            '<?xml version="1.0"?>' ."\n"
    		.'<int>1234</int>' ."\n",
            $doc->saveXML()
         );
    }

    public function testBuildNode_Float ()
    {
        $builder = new \h2o\XMLBuilder\Quick\Values( "float",  12.34 );

        $doc = new DOMDocument;
        $result = $builder->buildNode( $doc );

        $this->assertThat( $result, $this->isInstanceOf("\DOMElement") );

        $doc->appendChild( $result );

        $this->assertSame(
            '<?xml version="1.0"?>' ."\n"
    		.'<float>12.34</float>' ."\n",
            $doc->saveXML()
         );
    }

    public function testBuildNode_Boolean ()
    {
        $builder = new \h2o\XMLBuilder\Quick\Values( "bool",  TRUE );

        $doc = new DOMDocument;
        $result = $builder->buildNode( $doc );

        $this->assertThat( $result, $this->isInstanceOf("\DOMElement") );

        $doc->appendChild( $result );

        $this->assertSame(
            '<?xml version="1.0"?>' ."\n"
    		.'<bool>1</bool>' ."\n",
            $doc->saveXML()
         );
    }

    public function testBuildNode_ArrayBasic ()
    {
        $builder = new \h2o\XMLBuilder\Quick\Values(
        	"ary",
            array( "key" => "value", "key2" => "value2" )
        );

        $doc = new DOMDocument;
        $result = $builder->buildNode( $doc );

        $this->assertThat( $result, $this->isInstanceOf("\DOMElement") );

        $doc->appendChild( $result );

        $this->assertSame(
            '<?xml version="1.0"?>' ."\n"
    		.'<ary><key>value</key><key2>value2</key2></ary>' ."\n",
            $doc->saveXML()
         );
    }

    public function testBuildNode_ArrayDepth ()
    {
        $builder = new \h2o\XMLBuilder\Quick\Values(
        	"ary",
            array( "key" => array( "sub" => array( "child" => "data", "stuff" => "info" ) ) )
        );

        $doc = new DOMDocument;
        $result = $builder->buildNode( $doc );

        $this->assertThat( $result, $this->isInstanceOf("\DOMElement") );

        $doc->appendChild( $result );

        $this->assertSame(
            '<?xml version="1.0"?>' ."\n"
    		.'<ary><key><sub><child>data</child><stuff>info</stuff></sub></key></ary>' ."\n",
            $doc->saveXML()
         );
    }

    public function testBuildNode_Iterators ()
    {
        $builder = new \h2o\XMLBuilder\Quick\Values(
        	"iter",
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
    		.'<iter><key><sub><child>data</child><stuff>info</stuff></sub></key></iter>' ."\n",
            $doc->saveXML()
         );
    }

    public function testBuildNode_Empty ()
    {
        $builder = new \h2o\XMLBuilder\Quick\Values( "empty",  array() );

        $doc = new DOMDocument;
        $result = $builder->buildNode( $doc );

        $this->assertThat( $result, $this->isInstanceOf("\DOMElement") );

        $doc->appendChild( $result );

        $this->assertSame(
            '<?xml version="1.0"?>' ."\n"
            .'<empty/>' ."\n",
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

        $builder = new \h2o\XMLBuilder\Quick\Values( "build",  $subBuilder );

        $result = $builder->buildNode( $doc );

        $this->assertThat( $result, $this->isInstanceOf("\DOMElement") );

        $doc->appendChild( $result );

        $this->assertSame(
            '<?xml version="1.0"?>' ."\n"
    		.'<build><test/></build>' ."\n",
            $doc->saveXML()
         );
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

        $builder = new \h2o\XMLBuilder\Quick\Values( "build",  array("parent" => $subBuilder) );

        $result = $builder->buildNode( $doc );

        $this->assertThat( $result, $this->isInstanceOf("\DOMElement") );

        $doc->appendChild( $result );

        $this->assertSame(
            '<?xml version="1.0"?>' ."\n"
    		.'<build><parent><test/></parent></build>' ."\n",
            $doc->saveXML()
         );
    }

    public function testBuildNode_ObjectProps ()
    {
        $obj = new stdClass;
        $obj->child = "data";
        $obj->stuff = "info";
        $obj->two = "blah";

        $builder = new \h2o\XMLBuilder\Quick\Values( "obj",  $obj );

        $doc = new DOMDocument;
        $result = $builder->buildNode( $doc );

        $this->assertThat( $result, $this->isInstanceOf("\DOMElement") );

        $doc->appendChild( $result );

        $this->assertSame(
            '<?xml version="1.0"?>' ."\n"
    		.'<obj><child>data</child><stuff>info</stuff><two>blah</two></obj>' ."\n",
            $doc->saveXML()
         );
    }

    public function testBuildNode_NestedObjectProps ()
    {
        $obj = new stdClass;
        $obj->key = new stdClass;
        $obj->key->sub = new stdClass;
        $obj->key->sub->child = "data";
        $obj->key->sub->stuff = "info";
        $obj->two = "blah";

        $builder = new \h2o\XMLBuilder\Quick\Values( "obj",  $obj );

        $doc = new DOMDocument;
        $result = $builder->buildNode( $doc );

        $this->assertThat( $result, $this->isInstanceOf("\DOMElement") );

        $doc->appendChild( $result );

        $this->assertSame(
            '<?xml version="1.0"?>' ."\n"
    		.'<obj><key><sub><child>data</child><stuff>info</stuff></sub></key><two>blah</two></obj>' ."\n",
            $doc->saveXML()
         );
    }

    public function testBuildNode_ToString ()
    {
        $obj = $this->getMock( 'stdClass', array('__toString') );
        $obj->expects( $this->once() )
            ->method( "__toString" )
            ->will( $this->returnValue( "Data Chunk" ) );

        $builder = new \h2o\XMLBuilder\Quick\Values( "tostr",  $obj );

        $doc = new DOMDocument;
        $result = $builder->buildNode( $doc );

        $this->assertThat( $result, $this->isInstanceOf("\DOMElement") );

        $doc->appendChild( $result );

        $this->assertSame(
            '<?xml version="1.0"?>' ."\n"
    		.'<tostr>Data Chunk</tostr>' ."\n",
            $doc->saveXML()
         );
    }

    public function testBuildNode_NestedToString ()
    {
        $obj = $this->getMock( 'stdClass', array('__toString') );
        $obj->expects( $this->once() )
            ->method( "__toString" )
            ->will( $this->returnValue( "Data Chunk" ) );

        $builder = new \h2o\XMLBuilder\Quick\Values(
        	"tostr",
            array( "tag" => $obj )
        );

        $doc = new DOMDocument;
        $result = $builder->buildNode( $doc );

        $this->assertThat( $result, $this->isInstanceOf("\DOMElement") );

        $doc->appendChild( $result );

        $this->assertSame(
            '<?xml version="1.0"?>' ."\n"
    		.'<tostr><tag>Data Chunk</tag></tostr>' ."\n",
            $doc->saveXML()
         );
    }

    public function testBuildNode_Namespaced ()
    {
        $builder = new \h2o\XMLBuilder\Quick\Values(
        	"ary",
            array( "key" => "value" ),
            "test:uri"
        );

        $doc = new DOMDocument;
        $result = $builder->buildNode( $doc );

        $this->assertThat( $result, $this->isInstanceOf("\DOMElement") );

        $doc->appendChild( $result );

        $this->assertSame(
            '<?xml version="1.0"?>' ."\n"
    		.'<ary xmlns="test:uri"><key>value</key></ary>' ."\n",
            $doc->saveXML()
         );
    }

    public function testBuildNode_InvalidKey ()
    {
        $builder = new \h2o\XMLBuilder\Quick\Values(
        	"keys",
            array( "  !! @@ &&" => "val", 26 => "number" )
        );

        $doc = new DOMDocument;
        $result = $builder->buildNode( $doc );

        $this->assertThat( $result, $this->isInstanceOf("\DOMElement") );

        $doc->appendChild( $result );

        $this->assertSame(
            '<?xml version="1.0"?>' ."\n"
    		.'<keys><unknown>val</unknown><numeric_26>number</numeric_26></keys>' ."\n",
            $doc->saveXML()
         );
    }

    public function testBuildNode_List ()
    {
        $builder = new \h2o\XMLBuilder\Quick\Values(
        	"list",
            array(
                "item" => array(
                	array( "key" => "value" ),
                	"string",
                	array( "other" => "thing" )
                )
            )
        );

        $doc = new DOMDocument;
        $result = $builder->buildNode( $doc );

        $this->assertThat( $result, $this->isInstanceOf("\DOMElement") );

        $doc->appendChild( $result );

        $this->assertSame(
            '<?xml version="1.0"?>' ."\n"
    		.'<list><item><key>value</key></item><item>string</item><item><other>thing</other></item></list>' ."\n",
            $doc->saveXML()
         );
    }

}

?>