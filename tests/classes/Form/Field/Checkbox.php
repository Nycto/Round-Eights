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

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_form_field_checkbox extends PHPUnit_Framework_TestCase
{

    public function testDefaults ()
    {
        $field = new \r8\Form\Field\Checkbox("fld");

        $this->assertThat(
                $field->getFilter(),
                $this->isInstanceOf("r8\Filter\Boolean")
            );

        $this->assertFalse( $field->getValue() );
    }

    public function testGetTag ()
    {
        $field = new \r8\Form\Field\Checkbox("fld");
        $field->setName("fldName");

        $tag = $field->getTag();

        $this->assertThat( $tag, $this->isInstanceOf("r8\HTML\Tag") );
        $this->assertSame( "input", $tag->getTag() );

        $this->assertTrue( isset($tag['name']) );
        $this->assertSame( "fldName", $tag['name'] );

        $this->assertTrue( isset($tag['value']) );
        $this->assertSame( "on", $tag['value'] );

        $this->assertTrue( isset($tag['type']) );
        $this->assertSame( "checkbox", $tag['type'] );

        $this->assertFalse( isset($tag['checked']) );


        $field->setValue('on');

        $tag = $field->getTag();

        $this->assertTrue( isset($tag['checked']) );
        $this->assertSame( "checked", $tag['checked'] );
    }

}

?>