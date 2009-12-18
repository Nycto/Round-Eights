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
        \r8\swap($var1, $var2);

        $this->assertEquals("test", $var2);
        $this->assertEquals("other", $var1);
    }

    public function testReduce ()
    {
        $this->assertFalse( \r8\reduce( FALSE ) );
        $this->assertTrue( \r8\reduce( TRUE ) );
        $this->assertNull( \r8\reduce( NULL ) );
        $this->assertEquals( 270, \r8\reduce( 270 ) );
        $this->assertEquals( 151.12, \r8\reduce( 151.12 ) );
        $this->assertEquals( 151.12, \r8\reduce( array(151.12, 150) ) );
        $this->assertEquals( 151.12, \r8\reduce( array( array(151.12, 150) ) ) );
        $this->assertEquals( "String of stuff", \r8\reduce( "String of stuff" ) );
        $this->assertEquals( "stream", \r8\reduce(STDOUT) );
    }

    public function testDefineIf ()
    {
        $this->assertFalse( defined("testDefineIf_example") );

        $this->assertTrue( \r8\defineIf("testDefineIf_example", "value") );

        $this->assertTrue( defined("testDefineIf_example") );
        $this->assertEquals( "value", testDefineIf_example );

        $this->assertTrue( \r8\defineIf("testDefineIf_example", "new value") );

        $this->assertEquals( "value", testDefineIf_example );

    }

    public function testIsEmpty ()
    {
        $this->assertTrue( \r8\isEmpty("") );
        $this->assertTrue( \r8\isEmpty(0) );
        $this->assertTrue( \r8\isEmpty(NULL) );
        $this->assertTrue( \r8\isEmpty(FALSE) );
        $this->assertTrue( \r8\isEmpty( array() ) );
        $this->assertTrue( \r8\isEmpty( "  " ) );

        $this->assertFalse( \r8\isEmpty("string") );
        $this->assertFalse( \r8\isEmpty(1) );
        $this->assertFalse( \r8\isEmpty("0") );
        $this->assertFalse( \r8\isEmpty("1") );
        $this->assertFalse( \r8\isEmpty(TRUE) );
        $this->assertFalse( \r8\isEmpty( array(1) ) );

        $this->assertFalse( \r8\isEmpty("", \r8\ALLOW_BLANK) );
        $this->assertFalse( \r8\isEmpty(0, \r8\ALLOW_ZERO) );
        $this->assertFalse( \r8\isEmpty(NULL, \r8\ALLOW_NULL) );
        $this->assertFalse( \r8\isEmpty(FALSE, \r8\ALLOW_FALSE) );
        $this->assertFalse( \r8\isEmpty( array(), \r8\ALLOW_EMPTY_ARRAYS ) );
        $this->assertFalse( \r8\isEmpty( "  ", \r8\ALLOW_SPACES ) );
    }

    public function testIsVague ()
    {
        $this->assertTrue( \r8\isVague(FALSE) );
        $this->assertTrue( \r8\isVague(TRUE) );
        $this->assertTrue( \r8\isVague("") );
        $this->assertTrue( \r8\isVague(0) );
        $this->assertTrue( \r8\isVague(NULL) );
        $this->assertTrue( \r8\isVague( array() ) );
        $this->assertTrue( \r8\isVague( "  " ) );

        $this->assertFalse( \r8\isVague("string") );
        $this->assertFalse( \r8\isVague(1) );
        $this->assertFalse( \r8\isVague("0") );
        $this->assertFalse( \r8\isVague("1") );
        $this->assertFalse( \r8\isVague( array(1) ) );
    }

    public function testIsBasic ()
    {
        $this->assertTrue( \r8\isBasic(FALSE) );
        $this->assertTrue( \r8\isBasic(TRUE) );
        $this->assertTrue( \r8\isBasic("some string") );
        $this->assertTrue( \r8\isBasic(500) );
        $this->assertTrue( \r8\isBasic(2.78) );
        $this->assertTrue( \r8\isBasic(NULL) );

        $this->assertFalse( \r8\isBasic( $this->getMock("object") ) );
        $this->assertFalse( \r8\isBasic( array() ) );
    }

    public function testArrayVal ()
    {
        $this->assertEquals( array(1, 2, 3), \r8\arrayVal(array(1, 2, 3)) );
        $this->assertEquals( array(1), \r8\arrayVal(1) );
    }

    public function testNumVal ()
    {
        $this->assertEquals( 1, \r8\numVal(1) );
        $this->assertEquals( 1.5, \r8\numVal(1.5) );
        $this->assertEquals( 1, \r8\numVal("1") );
        $this->assertEquals( 1.5, \r8\numVal("1.5") );
    }

    public function testBoolVal ()
    {
        $this->assertEquals( TRUE, \r8\boolVal(TRUE) );
        $this->assertEquals( FALSE, \r8\boolVal(FALSE) );
        $this->assertEquals( TRUE, \r8\boolVal(1) );
        $this->assertEquals( FALSE, \r8\boolVal(0) );
    }

    public function testStrVal ()
    {
        $this->assertEquals( "string", \r8\strVal("string") );
        $this->assertEquals( "5", \r8\strVal(5) );

        $toString = $this->getMock("stub_strval", array("__toString"));
        $toString->expects( $this->once() )
            ->method("__toString")
            ->will( $this->returnValue("String Version") );

        $this->assertEquals( "String Version", \r8\strVal( $toString ) );
    }

    public function testIndexVal ()
    {
        $this->assertSame( 0, \r8\indexVal(FALSE) );
        $this->assertSame( 1, \r8\indexVal(TRUE) );
        $this->assertSame( "", \r8\indexVal("") );
        $this->assertSame( 0, \r8\indexVal(0) );
        $this->assertSame( "", \r8\indexVal(NULL) );
        $this->assertSame( "", \r8\indexVal( array() ) );
        $this->assertSame( "  ", \r8\indexVal( "  " ) );
        $this->assertSame( "string", \r8\indexVal("string") );
        $this->assertSame( 1, \r8\indexVal(1) );
        $this->assertSame( "0", \r8\indexVal("0") );
        $this->assertSame( "1", \r8\indexVal("1") );
        $this->assertSame( 1, \r8\indexVal( array(1) ) );
    }

    public function testKindOf ()
    {
        $filter = new \r8\Filter\Chain;

        $this->assertTrue( \r8\kindOf( $filter, 'r8\Filter\Chain' ) );
        $this->assertTrue( \r8\kindOf( $filter, 'r8\Filter' ) );
        $this->assertTrue( \r8\kindOf( $filter, 'r8\iface\Filter' ) );

        $this->assertTrue( \r8\kindOf( $filter, 'r8\filter\chain' ) );
        $this->assertTrue( \r8\kindOf( $filter, 'r8\filter' ) );
        $this->assertTrue( \r8\kindOf( $filter, 'r8\iface\filter' ) );

        $this->assertTrue( \r8\kindOf( $filter, '\r8\Filter\Chain' ) );
        $this->assertTrue( \r8\kindOf( $filter, '\r8\Filter' ) );
        $this->assertTrue( \r8\kindOf( $filter, '\r8\iface\Filter' ) );

        $this->assertFalse( \r8\kindOf( $filter, 'r8\Validator' ) );
        $this->assertFalse( \r8\kindOf( $filter, 'r8\iface\Validator' ) );

        $this->assertFalse( \r8\kindOf( $filter, '\r8\Validator' ) );
        $this->assertFalse( \r8\kindOf( $filter, '\r8\iface\Validator' ) );


        $this->assertTrue( \r8\kindOf( 'r8\Filter\Chain', 'r8\Filter\Chain' ) );
        $this->assertTrue( \r8\kindOf( 'r8\Filter\Chain', 'r8\Filter' ) );
        $this->assertTrue( \r8\kindOf( 'r8\Filter\Chain', 'r8\iface\Filter' ) );

        $this->assertTrue( \r8\kindOf( 'r8\Filter\Chain', 'r8\filter\chain' ) );
        $this->assertTrue( \r8\kindOf( 'r8\Filter\Chain', 'r8\filter' ) );
        $this->assertTrue( \r8\kindOf( 'r8\Filter\Chain', 'r8\iface\filter' ) );

        $this->assertTrue( \r8\kindOf( '\r8\Filter\Chain', 'r8\Filter\Chain' ) );
        $this->assertTrue( \r8\kindOf( '\r8\Filter\Chain', 'r8\Filter' ) );
        $this->assertTrue( \r8\kindOf( '\r8\Filter\Chain', 'r8\iface\Filter' ) );

        $this->assertTrue( \r8\kindOf( 'r8\Filter\Chain', '\r8\Filter\Chain' ) );
        $this->assertTrue( \r8\kindOf( 'r8\Filter\Chain', '\r8\Filter' ) );
        $this->assertTrue( \r8\kindOf( 'r8\Filter\Chain', '\r8\iface\Filter' ) );

        $this->assertTrue( \r8\kindOf( '\r8\Filter\Chain', '\r8\Filter\Chain' ) );
        $this->assertTrue( \r8\kindOf( '\r8\Filter\Chain', '\r8\Filter' ) );
        $this->assertTrue( \r8\kindOf( '\r8\Filter\Chain', '\r8\iface\Filter' ) );


        $this->assertFalse( \r8\kindOf( 'r8\Filter\Chain', 'r8\Validator' ) );
        $this->assertFalse( \r8\kindOf( 'r8\Filter\Chain', 'r8\iface\Validator' ) );

        $this->assertFalse( \r8\kindOf( '\r8\Filter\Chain', 'r8\Validator' ) );
        $this->assertFalse( \r8\kindOf( '\r8\Filter\Chain', 'r8\iface\Validator' ) );

        $this->assertFalse( \r8\kindOf( 'r8\Filter\Chain', '\r8\Validator' ) );
        $this->assertFalse( \r8\kindOf( 'r8\Filter\Chain', '\r8\iface\Validator' ) );

        $this->assertFalse( \r8\kindOf( '\r8\Filter\Chain', '\r8\Validator' ) );
        $this->assertFalse( \r8\kindOf( '\r8\Filter\Chain', '\r8\iface\Validator' ) );
    }

    public function testRespondTo ()
    {
        $this->assertFalse( \r8\respondTo( 505, 'method') );
        $this->assertFalse( \r8\respondTo( 50.5, 'method') );
        $this->assertFalse( \r8\respondTo( NULL, 'method') );
        $this->assertFalse( \r8\respondTo( TRUE, 'method') );
        $this->assertFalse( \r8\respondTo( FALSE, 'method') );
        $this->assertFalse( \r8\respondTo( 'string', 'method') );
        $this->assertFalse( \r8\respondTo( array(), 'method') );

        $this->assertFalse( \r8\respondTo( new stdClass, 'method') );

        $test = $this->getMock('stdClass', array('testFunc'));

        $this->assertTrue( \r8\respondTo( $test, 'testFunc') );
        $this->assertFalse( \r8\respondTo( $test, 'Not A Valid Method Name') );
    }

}

?>