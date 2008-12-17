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

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * unit tests
 */
class classes_template extends PHPUnit_Framework_TestCase
{

    public function testNormalizeLabel ()
    {
        $this->assertSame(
                "VariableName",
                \cPHP\Template::normalizeLabel("Variable Name")
            );

        try {
            \cPHP\Template::normalizeLabel("50Label");
            $this->fail('An expected exception was not thrown');
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame( "Must be a valid PHP variable name", $err->getMessage() );
        }
    }

    public function testSet ()
    {
        $tpl = $this->getMock( 'cPHP\Template', array("_mock") );
        $this->assertEquals( new \cPHP\Ary, $tpl->getValues() );

        $this->assertSame( $tpl, $tpl->set('var', 'value') );
        $this->assertEquals(
                new \cPHP\Ary( array('var' => 'value') ),
                $tpl->getValues()
            );

        $this->assertSame( $tpl, $tpl->set('other', 2) );
        $this->assertEquals(
                new \cPHP\Ary( array('var' => 'value', 'other' => 2) ),
                $tpl->getValues()
            );

        $obj = new stdClass;
        $this->assertSame( $tpl, $tpl->set('var', $obj) );
        $this->assertSame(
                array('var' => $obj, 'other' => 2),
                $tpl->getValues()->get()
            );
    }

    public function testRemove ()
    {
        $tpl = $this->getMock( 'cPHP\Template', array("_mock") );
        $this->assertEquals( new \cPHP\Ary, $tpl->getValues() );

        $this->assertSame( $tpl, $tpl->remove('doesnt Exist') );

        $tpl->set('var', 'value');
        $tpl->set('other', 3.1415);

        $this->assertEquals(
                new \cPHP\Ary( array('var' => 'value', 'other' => 3.1415) ),
                $tpl->getValues()
            );

        $this->assertSame( $tpl, $tpl->remove('var') );
        $this->assertEquals(
                new \cPHP\Ary( array('other' => 3.1415) ),
                $tpl->getValues()
            );

        $this->assertSame( $tpl, $tpl->remove('other') );
        $this->assertEquals( new \cPHP\Ary, $tpl->getValues() );
    }

    public function testExists ()
    {
        $tpl = $this->getMock( 'cPHP\Template', array("_mock") );
        $this->assertFalse( $tpl->exists('var') );

        $tpl->set('var', 'value');
        $this->assertTrue( $tpl->exists('var') );

        $tpl->remove('var');
        $this->assertFalse( $tpl->exists('var') );
    }

    public function testGet ()
    {
        $tpl = $this->getMock( 'cPHP\Template', array("_mock") );

        $this->assertNull( $tpl->get('var') );

        $tpl->set('var', 'value');
        $this->assertSame( 'value', $tpl->get('var') );

        $obj = new stdClass;
        $tpl->set('Other One', $obj);
        $this->assertSame( $obj, $tpl->get('Other One') );

        $tpl->remove('Other One');
        $this->assertNull( $tpl->get('Other One') );

        $this->assertSame( 'value', $tpl->get('var') );

        $tpl->remove('var');
        $this->assertNull( $tpl->get('var') );
    }

}

?>