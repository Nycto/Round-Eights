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
class classes_Iterator_DOMNodeList extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a test XPath Object
     *
     * @return DOMXPath
     */
    public function runTestXPath ( $query )
    {
        $doc = new DOMDocument;
        $doc->loadXML(
            '<doc>'
                .'<one />'
                .'<two />'
                .'<three />'
                .'<four />'
            .'</doc>'
        );

        $xpath = new DOMXPath( $doc );
        return $xpath->query( $query );
    }

    public function testIteration_empty ()
    {
        $iterator = new \r8\Iterator\DOMNodeList(
            $this->runTestXPath("/none")
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array(),
            $iterator
        );
    }

    public function testIteration_one ()
    {
        $iterator = new \r8\Iterator\DOMNodeList(
            $this->runTestXPath("/doc")
        );

        $result = PHPUnit_Framework_Constraint_Iterator::iteratorToArray(
            5,
            $iterator
        );

        $this->assertSame( 1, count($result) );
        $this->assertArrayHasKey( 0, $result );
        $this->assertThat( $result[0], $this->isInstanceOf("DOMElement") );
        $this->assertSame( "doc", $result[0]->tagName );
    }

    public function testIteration_many ()
    {
        $iterator = new \r8\Iterator\DOMNodeList(
            $this->runTestXPath("/doc/*")
        );

        $result = PHPUnit_Framework_Constraint_Iterator::iteratorToArray(
            10,
            $iterator
        );

        $this->assertSame( 4, count($result) );

        $this->assertArrayHasKey( 0, $result );
        $this->assertArrayHasKey( 1, $result );
        $this->assertArrayHasKey( 2, $result );
        $this->assertArrayHasKey( 3, $result );

        $this->assertThat( $result[0], $this->isInstanceOf("DOMElement") );
        $this->assertThat( $result[1], $this->isInstanceOf("DOMElement") );
        $this->assertThat( $result[2], $this->isInstanceOf("DOMElement") );
        $this->assertThat( $result[3], $this->isInstanceOf("DOMElement") );

        $this->assertSame( "one", $result[0]->tagName );
        $this->assertSame( "two", $result[1]->tagName );
        $this->assertSame( "three", $result[2]->tagName );
        $this->assertSame( "four", $result[3]->tagName );
    }

    public function testCount ()
    {
        $iterator = new \r8\Iterator\DOMNodeList(
            $this->runTestXPath("/doc/*")
        );
        $this->assertSame( 4, count($iterator) );
        $this->assertSame( 4, $iterator->count() );


        $iterator = new \r8\Iterator\DOMNodeList(
            $this->runTestXPath("/doc")
        );
        $this->assertSame( 1, $iterator->count() );


        $iterator = new \r8\Iterator\DOMNodeList(
            $this->runTestXPath("/none")
        );
        $this->assertSame( 0, $iterator->count() );
    }

    public function testItem_many ()
    {
        $iterator = new \r8\Iterator\DOMNodeList(
            $this->runTestXPath("/doc/*")
        );
        $node = $iterator->item(2);
        $this->assertThat( $node, $this->isInstanceOf("DOMElement") );
        $this->assertSame( "three", $node->tagName );

        $this->assertNull( $iterator->item(50) );
    }

}

?>