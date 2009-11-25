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
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * Test Suite
 */
class classes_Template_Replace
{

    public static function suite()
    {
        $suite = new r8_Base_TestSuite;
        $suite->addTestSuite( 'classes_Template_Replace_Standard' );
        $suite->addTestSuite( 'classes_Template_Replace_Output' );
        return $suite;
    }

}

/**
 * unit tests
 */
class classes_Template_Replace_Standard extends PHPUnit_Framework_TestCase
{

    public function testTemplateAccessors ()
    {
        $tpl = new \r8\Template\Replace("tpl");

        $this->assertSame( "tpl", $tpl->getTemplate() );
        $this->assertSame( $tpl, $tpl->setTemplate("new template") );
        $this->assertSame( "new template", $tpl->getTemplate() );
    }

    public function testSearchAccessors ()
    {
        $tpl = new \r8\Template\Replace("tpl");

        $this->assertSame( '/(\\\\*)(#\{(.*?)\})/', $tpl->getSearch() );
        $this->assertSame( $tpl, $tpl->setSearch('/replace/') );
        $this->assertSame( '/replace/', $tpl->getSearch() );
    }

    public function testRender ()
    {
        $tpl = new \r8\Template\Replace("#{adjective} #{noun} #{verb}");
        $tpl->import(array(
                "adjective" => "quick",
                "noun" => "fox",
                "verb" => "ran"
            ));
        $this->assertSame( "quick fox ran", $tpl->render() );
    }

    public function testRender_escaped ()
    {
        $tpl = new \r8\Template\Replace('#{test} \\#{test} \\\\#{test} \\\\\\#{test}');
        $tpl->set('test', 'value');

        $this->assertSame( "value #{test} \\\\value \\\\#{test}", $tpl->render() );
    }

    public function testRender_empty ()
    {
        $tpl = new \r8\Template\Replace("The #{adjective} #{noun} #{verb}");
        $this->assertSame( "The   ", $tpl->render() );
    }

    public function testRender_recursion ()
    {
        $tpl = new \r8\Template\Replace("#{value}");
        $tpl->set('value', $tpl);
        $this->assertSame( "", $tpl->render() );
    }

    public function testNewSearch ()
    {
        $tpl = new \r8\Template\Replace('<b><%=bold %></b>');
        $tpl->setSearch("/(\\\\*)(\<%=\s*(\w+)\s*%\>)/");
        $tpl->set('bold', 'STRONG');

        $this->assertSame( "<b>STRONG</b>", $tpl->render() );
    }

    public function testWrongGroupings ()
    {
        $tpl = new \r8\Template\Replace('replace');
        $tpl->setSearch("/replace/");

        try {
            $tpl->render();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Data $err ) {
            $this->assertSame( "Must return at least 3 groupings", $err->getMessage() );
        }
    }

    public function testToString ()
    {
        $tpl = new \r8\Template\Replace("#{adjective} #{noun} #{verb}");
        $tpl->import(array(
                "adjective" => "quick",
                "noun" => "fox",
                "verb" => "ran"
            ));
        $this->assertSame( "quick fox ran", $tpl->__toString() );
    }

}

class classes_Template_Replace_Output extends PHPUnit_Extensions_OutputTestCase
{

    public function testDisplay ()
    {
        $this->expectOutputString("The quick fox ran");

        $tpl = new \r8\Template\Replace("The #{adjective} #{noun} #{verb}");
        $tpl->import(array(
                "adjective" => "quick",
                "noun" => "fox",
                "verb" => "ran"
            ));

        $this->assertSame( $tpl, $tpl->display() );
    }

}

?>