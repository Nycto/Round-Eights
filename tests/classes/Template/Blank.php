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
class classes_template_blank
{

    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite;
        $suite->addTestSuite( 'classes_template_blank_standard' );
        $suite->addTestSuite( 'classes_template_blank_output' );
        return $suite;
    }

}

/**
 * unit tests
 */
class classes_template_blank_standard extends PHPUnit_Framework_TestCase
{

    public function testRender ()
    {
        $tpl = new \cPHP\Template\Blank;
        $this->assertSame( "", $tpl->render() );
    }

    public function testToString ()
    {
        $tpl = new \cPHP\Template\Blank;
        $this->assertSame( "", $tpl->render() );

        $this->assertSame( "", "$tpl" );
    }

}

class classes_template_blank_output extends PHPUnit_Extensions_OutputTestCase
{

    public function testDisplay_string ()
    {
        $this->expectOutputString("");

        $tpl = new \cPHP\Template\Blank;
        $this->assertSame( $tpl, $tpl->display() );
    }

}

?>