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

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_XMLBuilder_Quick_Attrs extends PHPUnit_Framework_TestCase
{

    public function testBuildNode_ArrayBasic ()
    {
        $builder = new \r8\XMLBuilder\Quick\Attrs(
        	"ary",
            array( "key" => "value", "key2" => "value2" )
        );

        $doc = new DOMDocument;
        $result = $builder->buildNode( $doc );

        $this->assertThat( $result, $this->isInstanceOf("\DOMElement") );

        $doc->appendChild( $result );

        $this->assertSame(
            '<?xml version="1.0"?>' ."\n"
    		.'<ary key="value" key2="value2"/>' ."\n",
            $doc->saveXML()
         );
    }

    public function testBuildNode_ArrayDepth ()
    {
        $builder = new \r8\XMLBuilder\Quick\Attrs(
        	"ary",
            array(
            	"key" => array(
                    "attr" => "hrmph",
            		"sub" => array(
            			"child" => "data",
            			"stuff" => "info"
                    )
                )
            )
        );

        $doc = new DOMDocument;
        $result = $builder->buildNode( $doc );

        $this->assertThat( $result, $this->isInstanceOf("\DOMElement") );

        $doc->appendChild( $result );

        $this->assertSame(
            '<?xml version="1.0"?>' ."\n"
    		.'<ary><key attr="hrmph"><sub child="data" stuff="info"/></key></ary>' ."\n",
            $doc->saveXML()
         );
    }

    public function testBuildNode_Iterators ()
    {
        $builder = new \r8\XMLBuilder\Quick\Attrs(
        	"iter",
            new ArrayIterator(array(
            	"key" => new ArrayIterator(array(
                    "attr" => "hrmph",
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
    		.'<iter><key attr="hrmph"><sub child="data" stuff="info"/></key></iter>' ."\n",
            $doc->saveXML()
         );
    }

    public function testBuildNode_Empty ()
    {
        $builder = new \r8\XMLBuilder\Quick\Attrs( "empty",  array() );

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

        $subBuilder = $this->getMock('r8\iface\XMLBuilder');
        $subBuilder->expects( $this->once() )
            ->method( "buildNode" )
            ->with( $this->isInstanceOf("DOMDocument") )
            ->will( $this->returnValue($node) );

        $builder = new \r8\XMLBuilder\Quick\Attrs( "build",  $subBuilder );

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

        $subBuilder = $this->getMock('\r8\iface\XMLBuilder');
        $subBuilder->expects( $this->once() )
            ->method( "buildNode" )
            ->with( $this->isInstanceOf("DOMDocument") )
            ->will( $this->returnValue($node) );

        $builder = new \r8\XMLBuilder\Quick\Attrs( "build",  array("parent" => $subBuilder) );

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

        $builder = new \r8\XMLBuilder\Quick\Attrs( "obj",  $obj );

        $doc = new DOMDocument;
        $result = $builder->buildNode( $doc );

        $this->assertThat( $result, $this->isInstanceOf("\DOMElement") );

        $doc->appendChild( $result );

        $this->assertSame(
            '<?xml version="1.0"?>' ."\n"
    		.'<obj child="data" stuff="info" two="blah"/>' ."\n",
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

        $builder = new \r8\XMLBuilder\Quick\Attrs( "obj",  $obj );

        $doc = new DOMDocument;
        $result = $builder->buildNode( $doc );

        $this->assertThat( $result, $this->isInstanceOf("\DOMElement") );

        $doc->appendChild( $result );

        $this->assertSame(
            '<?xml version="1.0"?>' ."\n"
    		.'<obj two="blah"><key><sub child="data" stuff="info"/></key></obj>' ."\n",
            $doc->saveXML()
         );
    }

    public function testBuildNode_ToString ()
    {
        $obj = $this->getMock( 'stdClass', array('__toString') );
        $obj->expects( $this->once() )
            ->method( "__toString" )
            ->will( $this->returnValue( "Data Chunk" ) );

        $builder = new \r8\XMLBuilder\Quick\Attrs( "tostr",  $obj );

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

        $builder = new \r8\XMLBuilder\Quick\Attrs(
        	"tostr",
            array( "tag" => $obj )
        );

        $doc = new DOMDocument;
        $result = $builder->buildNode( $doc );

        $this->assertThat( $result, $this->isInstanceOf("\DOMElement") );

        $doc->appendChild( $result );

        $this->assertSame(
            '<?xml version="1.0"?>' ."\n"
    		.'<tostr tag="Data Chunk"/>' ."\n",
            $doc->saveXML()
         );
    }

    public function testBuildNode_Namespaced ()
    {
        $builder = new \r8\XMLBuilder\Quick\Attrs(
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
    		.'<ary xmlns="test:uri" key="value"/>' ."\n",
            $doc->saveXML()
         );
    }

    public function testBuildNode_InvalidKey ()
    {
        $builder = new \r8\XMLBuilder\Quick\Attrs(
        	"keys",
            array( "  !! @@ &&" => "val", 26 => "number" )
        );

        $doc = new DOMDocument;
        $result = $builder->buildNode( $doc );

        $this->assertThat( $result, $this->isInstanceOf("\DOMElement") );

        $doc->appendChild( $result );

        $this->assertSame(
            '<?xml version="1.0"?>' ."\n"
    		.'<keys unknown="val" numeric_26="number"/>' ."\n",
            $doc->saveXML()
         );
    }

}

?>