<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of commonPHP.
 *
 * commonPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * commonPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with commonPHP. If not, see <http://www.commonphp.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@commonphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * Test Suite
 */
class classes_template_collection
{

    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite;
        $suite->addTestSuite( 'classes_template_collection_standard' );
        $suite->addTestSuite( 'classes_template_collection_output' );
        return $suite;
    }

}

/**
 * unit tests
 */
class classes_template_collection_standard extends PHPUnit_Framework_TestCase
{

    public function getMockTpl ()
    {
        return $this->getMock(
                'cPHP\\iface\\Template',
                array("render", "display", "__toString")
            );
    }

    public function testAdd ()
    {
        $tpl = new \cPHP\Template\Collection;
        $this->assertEquals( new \cPHP\Ary, $tpl->getTemplates() );

        $mock = $this->getMockTpl();
        $this->assertSame( $tpl, $tpl->add( $mock ) );
        $this->assertThat( $tpl->getTemplates(), $this->isInstanceOf('cPHP\Ary') );
        $this->assertSame( array($mock), $tpl->getTemplates()->get() );

        $mock2 = $this->getMockTpl();
        $this->assertSame( $tpl, $tpl->add( $mock2 ) );
        $this->assertThat( $tpl->getTemplates(), $this->isInstanceOf('cPHP\Ary') );
        $this->assertSame( array($mock, $mock2), $tpl->getTemplates()->get() );
    }

    public function testRender ()
    {
        $tpl = new \cPHP\Template\Collection;


        $mock = $this->getMockTpl();
        $mock->expects( $this->once() )
            ->method('display')
            ->will( $this->returnCallback( function () {
                echo "Lorem";
            }));

        $tpl->add( $mock );


        $mock2 = $this->getMockTpl();
        $mock2->expects( $this->once() )
            ->method('display')
            ->will( $this->returnCallback( function () {
                echo " Ipsum";
            }));

        $tpl->add( $mock2 );


        $this->assertSame("Lorem Ipsum", $tpl->render());
    }

    public function testRender_empty ()
    {
        $tpl = new \cPHP\Template\Collection;
        $this->assertSame("", $tpl->render());
    }

    public function testToString ()
    {
        $tpl = new \cPHP\Template\Collection;


        $mock = $this->getMockTpl();
        $mock->expects( $this->once() )
            ->method('display')
            ->will( $this->returnCallback( function () {
                echo "Lorem";
            }));

        $tpl->add( $mock );


        $mock2 = $this->getMockTpl();
        $mock2->expects( $this->once() )
            ->method('display')
            ->will( $this->returnCallback( function () {
                echo " Ipsum";
            }));

        $tpl->add( $mock2 );


        $this->assertSame("Lorem Ipsum", "$tpl");
    }

    public function testToString_empty ()
    {
        $tpl = new \cPHP\Template\Collection;
        $this->assertSame("", "$tpl");
    }

}

class classes_template_collection_output extends PHPUnit_Extensions_OutputTestCase
{

    public function getMockTpl ()
    {
        return $this->getMock(
                'cPHP\\iface\\Template',
                array("render", "display", "__toString")
            );
    }

    public function testDisplay ()
    {
        $this->expectOutputString("Lorem Ipsum");

        $tpl = new \cPHP\Template\Collection;


        $mock = $this->getMockTpl();
        $mock->expects( $this->once() )
            ->method('display')
            ->will( $this->returnCallback( function () {
                echo "Lorem";
            }));

        $tpl->add( $mock );


        $mock2 = $this->getMockTpl();
        $mock2->expects( $this->once() )
            ->method('display')
            ->will( $this->returnCallback( function () {
                echo " Ipsum";
            }));

        $tpl->add( $mock2 );


        $this->assertSame($tpl, $tpl->display());
    }

    public function testDisplay_empty ()
    {
        $this->expectOutputString("");

        $tpl = new \cPHP\Template\Collection;

        $this->assertSame($tpl, $tpl->display());
    }

}

?>