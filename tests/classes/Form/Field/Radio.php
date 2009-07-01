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

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_form_field_radio extends PHPUnit_Framework_TestCase
{

    public function testGetRadioOptionID ()
    {
        $field = new \h2o\Form\Field\Radio("fld");
        $field->importOptions(array(1 => "One", 2 => "Two", 3 => "Three"));

        $this->assertSame("radio_fld_356a192b79", $field->getRadioOptionID(1));
        $this->assertSame("radio_fld_da4b9237ba", $field->getRadioOptionID(2));
        $this->assertSame("radio_fld_77de68daec", $field->getRadioOptionID(3));

        try {
            $field->getRadioOptionID(4);
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\Index $err ) {
            $this->assertSame("Option does not exist in field", $err->getMessage());
        }
    }

    public function testGetOptionRadioTag_unchecked ()
    {
        $field = new \h2o\Form\Field\Radio("fld");
        $field->importOptions(array(1 => "One", 2 => "Two", 3 => "Three"));

        $tag = $field->getOptionRadioTag(2);

        $this->assertThat( $tag, $this->isInstanceOf("h2o\Tag") );
        $this->assertSame( "input", $tag->getTag() );

        $this->assertTrue( isset($tag['name']) );
        $this->assertSame( "fld", $tag['name'] );

        $this->assertTrue( isset($tag['value']) );
        $this->assertSame( 2, $tag['value'] );

        $this->assertTrue( isset($tag['type']) );
        $this->assertSame( "radio", $tag['type'] );

        $this->assertTrue( isset($tag['id']) );
        $this->assertSame( "radio_fld_da4b9237ba", $tag['id'] );

        $this->assertFalse( isset($tag['checked']) );

        $this->assertNull($tag->getcontent());
    }

    public function testGetOptionRadioTag_checked ()
    {
        $field = new \h2o\Form\Field\Radio("fld");
        $field->importOptions(array(1 => "One", 2 => "Two", 3 => "Three"));
        $field->setValue( 2 ) ;

        $tag = $field->getOptionRadioTag(2);

        $this->assertThat( $tag, $this->isInstanceOf("h2o\Tag") );
        $this->assertSame( "input", $tag->getTag() );

        $this->assertTrue( isset($tag['name']) );
        $this->assertSame( "fld", $tag['name'] );

        $this->assertTrue( isset($tag['value']) );
        $this->assertSame( 2, $tag['value'] );

        $this->assertTrue( isset($tag['type']) );
        $this->assertSame( "radio", $tag['type'] );

        $this->assertTrue( isset($tag['id']) );
        $this->assertSame( "radio_fld_da4b9237ba", $tag['id'] );

        $this->assertTrue( isset($tag['checked']) );
        $this->assertSame( "checked", $tag['checked'] );

        $this->assertNull($tag->getcontent());
    }

    public function testGetOptionRadioTag_error ()
    {
        $field = new \h2o\Form\Field\Radio("fld");
        $field->importOptions(array(1 => "One", 2 => "Two", 3 => "Three"));

        try {
            $field->getOptionRadioTag(4);
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\Index $err ) {
            $this->assertSame("Option does not exist in field", $err->getMessage());
        }
    }

    public function testGetOptionLabelTag ()
    {
        $field = new \h2o\Form\Field\Radio("fld");
        $field->importOptions(array(1 => "One", 2 => "Two", 3 => "Three"));


        $tag = $field->getOptionLabelTag(2);

        $this->assertThat( $tag, $this->isInstanceOf("h2o\Tag") );
        $this->assertSame( "label", $tag->getTag() );

        $this->assertTrue( isset($tag['for']) );
        $this->assertSame( "radio_fld_da4b9237ba", $tag['for'] );

        $this->assertSame( "Two", $tag->getcontent() );


        try {
            $field->getOptionLabelTag(4);
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\Index $err ) {
            $this->assertSame("Option does not exist in field", $err->getMessage());
        }
    }

    public function testGetOptionList ()
    {
        $field = new \h2o\Form\Field\Radio("fld");
        $field->importOptions(array(1 => "One", 2 => "Two", 3 => "Three"));
        $field->setValue(3);

        $this->assertSame(
                '<li><input name="fld" value="1" type="radio" id="radio_fld_356a192b79" /> '
                .'<label for="radio_fld_356a192b79">One</label></li>'

                .'<li><input name="fld" value="2" type="radio" id="radio_fld_da4b9237ba" /> '
                .'<label for="radio_fld_da4b9237ba">Two</label></li>'

                .'<li><input name="fld" value="3" type="radio" id="radio_fld_77de68daec" checked="checked" /> '
                .'<label for="radio_fld_77de68daec">Three</label></li>',
                $field->getOptionList()
            );
    }

    public function testGetTag_noOptions ()
    {
        $field = new \h2o\Form\Field\Radio("fld");
        $field->setName("fldName");

        $tag = $field->getTag();

        $this->assertThat( $tag, $this->isInstanceOf("h2o\Tag") );
        $this->assertSame( "ul", $tag->getTag() );

        $this->assertNull($tag->getcontent());
    }

    public function testGetTag_withOptions ()
    {
        $field = new \h2o\Form\Field\Radio("fld");
        $field->setName("fldName");
        $field->addOption("one", "Single");
        $field->addOption("two", "Double");

        $tag = $field->getTag();

        $this->assertThat( $tag, $this->isInstanceOf("h2o\Tag") );
        $this->assertSame( "ul", $tag->getTag() );

        $this->assertSame(
                '<li><input name="fldName" value="one" type="radio" id="radio_fldName_fe05bcdcdc" /> '
                .'<label for="radio_fldName_fe05bcdcdc">Single</label></li>'
                .'<li><input name="fldName" value="two" type="radio" id="radio_fldName_ad782ecdac" /> '
                .'<label for="radio_fldName_ad782ecdac">Double</label></li>',
                $tag->getcontent()
            );
    }

}

?>