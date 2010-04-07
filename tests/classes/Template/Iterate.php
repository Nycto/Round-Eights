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
 * Test Suite
 */
class classes_Template_Iterate
{

    /**
     * Returns a test Iterator Template
     *
     * @return \r8\Template\Iterate
     */
    static public function getTestTpl ()
    {
        return new \r8\Template\Iterate(
            new \r8\Template\Replace('#{key},#{value};'),
            new ArrayIterator(array(
                "one" => array( "value" => "first" ),
                "two" => array( "value" => "second" ),
                "three" => array( "value" => "third" ),
            ))
        );
    }

    /**
     * Returns an empty Iterator Template
     *
     * @return \r8\Template\Iterate
     */
    static public function getTestEmptyTpl ()
    {
        return new \r8\Template\Iterate(
            new \r8\Template\Replace('#{key},#{value};'),
            new \EmptyIterator
        );
    }

    public static function suite()
    {
        $suite = new \r8\Test\Suite;
        $suite->addTestSuite( 'classes_Template_Iterate_Standard' );
        $suite->addTestSuite( 'classes_Template_Iterate_Output' );
        return $suite;
    }

}

/**
 * unit tests
 */
class classes_Template_Iterate_Standard extends PHPUnit_Framework_TestCase
{

    public function testRender ()
    {
        $tpl = classes_Template_Iterate::getTestTpl();

        $this->assertSame(
            "one,first;two,second;three,third;",
            $tpl->render()
        );
    }

    public function testRender_Empty ()
    {
        $tpl = classes_Template_Iterate::getTestEmptyTpl();
        $this->assertSame( "", $tpl->render() );
    }

    public function testToString ()
    {
        $tpl = classes_Template_Iterate::getTestTpl();

        $this->assertSame(
            "one,first;two,second;three,third;",
            $tpl->__toString()
        );
    }

    public function testToString_Empty ()
    {
        $tpl = classes_Template_Iterate::getTestEmptyTpl();
        $this->assertSame( "", $tpl->__toString() );
    }

}

class classes_Template_Iterate_Output extends PHPUnit_Extensions_OutputTestCase
{

    public function testDisplay ()
    {
        $this->expectOutputString("one,first;two,second;three,third;");
        $tpl = classes_Template_Iterate::getTestTpl();
        $tpl->display();
    }

    public function testDisplay_Empty ()
    {
        $this->expectOutputString("");
        $tpl = classes_Template_Iterate::getTestEmptyTpl();
        $tpl->display();
    }

}

?>