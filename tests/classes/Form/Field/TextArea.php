<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of RaindropPHP.
 *
 * RaindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * RaindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with RaindropPHP. If not, see <http://www.RaindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@Raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_form_field_textarea extends PHPUnit_Framework_TestCase
{

    public function testGetTag ()
    {
        $field = new \h2o\Form\Field\TextArea("fld");
        $field->setValue("New Value")
            ->setName("fldName");

        $tag = $field->getTag();

        $this->assertThat( $tag, $this->isInstanceOf("h2o\Tag") );
        $this->assertSame( "textarea", $tag->getTag() );

        $this->assertTrue( isset($tag['name']) );
        $this->assertSame( "fldName", $tag['name'] );

        $this->assertSame( "New Value", $tag->getContent() );
    }

    public function testGetTag_outFiltered ()
    {
        $field = new \h2o\Form\Field\TextArea("fld");
        $field->setValue("New Value")
            ->setName("fldName");


        $outFilter = $this->getMock("h2o\iface\Filter", array("filter"));
        $outFilter->expects( $this->once() )
            ->method("filter")
            ->with("New Value")
            ->will( $this->returnValue("Filtered New Value") );

        $field->setOutputFilter($outFilter);

        $tag = $field->getTag();

        $this->assertSame( "Filtered New Value", $tag->getContent() );
    }

}

?>