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
 * unit tests
 */
class classes_form_field extends PHPUnit_Framework_TestCase
{

    public function testSetGetName ()
    {
        $field = $this->getMock("cPHP\Form\Field", array("_mock"), array("fld"));

        $this->assertSame("fld", $field->getName());

        $this->assertSame( $field, $field->setName("fieldName") );
        $this->assertSame("fieldName", $field->getName());

        try {
            $field->setName("123");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame( "Must be a valid PHP variable name", $err->getMessage() );
        }
    }

    public function testGetFilter ()
    {
        $field = $this->getMock("cPHP\Form\Field", array("_mock"), array("fld"));

        $filter = $field->getFilter();

        $this->assertThat( $filter, $this->isInstanceOf("cPHP\Filter\Chain") );

        $this->assertSame( $filter, $field->getFilter() );
    }

    public function testSetFilter ()
    {
        $field = $this->getMock("cPHP\Form\Field", array("_mock"), array("fld"));

        $filter = $this->getMock("cPHP\iface\Filter", array("filter"));

        $this->assertSame( $field, $field->setFilter($filter) );

        $this->assertSame( $filter, $field->getFilter() );
    }

    public function testGetOutputFilter ()
    {
        $field = $this->getMock("cPHP\Form\Field", array("_mock"), array("fld"));

        $filter = $field->getOutputFilter();

        $this->assertThat( $filter, $this->isInstanceOf("cPHP\Filter\Chain") );

        $this->assertSame( $filter, $field->getOutputFilter() );
    }

    public function testSetOutputFilter ()
    {
        $field = $this->getMock("cPHP\Form\Field", array("_mock"), array("fld"));

        $filter = $this->getMock("cPHP\iface\Filter", array("filter"));

        $this->assertSame( $field, $field->setOutputFilter($filter) );

        $this->assertSame( $filter, $field->getOutputFilter() );
        $this->assertSame( $filter, $field->getOutputFilter() );
    }

    public function testGetValidator ()
    {
        $field = $this->getMock("cPHP\Form\Field", array("_mock"), array("fld"));

        $validator = $field->getValidator();

        $this->assertThat( $validator, $this->isInstanceOf("cPHP\Validator\Any") );

        $this->assertSame( $validator, $field->getValidator() );
    }

    public function testSetValidator ()
    {
        $field = $this->getMock("cPHP\Form\Field", array("_mock"), array("fld"));

        $validator = $this->getMock("cPHP\iface\Validator", array("validate", "isValid"));

        $this->assertSame( $field, $field->setValidator($validator) );

        $this->assertSame( $validator, $field->getValidator() );
    }

    public function testAndValidator ()
    {
        $field = $this->getMock("cPHP\Form\Field", array("_mock"), array("fld"));
        $validator = $this->getMock("cPHP\iface\Validator", array("validate", "isValid"));
        $validator2 = $this->getMock("cPHP\iface\Validator", array("validate", "isValid"));
        $validator3 = $this->getMock("cPHP\iface\Validator", array("validate", "isValid"));

        $field->setValidator( $validator );
        $this->assertSame( $validator, $field->getValidator() );


        $this->assertSame( $field, $field->andValidator( $validator2 ) );

        $all = $field->getValidator();
        $this->assertThat( $all, $this->isInstanceOf("cPHP\Validator\All") );
        $this->assertSame(
                array( $validator, $validator2 ),
                $all->getValidators()
            );


        $this->assertSame( $field, $field->andValidator( $validator3 ) );

        $all = $field->getValidator();
        $this->assertThat( $all, $this->isInstanceOf("cPHP\Validator\All") );
        $this->assertSame(
                array( $validator, $validator2, $validator3 ),
                $all->getValidators()
            );
    }

    public function testSetValue ()
    {
        $field = $this->getMock("cPHP\Form\Field", array("_mock"), array("fld"));

        $this->assertNull( $field->getRawValue() );

        $this->assertSame( $field, $field->setValue("New Value") );
        $this->assertSame( "New Value", $field->getRawValue() );

        $this->assertSame( $field, $field->setValue( array(505) ) );
        $this->assertSame( 505, $field->getRawValue() );

        $this->assertSame( $field, $field->setValue( TRUE ) );
        $this->assertSame( TRUE, $field->getRawValue() );

        $this->assertSame( $field, $field->setValue( NULL ) );
        $this->assertSame( NULL, $field->getRawValue() );

        $this->assertSame( $field, $field->setValue( 0.22 ) );
        $this->assertSame( 0.22, $field->getRawValue() );
    }

    public function testGetValue ()
    {
        $field = $this->getMock("cPHP\Form\Field", array("_mock"), array("fld"));

        $field->setValue("New Value");

        $this->assertSame("New Value", $field->getValue());

        $field->setFilter( new \cPHP\Curry\Call("strtoupper") );

        // the output filter should NOT be called
        $field->setOutputFilter( new \cPHP\Filter\Digits );

        $this->assertSame("NEW VALUE", $field->getValue());

        $this->assertSame("New Value", $field->getRawValue());
    }

    public function testGetForOutput ()
    {
        $field = $this->getMock("cPHP\Form\Field", array("_mock"), array("fld"));

        $field->setValue("New 123 Value");

        $field->setFilter( new \cPHP\Filter\Alpha );
        $field->setOutputFilter( new \cPHP\Curry\Call("strtoupper") );

        $this->assertSame("New 123 Value", $field->getRawValue());
        $this->assertSame("NewValue", $field->getValue());
        $this->assertSame("NEWVALUE", $field->getForOutput());
    }

    public function testValidate ()
    {
        $field = $this->getMock("cPHP\Form\Field", array("_mock"), array("fld"));
        $field->setValidator( new \cPHP\Validator\NoSpaces );

        $field->setValue("Some String 123");
        $result = $field->validate();
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Validator\Result") );
        $this->assertFalse( $result->isValid() );

        $field->setValue("SomeString123");
        $result = $field->validate();
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Validator\Result") );
        $this->assertTrue( $result->isValid() );
    }

    public function testIsValid ()
    {
        $field = $this->getMock("cPHP\Form\Field", array("_mock"), array("fld"));
        $field->setValidator( new \cPHP\Validator\NoSpaces );

        $field->setValue("Some String 123");
        $this->assertFalse( $field->isValid() );

        $field->setValue("SomeString123");
        $this->assertTrue( $field->isValid() );
    }

    public function testGetTag ()
    {
        $field = $this->getMock("cPHP\Form\Field", array("_mock"), array("fld"));
        $field->setValue("New Value")
            ->setName("fldName");

        $outFilter = $this->getMock("cPHP\iface\Filter", array("filter"));
        $outFilter->expects( $this->once() )
            ->method("filter")
            ->with("New Value")
            ->will( $this->returnValue("Filtered New Value") );

        $field->setOutputFilter($outFilter);

        $tag = $field->getTag();

        $this->assertThat( $tag, $this->isInstanceOf("cPHP\Tag") );
        $this->assertSame( "input", $tag->getTag() );
        $this->assertTrue( isset($tag['name']) );
        $this->assertSame( "fldName", $tag['name'] );
        $this->assertTrue( isset($tag['value']) );
        $this->assertSame( "Filtered New Value", $tag['value'] );
    }

    public function testToString ()
    {
        $field = $this->getMock("cPHP\Form\Field", array("_mock"), array("fld"));
        $field->setValue("New Value")
            ->setName("fldName");

        $this->assertSame(
                '<input value="New Value" name="fldName" />',
                $field->__toString()
            );

        $this->assertSame(
                '<input value="New Value" name="fldName" />',
                "$field"
            );
    }

}

?>