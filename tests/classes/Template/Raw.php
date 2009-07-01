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

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * Test Suite
 */
class classes_template_raw
{

    public static function suite()
    {
        $suite = new h2o_Base_TestSuite;
        $suite->addTestSuite( 'classes_template_raw_standard' );
        $suite->addTestSuite( 'classes_template_raw_output' );
        return $suite;
    }

}

/**
 * unit tests
 */
class classes_template_raw_standard extends PHPUnit_Framework_TestCase
{

    public function testContentAccessors ()
    {
        $tpl = new \h2o\Template\Raw;

        $this->assertNull( $tpl->getContent() );
        $this->assertFalse( $tpl->contentExists() );

        $this->assertSame( $tpl, $tpl->setContent("chunk o stuff") );
        $this->assertSame( "chunk o stuff", $tpl->getContent() );
        $this->assertTrue( $tpl->contentExists() );

        $this->assertSame( $tpl, $tpl->clearContent() );
        $this->assertNull( $tpl->getContent() );
        $this->assertFalse( $tpl->contentExists() );

        $this->assertSame( $tpl, $tpl->setContent(505) );
        $this->assertSame( 505, $tpl->getContent() );
        $this->assertTrue( $tpl->contentExists() );

        $obj = new stdClass;
        $this->assertSame( $tpl, $tpl->setContent($obj) );
        $this->assertSame( $obj, $tpl->getContent() );
        $this->assertTrue( $tpl->contentExists() );
    }

    public function testConstruct ()
    {
        $tpl = new \h2o\Template\Raw( 3.1415 );

        $this->assertSame( 3.1415, $tpl->getContent() );
        $this->assertTrue( $tpl->contentExists() );
    }

    public function testRender ()
    {
        $tpl = new \h2o\Template\Raw( "Lorem Ipsum" );
        $this->assertSame( "Lorem Ipsum", $tpl->render() );


        $tpl->setContent(404);
        $this->assertSame( "404", $tpl->render() );


        $obj = $this->getMock("stdClass", array("__toString"));
        $obj->expects( $this->once() )
            ->method("__toString")
            ->will( $this->returnValue(3.1415) );

        $tpl->setContent( $obj );
        $this->assertSame( "3.1415", $tpl->render() );
    }

    public function testToString ()
    {
        $tpl = new \h2o\Template\Raw( "Lorem Ipsum" );
        $this->assertSame( "Lorem Ipsum", "$tpl" );
        $this->assertSame( "Lorem Ipsum", $tpl->__toString() );


        $tpl->setContent(404);
        $this->assertSame( "404", "$tpl" );
        $this->assertSame( "404", $tpl->__toString() );


        $obj = $this->getMock("stdClass", array("__toString"));
        $obj->expects( $this->exactly(2) )
            ->method("__toString")
            ->will( $this->returnValue(3.1415) );

        $tpl->setContent( $obj );
        $this->assertSame( "3.1415", "$tpl" );
        $this->assertSame( "3.1415", $tpl->__toString() );
    }

}

class classes_template_raw_output extends PHPUnit_Extensions_OutputTestCase
{

    public function testDisplay_string ()
    {
        $this->expectOutputString("Lorem Ipsum");

        $tpl = new \h2o\Template\Raw( "Lorem Ipsum" );
        $this->assertSame( $tpl, $tpl->display() );
    }

    public function testDisplay_integer ()
    {
        $this->expectOutputString("404");

        $tpl = new \h2o\Template\Raw( 404 );
        $this->assertSame( $tpl, $tpl->display() );
    }

    public function testDisplay_object ()
    {
        $this->expectOutputString("3.1415");

        $obj = $this->getMock("stdClass", array("__toString"));
        $obj->expects( $this->once() )
            ->method("__toString")
            ->will( $this->returnValue(3.1415) );

        $tpl = new \h2o\Template\Raw( $obj );
        $this->assertSame( $tpl, $tpl->display() );
    }

}

?>