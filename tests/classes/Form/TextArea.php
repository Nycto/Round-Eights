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
class classes_Form_textarea extends PHPUnit_Framework_TestCase
{

    public function testGetTag ()
    {
        $field = new \r8\Form\TextArea("fld");
        $field->setValue("New Value")
            ->setName("fldName");

        $tag = $field->getTag();

        $this->assertThat( $tag, $this->isInstanceOf("r8\HTML\Tag") );
        $this->assertSame( "textarea", $tag->getTag() );

        $this->assertTrue( isset($tag['name']) );
        $this->assertSame( "fldName", $tag['name'] );

        $this->assertSame( "New Value", $tag->getContent() );
    }

    public function testGetTag_outFiltered ()
    {
        $field = new \r8\Form\TextArea("fld");
        $field->setValue("New Value")
            ->setName("fldName");


        $outFilter = $this->getMock("r8\iface\Filter", array("filter"));
        $outFilter->expects( $this->once() )
            ->method("filter")
            ->with("New Value")
            ->will( $this->returnValue("Filtered New Value") );

        $field->setOutputFilter($outFilter);

        $tag = $field->getTag();

        $this->assertSame( "Filtered New Value", $tag->getContent() );
    }

    public function testVisit ()
    {
        $field = new \r8\Form\TextArea("fld");

        $visitor = $this->getMock('\r8\iface\Form\Visitor');
        $visitor->expects( $this->once() )
            ->method( "textArea" )
            ->with( $this->equalTo( $field ) );

        $this->assertNull( $field->visit( $visitor ) );
    }

}

?>