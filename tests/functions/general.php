<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of raindropPHP.
 *
 * raindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * raindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with raindropPHP. If not, see <http://www.raindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * general function unit tests
 */
class functions_general extends PHPUnit_Framework_TestCase
{

    public function testSwap ()
    {

        $var1 = "test";
        $var2 = "other";
        h2o\swap($var1, $var2);

        $this->assertEquals("test", $var2);
        $this->assertEquals("other", $var1);
    }

    public function testReduce ()
    {
        $this->assertFalse( h2o\reduce( FALSE ) );
        $this->assertTrue( h2o\reduce( TRUE ) );
        $this->assertNull( h2o\reduce( NULL ) );
        $this->assertEquals( 270, h2o\reduce( 270 ) );
        $this->assertEquals( 151.12, h2o\reduce( 151.12 ) );
        $this->assertEquals( 151.12, h2o\reduce( array(151.12, 150) ) );
        $this->assertEquals( 151.12, h2o\reduce( array( array(151.12, 150) ) ) );
    }

    public function testDefineIf ()
    {
        $this->assertFalse( defined("testDefineIf_example") );

        $this->assertTrue( h2o\defineIf("testDefineIf_example", "value") );

        $this->assertTrue( defined("testDefineIf_example") );
        $this->assertEquals( "value", testDefineIf_example );

        $this->assertTrue( h2o\defineIf("testDefineIf_example", "new value") );

        $this->assertEquals( "value", testDefineIf_example );

    }

    public function testIsEmpty ()
    {
        $this->assertTrue( h2o\isEmpty("") );
        $this->assertTrue( h2o\isEmpty(0) );
        $this->assertTrue( h2o\isEmpty(NULL) );
        $this->assertTrue( h2o\isEmpty(FALSE) );
        $this->assertTrue( h2o\isEmpty( array() ) );
        $this->assertTrue( h2o\isEmpty( "  " ) );

        $this->assertFalse( h2o\isEmpty("string") );
        $this->assertFalse( h2o\isEmpty(1) );
        $this->assertFalse( h2o\isEmpty("0") );
        $this->assertFalse( h2o\isEmpty("1") );
        $this->assertFalse( h2o\isEmpty(TRUE) );
        $this->assertFalse( h2o\isEmpty( array(1) ) );

        $this->assertFalse( h2o\isEmpty("", h2o\ALLOW_BLANK) );
        $this->assertFalse( h2o\isEmpty(0, h2o\ALLOW_ZERO) );
        $this->assertFalse( h2o\isEmpty(NULL, h2o\ALLOW_NULL) );
        $this->assertFalse( h2o\isEmpty(FALSE, h2o\ALLOW_FALSE) );
        $this->assertFalse( h2o\isEmpty( array(), h2o\ALLOW_EMPTY_ARRAYS ) );
        $this->assertFalse( h2o\isEmpty( "  ", h2o\ALLOW_SPACES ) );
    }

    public function testIsVague ()
    {
        $this->assertTrue( h2o\isVague(FALSE) );
        $this->assertTrue( h2o\isVague(TRUE) );
        $this->assertTrue( h2o\isVague("") );
        $this->assertTrue( h2o\isVague(0) );
        $this->assertTrue( h2o\isVague(NULL) );
        $this->assertTrue( h2o\isVague( array() ) );
        $this->assertTrue( h2o\isVague( "  " ) );

        $this->assertFalse( h2o\isVague("string") );
        $this->assertFalse( h2o\isVague(1) );
        $this->assertFalse( h2o\isVague("0") );
        $this->assertFalse( h2o\isVague("1") );
        $this->assertFalse( h2o\isVague( array(1) ) );
    }

    public function testIsBasic ()
    {
        $this->assertTrue( h2o\isBasic(FALSE) );
        $this->assertTrue( h2o\isBasic(TRUE) );
        $this->assertTrue( h2o\isBasic("some string") );
        $this->assertTrue( h2o\isBasic(500) );
        $this->assertTrue( h2o\isBasic(2.78) );
        $this->assertTrue( h2o\isBasic(NULL) );

        $this->assertFalse( h2o\isBasic( $this->getMock("object") ) );
        $this->assertFalse( h2o\isBasic( array() ) );
    }

    public function testArrayVal ()
    {
        $this->assertEquals( array(1, 2, 3), h2o\arrayVal(array(1, 2, 3)) );
        $this->assertEquals( array(1), h2o\arrayVal(1) );
    }

    public function testNumVal ()
    {
        $this->assertEquals( 1, h2o\numVal(1) );
        $this->assertEquals( 1.5, h2o\numVal(1.5) );
        $this->assertEquals( 1, h2o\numVal("1") );
        $this->assertEquals( 1.5, h2o\numVal("1.5") );
    }

    public function testBoolVal ()
    {
        $this->assertEquals( TRUE, h2o\boolVal(TRUE) );
        $this->assertEquals( FALSE, h2o\boolVal(FALSE) );
        $this->assertEquals( TRUE, h2o\boolVal(1) );
        $this->assertEquals( FALSE, h2o\boolVal(0) );
    }

    public function testStrVal ()
    {
        $this->assertEquals( "string", \h2o\strVal("string") );
        $this->assertEquals( "5", h2o\strVal(5) );

        $toString = $this->getMock("stub_strval", array("__toString"));
        $toString->expects( $this->once() )
            ->method("__toString")
            ->will( $this->returnValue("String Version") );

        $this->assertEquals( "String Version", \h2o\strVal( $toString ) );
    }

    public function testIndexVal ()
    {
        $this->assertSame( 0, h2o\indexVal(FALSE) );
        $this->assertSame( 1, h2o\indexVal(TRUE) );
        $this->assertSame( "", h2o\indexVal("") );
        $this->assertSame( 0, h2o\indexVal(0) );
        $this->assertSame( "", h2o\indexVal(NULL) );
        $this->assertSame( "", h2o\indexVal( array() ) );
        $this->assertSame( "  ", h2o\indexVal( "  " ) );
        $this->assertSame( "string", h2o\indexVal("string") );
        $this->assertSame( 1, h2o\indexVal(1) );
        $this->assertSame( "0", h2o\indexVal("0") );
        $this->assertSame( "1", h2o\indexVal("1") );
        $this->assertSame( 1, h2o\indexVal( array(1) ) );
    }

    public function testKindOf ()
    {
        $filter = new \h2o\Filter\Chain;

        $this->assertTrue( \h2o\kindOf( $filter, 'h2o\Filter\Chain' ) );
        $this->assertTrue( \h2o\kindOf( $filter, 'h2o\Filter' ) );
        $this->assertTrue( \h2o\kindOf( $filter, 'h2o\iface\Filter' ) );

        $this->assertTrue( \h2o\kindOf( $filter, 'h2o\filter\chain' ) );
        $this->assertTrue( \h2o\kindOf( $filter, 'h2o\filter' ) );
        $this->assertTrue( \h2o\kindOf( $filter, 'h2o\iface\filter' ) );

        $this->assertTrue( \h2o\kindOf( $filter, '\h2o\Filter\Chain' ) );
        $this->assertTrue( \h2o\kindOf( $filter, '\h2o\Filter' ) );
        $this->assertTrue( \h2o\kindOf( $filter, '\h2o\iface\Filter' ) );

        $this->assertFalse( \h2o\kindOf( $filter, 'h2o\Validator' ) );
        $this->assertFalse( \h2o\kindOf( $filter, 'h2o\iface\Validator' ) );

        $this->assertFalse( \h2o\kindOf( $filter, '\h2o\Validator' ) );
        $this->assertFalse( \h2o\kindOf( $filter, '\h2o\iface\Validator' ) );


        $this->assertTrue( \h2o\kindOf( 'h2o\Filter\Chain', 'h2o\Filter\Chain' ) );
        $this->assertTrue( \h2o\kindOf( 'h2o\Filter\Chain', 'h2o\Filter' ) );
        $this->assertTrue( \h2o\kindOf( 'h2o\Filter\Chain', 'h2o\iface\Filter' ) );

        $this->assertTrue( \h2o\kindOf( 'h2o\Filter\Chain', 'h2o\filter\chain' ) );
        $this->assertTrue( \h2o\kindOf( 'h2o\Filter\Chain', 'h2o\filter' ) );
        $this->assertTrue( \h2o\kindOf( 'h2o\Filter\Chain', 'h2o\iface\filter' ) );

        $this->assertTrue( \h2o\kindOf( '\h2o\Filter\Chain', 'h2o\Filter\Chain' ) );
        $this->assertTrue( \h2o\kindOf( '\h2o\Filter\Chain', 'h2o\Filter' ) );
        $this->assertTrue( \h2o\kindOf( '\h2o\Filter\Chain', 'h2o\iface\Filter' ) );

        $this->assertTrue( \h2o\kindOf( 'h2o\Filter\Chain', '\h2o\Filter\Chain' ) );
        $this->assertTrue( \h2o\kindOf( 'h2o\Filter\Chain', '\h2o\Filter' ) );
        $this->assertTrue( \h2o\kindOf( 'h2o\Filter\Chain', '\h2o\iface\Filter' ) );

        $this->assertTrue( \h2o\kindOf( '\h2o\Filter\Chain', '\h2o\Filter\Chain' ) );
        $this->assertTrue( \h2o\kindOf( '\h2o\Filter\Chain', '\h2o\Filter' ) );
        $this->assertTrue( \h2o\kindOf( '\h2o\Filter\Chain', '\h2o\iface\Filter' ) );


        $this->assertFalse( \h2o\kindOf( 'h2o\Filter\Chain', 'h2o\Validator' ) );
        $this->assertFalse( \h2o\kindOf( 'h2o\Filter\Chain', 'h2o\iface\Validator' ) );

        $this->assertFalse( \h2o\kindOf( '\h2o\Filter\Chain', 'h2o\Validator' ) );
        $this->assertFalse( \h2o\kindOf( '\h2o\Filter\Chain', 'h2o\iface\Validator' ) );

        $this->assertFalse( \h2o\kindOf( 'h2o\Filter\Chain', '\h2o\Validator' ) );
        $this->assertFalse( \h2o\kindOf( 'h2o\Filter\Chain', '\h2o\iface\Validator' ) );

        $this->assertFalse( \h2o\kindOf( '\h2o\Filter\Chain', '\h2o\Validator' ) );
        $this->assertFalse( \h2o\kindOf( '\h2o\Filter\Chain', '\h2o\iface\Validator' ) );
    }

    public function testRespondTo ()
    {
        $this->assertFalse( \h2o\respondTo( 505, 'method') );
        $this->assertFalse( \h2o\respondTo( 50.5, 'method') );
        $this->assertFalse( \h2o\respondTo( NULL, 'method') );
        $this->assertFalse( \h2o\respondTo( TRUE, 'method') );
        $this->assertFalse( \h2o\respondTo( FALSE, 'method') );
        $this->assertFalse( \h2o\respondTo( 'string', 'method') );
        $this->assertFalse( \h2o\respondTo( array(), 'method') );

        $this->assertFalse( \h2o\respondTo( new stdClass, 'method') );

        $test = $this->getMock('stdClass', array('testFunc'));

        $this->assertTrue( \h2o\respondTo( $test, 'testFunc') );
        $this->assertFalse( \h2o\respondTo( $test, 'Not A Valid Method Name') );
    }

}

?>