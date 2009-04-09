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
class classes_form extends PHPUnit_Framework_TestCase
{

    public function getMockField ()
    {
        return $this->getMock(
                "\cPHP\iface\Form\Field",
                array("getName", "getValue", "setValue", "validate", "isValid")
            );
    }

    public function testSetAction ()
    {
        $form = new \cPHP\Form;

        $this->assertSame( $form, $form->setAction("/file.php") );
        $this->assertSame( "/file.php", $form->getAction() );

        $this->assertSame( $form, $form->setAction("http://www.example.com/dir/file.php") );
        $this->assertSame(
                "http://www.example.com/dir/file.php",
                $form->getAction()
            );
    }

    public function testSetMethod()
    {
        $form = new \cPHP\Form;

        $this->assertSame( "POST", $form->getMethod() );

        $this->assertSame( $form, $form->setMethod("GET") );
        $this->assertSame( "GET", $form->getMethod() );

        try {
            $form->setMethod("   ");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }
    }

    public function testSetEncoding ()
    {
        $form = new \cPHP\Form;

        $this->assertSame( "application/x-www-form-urlencoded", $form->getEncoding() );

        $this->assertSame( $form, $form->setEncoding("multipart/form-data") );
        $this->assertSame( "multipart/form-data", $form->getEncoding() );

        try {
            $form->setEncoding("   ");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }
    }

    public function testGetAddField ()
    {
        $form = new \cPHP\Form;

        $this->assertSame( array(), $form->getFields() );

        // Add a field
        $field1 = $this->getMockField();
        $this->assertSame( $form, $form->addField($field1) );
        $this->assertSame( array($field1), $form->getFields() );


        // Make sure duplicates aren't allowed
        $this->assertSame( $form, $form->addField($field1) );
        $this->assertSame( array($field1), $form->getFields() );


        // Add another field
        $field2 = $this->getMockField();
        $this->assertSame( $form, $form->addField($field2) );
        $this->assertSame( array($field1, $field2), $form->getFields() );
    }

    public function testClearFields ()
    {
        $form = new \cPHP\Form;

        $field1 = $this->getMockField();
        $form->addField($field1);

        $field2 = $this->getMockField();
        $form->addField($field2);

        // Make sure the two fields were properly added
        $this->assertSame( array($field1, $field2), $form->getFields() );

        $this->assertSame( $form, $form->clearFields() );
        $this->assertSame( array(), $form->getFields() );
    }

    public function testCount ()
    {
        $form = new \cPHP\Form;
        $this->assertSame( 0, $form->count() );
        $this->assertSame( 0, count($form) );

        $form->addField($this->getMockField());
        $this->assertSame( 1, $form->count() );
        $this->assertSame( 1, count($form) );

        $form->addField($this->getMockField());
        $this->assertSame( 2, $form->count() );
        $this->assertSame( 2, count($form) );

        $form->addField($this->getMockField());
        $this->assertSame( 3, $form->count() );
        $this->assertSame( 3, count($form) );

        $form->clearFields();
        $this->assertSame( 0, $form->count() );
        $this->assertSame( 0, count($form) );
    }

    public function testFind ()
    {
        $field1 = $this->getMockField();
        $field1->expects( $this->any() )
            ->method("getName")
            ->will( $this->returnValue("fldOne") );

        $field2 = $this->getMockField();
        $field2->expects( $this->any() )
            ->method("getName")
            ->will( $this->returnValue("fldTwo") );

        $form = new \cPHP\Form;
        $form->addField( $field1 )->addField( $field2 );

        $this->assertSame( $field1, $form->find("fldOne") );


        $this->assertNull( $form->find("No Field") );

        try {
             $form->find("123");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame("Must be a valid PHP variable name", $err->getMessage());
        }

    }

    public function testAnyIn ()
    {
        $form = new \cPHP\Form;

        $this->assertFalse(
                $form->anyIn(array( "fldOne" => "value", "fldThree" => "other" ))
            );


        $field1 = $this->getMockField();
        $field1->expects( $this->any() )
            ->method("getName")
            ->will( $this->returnValue("fldOne") );
        $form->addField( $field1 );

        $this->assertTrue(
                $form->anyIn(array( "fldOne" => "value", "fldThree" => "other" ))
            );

        $this->assertFalse(
                $form->anyIn(array( "fldTwo" => "value", "fldThree" => "other" ))
            );


        $field2 = $this->getMockField();
        $field2->expects( $this->any() )
            ->method("getName")
            ->will( $this->returnValue("fldTwo") );
        $form->addField( $field2 );

        $this->assertTrue(
                $form->anyIn(array( "fldOne" => "value", "fldTwo" => "other" ))
            );

        $this->assertTrue(
                $form->anyIn( array( "fldThree" => "value", "fldOne" => "other" ) )
            );

    }

    public function testFill_array ()
    {
        $form = new \cPHP\Form;

        $field1 = $this->getMockField();
        $field1->expects( $this->any() )
            ->method("getName")
            ->will( $this->returnValue("fldOne") );
        $field1->expects( $this->once() )
            ->method("setValue")
            ->with( $this->equalTo("Value One") );
        $form->addField( $field1 );

        $field2 = $this->getMockField();
        $field2->expects( $this->any() )
            ->method("getName")
            ->will( $this->returnValue("fldTwo") );
        $field2->expects( $this->once() )
            ->method("setValue")
            ->with( $this->equalTo(null) );
        $form->addField( $field2 );

        $field3 = $this->getMockField();
        $field3->expects( $this->any() )
            ->method("getName")
            ->will( $this->returnValue("fldThree") );
        $field3->expects( $this->once() )
            ->method("setValue")
            ->with( $this->equalTo(3) );
        $form->addField( $field3 );


        $this->assertSame(
                $form,
                $form->fill(array( "fldOne" => "Value One", "filler" => FALSE, "fldThree" => 3 ))
            );
    }

    public function testFill_Ary ()
    {
        $form = new \cPHP\Form;

        $field1 = $this->getMockField();
        $field1->expects( $this->any() )
            ->method("getName")
            ->will( $this->returnValue("fldOne") );
        $field1->expects( $this->once() )
            ->method("setValue")
            ->with( $this->equalTo("Value One") );
        $form->addField( $field1 );

        $field2 = $this->getMockField();
        $field2->expects( $this->any() )
            ->method("getName")
            ->will( $this->returnValue("fldTwo") );
        $field2->expects( $this->once() )
            ->method("setValue")
            ->with( $this->equalTo(null) );
        $form->addField( $field2 );

        $field3 = $this->getMockField();
        $field3->expects( $this->any() )
            ->method("getName")
            ->will( $this->returnValue("fldThree") );
        $field3->expects( $this->once() )
            ->method("setValue")
            ->with( $this->equalTo(null) );
        $form->addField( $field3 );


        $this->assertSame(
                $form,
                $form->fill(array(
                        "fldOne" => "Value One",
                        "filler" => FALSE,
                        "fldThree" => null
                    ))
            );
    }

    public function testFill_ArrayIterator ()
    {
        $form = new \cPHP\Form;

        $field1 = $this->getMockField();
        $field1->expects( $this->any() )
            ->method("getName")
            ->will( $this->returnValue("fldOne") );
        $field1->expects( $this->once() )
            ->method("setValue")
            ->with( $this->equalTo("Value One") );
        $form->addField( $field1 );

        $field2 = $this->getMockField();
        $field2->expects( $this->any() )
            ->method("getName")
            ->will( $this->returnValue("fldTwo") );
        $field2->expects( $this->once() )
            ->method("setValue")
            ->with( $this->equalTo(FALSE) );
        $form->addField( $field2 );

        $field3 = $this->getMockField();
        $field3->expects( $this->any() )
            ->method("getName")
            ->will( $this->returnValue("fldThree") );
        $field3->expects( $this->once() )
            ->method("setValue")
            ->with( $this->equalTo(null) );
        $form->addField( $field3 );


        $this->assertSame(
                $form,
                $form->fill(array(
                        "fldOne" => "Value One",
                        "filler" => FALSE,
                        "fldTwo" => FALSE
                    ))
            );
    }

    public function testFill_ArrayObect ()
    {
        $form = new \cPHP\Form;

        $field1 = $this->getMockField();
        $field1->expects( $this->any() )
            ->method("getName")
            ->will( $this->returnValue("fldOne") );
        $field1->expects( $this->once() )
            ->method("setValue")
            ->with( $this->equalTo(null) );
        $form->addField( $field1 );

        $field2 = $this->getMockField();
        $field2->expects( $this->any() )
            ->method("getName")
            ->will( $this->returnValue("fldTwo") );
        $field2->expects( $this->once() )
            ->method("setValue")
            ->with( $this->equalTo(10.5) );
        $form->addField( $field2 );

        $field3 = $this->getMockField();
        $field3->expects( $this->any() )
            ->method("getName")
            ->will( $this->returnValue("fldThree") );
        $field3->expects( $this->once() )
            ->method("setValue")
            ->with( $this->isInstanceOf("ArrayObject") );
        $form->addField( $field3 );


        $this->assertSame(
                $form,
                $form->fill(array(
                        "fldTwo" => 10.5,
                        "fldThree" => new ArrayObject
                    ))
            );
    }

    public function testIsValid ()
    {
        $form = new \cPHP\Form;

        $this->assertTrue( $form->isValid() );


        $field1 = $this->getMockField();
        $field1->expects( $this->exactly(4) )
            ->method("isValid")
            ->will( $this->returnValue(TRUE) );

        $form->addField( $field1 );

        $this->assertTrue( $form->isValid() );


        $field2 = $this->getMockField();
        $field2->expects( $this->exactly(3) )
            ->method("isValid")
            ->will( $this->returnValue(TRUE) );

        $form->addField( $field2 );

        $this->assertTrue( $form->isValid() );


        $field3 = $this->getMockField();
        $field3->expects( $this->exactly(2) )
            ->method("isValid")
            ->will( $this->returnValue(FALSE) );

        $form->addField( $field3 );

        $this->assertFalse( $form->isValid() );


        $field4 = $this->getMockField();
        $field4->expects( $this->never() )
            ->method("isValid")
            ->will( $this->returnValue(TRUE) );

        $form->addField( $field4 );

        $this->assertFalse( $form->isValid() );

    }

    public function testGetTag ()
    {
        $form = new \cPHP\Form;
        $form->setMethod("get");
        $form->setEncoding('multipart/form-data');
        $form->setAction('/dir/file.php');

        $tag = $form->getTag();

        $this->assertThat( $tag, $this->isInstanceOf("cPHP\Tag") );
        $this->assertSame( "form", $tag->getTag() );

        $this->assertTrue( isset($tag['method']) );
        $this->assertSame( "get", $tag['method'] );

        $this->assertTrue( isset($tag['encoding']) );
        $this->assertSame( "multipart/form-data", $tag['encoding'] );

        $this->assertTrue( isset($tag['action']) );
        $this->assertSame( "/dir/file.php", $tag['action'] );
    }

    public function testToString ()
    {

        $form = new \cPHP\Form;
        $form->setMethod("get");
        $form->setEncoding('multipart/form-data');
        $form->setAction('/dir/file.php');

        $this->assertSame(
                '<form method="get" encoding="multipart/form-data" action="/dir/file.php">',
                $form->__toString()
            );

        $this->assertSame(
                '<form method="get" encoding="multipart/form-data" action="/dir/file.php">',
                "$form"
            );
    }

    public function testGetHidden ()
    {
        $form = new \cPHP\Form;

        $this->assertSame( array(), $form->getHidden() );


        $field1 = new \cPHP\Form\Field\Hidden("fld1");
        $field2 = new \cPHP\Form\Field\Text("fld2");
        $field3 = new \cPHP\Form\Field\Hidden("fld3");

        $form->addField( $field1 )
            ->addField( $field2 )
            ->addField( $field3 );

        $this->assertSame( array( 0 => $field1, 2 => $field3 ), $form->getHidden() );
    }

    public function testGetHiddenHTML ()
    {
        $form = new \cPHP\Form;

        $this->assertSame( "", $form->getHiddenHTML() );


        $field1 = new \cPHP\Form\Field\Hidden("fld1");
        $field2 = new \cPHP\Form\Field\Text("fld2");
        $field3 = new \cPHP\Form\Field\Hidden("fld3");

        $form->addField( $field1 )
            ->addField( $field2 )
            ->addField( $field3 );


        $this->assertSame(
                '<input value="" name="fld1" type="hidden" />'
                .'<input value="" name="fld3" type="hidden" />',
                $form->getHiddenHTML()
            );
    }

}

?>