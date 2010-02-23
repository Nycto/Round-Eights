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
class classes_Form_select extends PHPUnit_Framework_TestCase
{

    public function testGetOptionList ()
    {
        $field = new \r8\Form\Select("fld");

        $this->assertSame("", $field->getOptionList());

        $field->importOptions(array(1 => "One", 2 => "Two", 3 => "Three"));

        $this->assertSame(
                "<option value='1'>One</option>"
                ."<option value='2'>Two</option>"
                ."<option value='3'>Three</option>",
                $field->getOptionList()
            );

        $field->setValue(2);

        $this->assertSame(
                "<option value='1'>One</option>"
                ."<option value='2' selected='selected'>Two</option>"
                ."<option value='3'>Three</option>",
                $field->getOptionList()
            );
    }

    public function testGetTag_noOptions ()
    {
        $field = new \r8\Form\Select("fld");
        $field->setName("fldName");

        $tag = $field->getTag();

        $this->assertThat( $tag, $this->isInstanceOf("r8\HTML\Tag") );
        $this->assertSame( "select", $tag->getTag() );

        $this->assertTrue( isset($tag['name']) );
        $this->assertSame( "fldName", $tag['name'] );

        $this->assertNull($tag->getcontent());
    }

    public function testGetTag_withOptions ()
    {
        $field = new \r8\Form\Select("fld");
        $field->setName("fldName");
        $field->addOption("one", "Single");
        $field->addOption("two", "Double");

        $tag = $field->getTag();

        $this->assertThat( $tag, $this->isInstanceOf("r8\HTML\Tag") );
        $this->assertSame( "select", $tag->getTag() );

        $this->assertTrue( isset($tag['name']) );
        $this->assertSame( "fldName", $tag['name'] );

        $this->assertSame(
                "<option value='one'>Single</option><option value='two'>Double</option>",
                $tag->getcontent()
            );
    }

}

?>