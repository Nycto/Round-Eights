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
class classes_XMLBuilder_Quick extends PHPUnit_Framework_TestCase
{

    public function testBuildNode_NULL ()
    {
        $builder = $this->getMock('h2o\XMLBuilder\Quick\Attrs', array('iterate'), array( "null",  NULL ) );

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
        $builder = $this->getMock('h2o\XMLBuilder\Quick\Attrs', array('iterate'), array( "str",  "test" ) );

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
        $builder = $this->getMock('h2o\XMLBuilder\Quick\Attrs', array('iterate'), array( "int",  1234 ) );

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
        $builder = $this->getMock('h2o\XMLBuilder\Quick\Attrs', array('iterate'), array( "float",  12.34 ) );

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
        $builder = $this->getMock('h2o\XMLBuilder\Quick\Attrs', array('iterate'), array( "bool",  TRUE ) );

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

}

?>