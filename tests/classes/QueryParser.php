<?php
/**
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

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * unit tests
 */
class classes_queryparser extends PHPUnit_Framework_TestCase
{

    public function testOuterDelimAccessors ()
    {
        $parser = new \h2o\QueryParser;

        $this->assertSame( "&", $parser->getOuterDelim() );

        $this->assertSame( $parser, $parser->setOuterDelim(";=;") );
        $this->assertSame( ";=;", $parser->getOuterDelim() );

        $this->assertSame( $parser, $parser->setOuterDelim(" ") );
        $this->assertSame( " ", $parser->getOuterDelim() );

        try {
            $parser->setOuterDelim("");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }

        $this->assertSame( " ", $parser->getOuterDelim() );
    }

    public function testInnerDelimAccessors ()
    {
        $parser = new \h2o\QueryParser;

        $this->assertSame( "=", $parser->getInnerDelim() );

        $this->assertSame( $parser, $parser->setInnerDelim(";=;") );
        $this->assertSame( ";=;", $parser->getInnerDelim() );

        $this->assertSame( $parser, $parser->setInnerDelim(" ") );
        $this->assertSame( " ", $parser->getInnerDelim() );

        try {
            $parser->setInnerDelim("");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }

        $this->assertSame( " ", $parser->getInnerDelim() );
    }

    public function testStartDelimAccessors ()
    {
        $parser = new \h2o\QueryParser;

        $this->assertSame( "?", $parser->getStartDelim() );
        $this->assertTrue( $parser->startDelimExists() );

        $this->assertSame( $parser, $parser->setStartDelim(";=;") );
        $this->assertSame( ";=;", $parser->getStartDelim() );
        $this->assertTrue( $parser->startDelimExists() );

        $this->assertSame( $parser, $parser->setStartDelim("") );
        $this->assertNull( $parser->getStartDelim() );
        $this->assertFalse( $parser->startDelimExists() );

        $this->assertSame( $parser, $parser->setStartDelim(" ") );
        $this->assertSame( " ", $parser->getStartDelim() );
        $this->assertTrue( $parser->startDelimExists() );

        $this->assertSame( $parser, $parser->clearStartDelim() );
        $this->assertNull( $parser->getStartDelim() );
        $this->assertFalse( $parser->startDelimExists() );
    }

    public function testEndDelimAccessors ()
    {
        $parser = new \h2o\QueryParser;

        $this->assertSame( "#", $parser->getEndDelim() );
        $this->assertTrue( $parser->endDelimExists() );

        $this->assertSame( $parser, $parser->setEndDelim(";=;") );
        $this->assertSame( ";=;", $parser->getEndDelim() );
        $this->assertTrue( $parser->endDelimExists() );

        $this->assertSame( $parser, $parser->setEndDelim("") );
        $this->assertNull( $parser->getEndDelim() );
        $this->assertFalse( $parser->endDelimExists() );

        $this->assertSame( $parser, $parser->setEndDelim(" ") );
        $this->assertSame( " ", $parser->getEndDelim() );
        $this->assertTrue( $parser->endDelimExists() );

        $this->assertSame( $parser, $parser->clearEndDelim() );
        $this->assertNull( $parser->getEndDelim() );
        $this->assertFalse( $parser->endDelimExists() );
    }

    public function testSubRegExAccessors ()
    {
        $parser = new \h2o\QueryParser;

        $this->assertSame( '/\[(.*?)\]/', $parser->getSubRegEx() );

        $this->assertSame( $parser, $parser->setSubRegEx("`.`") );
        $this->assertSame( "`.`", $parser->getSubRegEx() );

        try {
            $parser->setSubRegEx("   ");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }

        $this->assertSame( "`.`", $parser->getSubRegEx() );
    }

    public function testKeyFilterAccessors ()
    {
        $parser = new \h2o\QueryParser;

        $this->assertThat($parser->getKeyFilter(), $this->isInstanceOf('h2o\Curry\Call'));

        $filter = $this->getMock('h2o\iface\Filter', array('filter'));
        $this->assertSame( $parser, $parser->setKeyFilter($filter) );
        $this->assertSame( $filter, $parser->getKeyFilter() );
    }

    public function testValueFilterAccessors ()
    {
        $parser = new \h2o\QueryParser;

        $this->assertThat($parser->getValueFilter(), $this->isInstanceOf('h2o\Curry\Call'));

        $filter = $this->getMock('h2o\iface\Filter', array('filter'));
        $this->assertSame( $parser, $parser->setValueFilter($filter) );
        $this->assertSame( $filter, $parser->getValueFilter() );
    }

    public function testParse_standards ()
    {
        $parser = new \h2o\QueryParser;

        $this->assertSame(
                array ( "key" => "value", "key2" => array('sub' => array('sub2' => "value3")) ),
                $parser->parse( "url.com?key=value&key2[sub][sub2]=value3#fragment" )
            );

        $this->assertSame(
                array ( "key" => "value", "key2" => array('sub' => array('sub2' => "value3")) ),
                $parser->parse( "key=value&key2[sub][sub2]=value3" )
            );

        $this->assertSame(
                array ( "key" => "value", "key2" =>  "value3", "k3" => "other" ),
                $parser->parse( "key=value&key2=value3&k3=other" )
            );

        $this->assertSame(
                array ( "key" => array("value", "value2", "value3") ),
                $parser->parse( "key[]=value&key[]=value2&key[]=value3" )
            );

        $this->assertSame(
                array ( "key more" => "value for decoding" ),
                $parser->parse( "key%20more=value%20for%20decoding" )
            );
    }

    public function testParse_fringe ()
    {
        $parser = new \h2o\QueryParser;

        $this->assertSame(
                array (),
                $parser->parse( "" )
            );

        $this->assertSame(
                array ("key" => "value", "key2" => ""),
                $parser->parse( "key=value&key2" )
            );

        $this->assertSame(
                array ("key" => "value2"),
                $parser->parse( "key=value&key=value2" )
            );

        $this->assertSame(
                array ("key" => "value"),
                $parser->parse( "key=value&&&   &" )
            );

        $this->assertSame(
                array (),
                $parser->parse( "=value" )
            );

        $this->assertSame(
                array ("key" => "value"),
                $parser->parse( "[key]=value" )
            );

        $this->assertSame(
                array ("key  " => array("key" => "value")),
                $parser->parse( "key  [key]=value" )
            );

        $this->assertSame(
                array ("key" => array("   " => "value")),
                $parser->parse( "key[   ]=value" )
            );

        $this->assertSame(
                array ("key  " => array("key" => array("key" => array("key" => "value")))),
                $parser->parse( "key  [key]  [key] [key]=value" )
            );

        $this->assertSame(
                array ("key" => array("  key  " => "value")),
                $parser->parse( "key[  key  ]=value" )
            );

        $this->assertSame(
                array ( "key.with" => "a.period" ),
                $parser->parse( "key.with=a.period" )
            );

        $this->assertSame(
                array ("key" => array("key" => "value")),
                $parser->parse( "key%5Bkey%5D=value" )
            );
    }

    public function testParse_custom ()
    {
        $parser = new \h2o\QueryParser;
        $parser->setStartDelim('(');
        $parser->setEndDelim(')');
        $parser->setOuterDelim('/');
        $parser->setInnerDelim(':');
        $parser->setSubRegEx('/\.([^\.]*)/');

        $this->assertSame(
                array ( "key" => "value", "key2" => array('sub' => array('sub2' => "value3")) ),
                $parser->parse( "url.com(key:value/key2.sub.sub2:value3)fragment" )
            );

        $this->assertSame(
                array ( "key" => "value", "key2" => array('sub' => array('sub2' => "value3")) ),
                $parser->parse( "key:value/key2.sub.sub2:value3" )
            );

        $this->assertSame(
                array ( "key" => "value", "key2" =>  "value3", "k3" => "other" ),
                $parser->parse( "key:value/key2:value3/k3:other" )
            );

        $this->assertSame(
                array ( "key" => array("value", "value2", "value3") ),
                $parser->parse( "key.:value/key.:value2/key.:value3" )
            );
    }

    public function testParse_badSubRegEx ()
    {
        $parser = new \h2o\QueryParser;
        $parser->setSubRegEx('/\[.*?\]/');

        try {
            $parser->parse( "key[sub]=value" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\Interaction $err ) {
            $this->assertSame( "Sub-Key RegEx did not return a sub-pattern", $err->getMessage() );
        }
    }

    public function testParse_filters ()
    {
        $keyFilter = $this->getMock('h2o\iface\Filter', array('filter'));
        $keyFilter->expects( $this->once() )
            ->method('filter')
            ->with( $this->equalTo('key[sub]') )
            ->will( $this->returnValue('newKey') );

        $valFilter = $this->getMock('h2o\iface\Filter', array('filter'));
        $valFilter->expects( $this->once() )
            ->method('filter')
            ->with( $this->equalTo('value') )
            ->will( $this->returnValue('newVal') );

        $parser = new \h2o\QueryParser;
        $parser->setKeyFilter($keyFilter);
        $parser->setValueFilter($valFilter);

        $this->assertSame(
                array ( "newKey" => "newVal" ),
                $parser->parse( "key[sub]=value" )
            );
    }

}

?>