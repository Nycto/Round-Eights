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
class classes_Template_Access extends PHPUnit_Framework_TestCase
{

    public function testNormalizeLabel ()
    {
        $this->assertSame(
                "VariableName",
                \r8\Template\Access::normalizeLabel("Variable Name")
            );

        try {
            \r8\Template\Access::normalizeLabel("50Label");
            $this->fail('An expected exception was not thrown');
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "Must be a valid PHP variable name", $err->getMessage() );
        }
    }

    public function testSet ()
    {
        $tpl = $this->getMock('\r8\Template\Access', array('_mock'));
        $this->assertEquals( array(), $tpl->getValues() );

        $this->assertSame( $tpl, $tpl->set('var', 'value') );
        $this->assertEquals(
                array('var' => 'value'),
                $tpl->getValues()
            );

        $this->assertSame( $tpl, $tpl->set('other', 2) );
        $this->assertEquals(
                array('var' => 'value', 'other' => 2),
                $tpl->getValues()
            );

        $obj = new stdClass;
        $this->assertSame( $tpl, $tpl->set('var', $obj) );
        $this->assertSame(
                array('var' => $obj, 'other' => 2),
                $tpl->getValues()
            );
    }

    public function testRemove ()
    {
        $tpl = $this->getMock('\r8\Template\Access', array('_mock'));
        $this->assertEquals( array(), $tpl->getValues() );

        $this->assertSame( $tpl, $tpl->remove('doesnt Exist') );

        $tpl->set('var', 'value');
        $tpl->set('other', 3.1415);

        $this->assertEquals(
                array('var' => 'value', 'other' => 3.1415),
                $tpl->getValues()
            );

        $this->assertSame( $tpl, $tpl->remove('var') );
        $this->assertEquals(
                array('other' => 3.1415),
                $tpl->getValues()
            );

        $this->assertSame( $tpl, $tpl->remove('other') );
        $this->assertEquals( array(), $tpl->getValues() );
    }

    public function testExists ()
    {
        $tpl = $this->getMock('\r8\Template\Access', array('_mock'));
        $this->assertFalse( $tpl->exists('var') );

        $tpl->set('var', 'value');
        $this->assertTrue( $tpl->exists('var') );

        $tpl->remove('var');
        $this->assertFalse( $tpl->exists('var') );
    }

    public function testGet ()
    {
        $tpl = $this->getMock('\r8\Template\Access', array('_mock'));

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

    public function testAdd ()
    {
        $tpl = $this->getMock('\r8\Template\Access', array('_mock'));
        $this->assertEquals( array(), $tpl->getValues() );

        $this->assertSame( $tpl, $tpl->add('var', 'value') );
        $this->assertEquals(
                array('var' => 'value'),
                $tpl->getValues()
            );

        $this->assertSame( $tpl, $tpl->add('other', 2) );
        $this->assertEquals(
                array('var' => 'value', 'other' => 2),
                $tpl->getValues()
            );

        $this->assertSame( $tpl, $tpl->add('var', 'new value') );
        $this->assertEquals(
                array('var' => 'value', 'other' => 2),
                $tpl->getValues()
            );
    }

    public function testClear ()
    {
        $tpl = $this->getMock('\r8\Template\Access', array('_mock'));

        $this->assertSame( $tpl, $tpl->clear() );
        $this->assertEquals( array(), $tpl->getValues() );

        $tpl->set('var', 'value');
        $tpl->set('other', 3.1415);

        $this->assertEquals(
                array('var' => 'value', 'other' => 3.1415),
                $tpl->getValues()
            );

        $this->assertSame( $tpl, $tpl->clear() );
        $this->assertEquals( array(), $tpl->getValues() );
    }

    public function testAppend ()
    {
        $tpl = $this->getMock('\r8\Template\Access', array('_mock'));
        $this->assertEquals( array(), $tpl->getValues() );

        $this->assertSame( $tpl, $tpl->append('var', 'value') );
        $this->assertEquals(
                array('var' => 'value'),
                $tpl->getValues()
            );

        $this->assertSame( $tpl, $tpl->append('other', 3.1415) );
        $this->assertEquals(
                array('var' => 'value', 'other' => 3.1415),
                $tpl->getValues()
            );

        $this->assertSame( $tpl, $tpl->append('var', ' add') );
        $this->assertEquals(
                array('var' => 'value add', 'other' => 3.1415),
                $tpl->getValues()
            );

        $this->assertSame( $tpl, $tpl->append('other', 'num') );
        $this->assertEquals(
                array('var' => 'value add', 'other' => '3.1415num'),
                $tpl->getValues()
            );
    }

    public function testImport_array ()
    {
        $tpl = $this->getMock('\r8\Template\Access', array('_mock'));
        $this->assertSame(
                $tpl,
                $tpl->import( array('var' => 'value', 'other' => 3.1415) )
            );
        $this->assertEquals(
                array('var' => 'value', 'other' => 3.1415),
                $tpl->getValues()
            );
    }

    public function testImport_Ary ()
    {
        $tpl = $this->getMock('\r8\Template\Access', array('_mock'));
        $this->assertSame(
                $tpl,
                $tpl->import( array('var' => 'value', 'other' => 3.1415) )
            );
        $this->assertEquals(
                array('var' => 'value', 'other' => 3.1415),
                $tpl->getValues()
            );
    }

    public function testImport_Iterable ()
    {
        $tpl = $this->getMock('\r8\Template\Access', array('_mock'));
        $this->assertSame(
                $tpl,
                $tpl->import( new ArrayIterator( array('var' => 'value', 'other' => 3.1415) ) )
            );
    }

    public function testImport_object ()
    {
        $obj = new stdClass;
        $obj->var = "value";
        $obj->other = 3.1415;

        $tpl = $this->getMock('\r8\Template\Access', array('_mock'));
        $this->assertSame( $tpl, $tpl->import( $obj ) );
        $this->assertEquals(
                array('var' => 'value', 'other' => 3.1415),
                $tpl->getValues()
            );
    }

    public function testImport_importable ()
    {
        $obj = $this->getMock('\r8\iface\Template\Importable');
        $obj->expects( $this->once() )
            ->method( "getTemplateValues" )
            ->will( $this->returnValue(
                array('var' => 'value', 'other' => 3.1415)
            ) );

        $tpl = $this->getMock('\r8\Template\Access', array('_mock'));
        $this->assertSame( $tpl, $tpl->import( $obj ) );
        $this->assertEquals(
                array('var' => 'value', 'other' => 3.1415),
                $tpl->getValues()
            );
    }

    public function testImport_notImportable ()
    {
        $tpl = $this->getMock('\r8\Template\Access', array('_mock'));

        try {
            $tpl->import( "String" );
            $this->fail('An expected exception was not thrown');
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "Value can not be imported", $err->getMessage() );
        }
    }

    public function testImport_badLabel ()
    {
        $tpl = $this->getMock('\r8\Template\Access', array('_mock'));

        try {
            $tpl->import( array("50" => "String") );
            $this->fail('An expected exception was not thrown');
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "Must be a valid PHP variable name", $err->getMessage() );
        }
    }

    public function testOverLoading ()
    {
        $tpl = $this->getMock('\r8\Template\Access', array('_mock'));

        $this->assertEquals( array(), $tpl->getValues() );
        $this->assertNull( $tpl->var );
        $this->assertFalse( isset($tpl->var) );

        $tpl->var = 'value';
        $this->assertSame( 'value', $tpl->var );
        $this->assertTrue( isset($tpl->var) );
        $this->assertEquals(
                array( 'var' => 'value' ),
                $tpl->getValues()
            );

        $tpl->pi = 3.1415;
        $this->assertSame( 3.1415, $tpl->pi );
        $this->assertTrue( isset($tpl->pi) );
        $this->assertEquals(
                array( 'var' => 'value', 'pi' => 3.1415 ),
                $tpl->getValues()
            );

        $tpl->var .= ' Lorem';
        $this->assertSame( 'value Lorem', $tpl->var );
        $this->assertTrue( isset($tpl->var) );
        $this->assertEquals(
                array( 'var' => 'value Lorem', 'pi' => 3.1415 ),
                $tpl->getValues()
            );

        unset( $tpl->var );
        $this->assertNull( $tpl->var );
        $this->assertFalse( isset($tpl->var) );
        $this->assertEquals(
                array( 'pi' => 3.1415 ),
                $tpl->getValues()
            );

        unset( $tpl->pi );
        $this->assertNull( $tpl->pi );
        $this->assertFalse( isset($tpl->pi) );
        $this->assertEquals( array(), $tpl->getValues() );
    }

}

?>