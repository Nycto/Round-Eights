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
 * unit tests
 */
class classes_form_multi extends PHPUnit_Framework_TestCase
{

    public function testAddOption_strValue ()
    {
        $mock = $this->getMock("r8\Form\Multi", array("_mock"), array("fld"));

        $this->assertSame( $mock, $mock->addOption("str", "lbl") );

        $this->assertSame( array("str" => "lbl"), $mock->getOptions() );
    }

    public function testAddOption_intValue ()
    {
        $mock = $this->getMock("r8\Form\Multi", array("_mock"), array("fld"));

        $this->assertSame( $mock, $mock->addOption(50, "lbl") );

        $this->assertSame( array(50 => "lbl"), $mock->getOptions() );
    }

    public function testAddOption_floatValue ()
    {
        $mock = $this->getMock("r8\Form\Multi", array("_mock"), array("fld"));

        $this->assertSame( $mock, $mock->addOption(1.5, "othr") );

        $this->assertSame(
                array(1 => "othr"),
                $mock->getOptions()
            );
    }

    public function testAddOption_boolValue ()
    {

        $mock = $this->getMock("r8\Form\Multi", array("_mock"), array("fld"));

        $this->assertSame( $mock, $mock->addOption(FALSE, "lbl") );

        $this->assertSame(
                array(0 => "lbl"),
                $mock->getOptions()
            );

        $this->assertSame( $mock, $mock->addOption(TRUE, "lbl2") );

        $this->assertSame(
                array(0 => "lbl", 1 => "lbl2"),
                $mock->getOptions()
            );
    }

    public function testAddOption_nullValue ()
    {

        $mock = $this->getMock("r8\Form\Multi", array("_mock"), array("fld"));

        $this->assertSame( $mock, $mock->addOption(null, "lbl") );

        $this->assertSame(
                array("" => "lbl"),
                $mock->getOptions()
            );
    }

    public function testAddOption_objValue ()
    {

        $mock = $this->getMock("r8\Form\Multi", array("_mock"), array("fld"));

        $this->assertSame( $mock, $mock->addOption( $this->getMock("stub"), "lbl") );

        $this->assertSame(
                array("" => "lbl"),
                $mock->getOptions()
            );
    }

    public function testAddOption_nonStringLabel ()
    {
        $mock = $this->getMock("r8\Form\Multi", array("_mock"), array("fld"));

        $this->assertSame( $mock, $mock->addOption( 1, 5) );
        $this->assertSame(
                array(1 => "5"),
                $mock->getOptions()
            );

        $this->assertSame( $mock, $mock->addOption( 2, 27.8) );
        $this->assertSame(
                array(1 => "5", 2 => "27.8"),
                $mock->getOptions()
            );
    }

    public function testAddOption_conflict ()
    {

        $mock = $this->getMock("r8\Form\Multi", array("_mock"), array("fld"));

        $this->assertSame( $mock, $mock->addOption( "val", "one") );
        $this->assertSame(
                array("val" => "one"),
                $mock->getOptions()
            );


        $this->assertSame( $mock, $mock->addOption( "val", "two") );
        $this->assertSame(
                array("val" => "two"),
                $mock->getOptions()
            );
    }

    public function testHasOption ()
    {
        $mock = $this->getMock("r8\Form\Multi", array("_mock"), array("fld"));
        $mock->addOption( 1, "one");
        $mock->addOption( 2, "two");
        $mock->addOption( 3, "three");

        $this->assertTrue( $mock->hasOption(1) );
        $this->assertTrue( $mock->hasOption(2) );
        $this->assertTrue( $mock->hasOption(3) );

        $this->assertFalse( $mock->hasOption(4) );
    }

    public function testGetOptionLabel ()
    {
        $mock = $this->getMock("r8\Form\Multi", array("_mock"), array("fld"));
        $mock->addOption( 1, "one");
        $mock->addOption( 2, "two");
        $mock->addOption( 3, "three");

        $this->assertSame( "one", $mock->getOptionLabel(1) );
        $this->assertSame( "two", $mock->getOptionLabel(2) );
        $this->assertSame( "three", $mock->getOptionLabel(3) );

        $this->assertSame( "one", $mock->getOptionLabel("1") );
        $this->assertSame( "two", $mock->getOptionLabel("2") );
        $this->assertSame( "three", $mock->getOptionLabel("3") );

        try {
            $mock->getOptionLabel(4);
            $this->fail("An expected exception was not thrown");
        }
        catch( \r8\Exception\Index $err ) {
            $this->assertSame("Option does not exist in field", $err->getMessage());
        }
    }

    public function testRemoveOption ()
    {
        $mock = $this->getMock("r8\Form\Multi", array("_mock"), array("fld"));
        $mock->addOption( 1, "one");
        $mock->addOption( 2, "two");
        $mock->addOption( 3, "three");
        $this->assertSame(
                array(1 => 'one', 2 => 'two', 3 => 'three'),
                $mock->getOptions()
            );

        $this->assertSame( $mock, $mock->removeOption(2) );
        $this->assertSame(
                array(1 => 'one', 3 => 'three'),
                $mock->getOptions()
            );
    }

    public function testClearOptions ()
    {

        $mock = $this->getMock("r8\Form\Multi", array("_mock"), array("fld"));
        $mock->addOption( 1, "one");
        $mock->addOption( 2, "two");
        $mock->addOption( 3, "three");

        $this->assertSame(
                array(1 => 'one', 2 => 'two', 3 => 'three'),
                $mock->getOptions()
            );

        $this->assertSame( $mock, $mock->clearOptions() );
        $this->assertSame( array(), $mock->getOptions() );
    }

    public function testImportOptions ()
    {
        $mock = $this->getMock("r8\Form\Multi", array("_mock"), array("fld"));

        $this->assertSame(
                $mock,
                $mock->importOptions(array(1 => 'one', 2 => 'two', 3 => 'three'))
            );

        $this->assertSame(
                array(1 => 'one', 2 => 'two', 3 => 'three'),
                $mock->getOptions()
            );
    }

    public function testDefaultValidator ()
    {
        $field = $this->getMock("r8\Form\Multi", array("_mock"), array("fld"));
        $field->importOptions(array("one" => "Single", 2 => "Double", "three" => "Triple"));

        $this->assertThat(
                $field->getValidator(),
                $this->isInstanceOf("r8\Validator\MultiField")
            );

        $field->setValue( "one" );
        $this->assertTrue( $field->isValid() );

        $field->setValue( 5 );
        $this->assertFalse( $field->isValid() );
    }

}

?>