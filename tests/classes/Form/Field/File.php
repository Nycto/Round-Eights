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

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * Unit test for running both filesystem test suites
 */
class classes_form_field_file
{

    public static function suite()
    {
        $suite = new r8_Base_TestSuite;
        $suite->addTestSuite( 'classes_form_field_file_noFile' );
        $suite->addTestSuite( 'classes_form_field_file_withFile' );
        return $suite;
    }

}

/**
 * unit tests that don't require a temporary file
 */
class classes_form_field_file_noFile extends PHPUnit_Framework_TestCase
{

    public function testGetRawValue_noFile ()
    {
        $field = $this->getMock("r8\Form\Field\File", array("getUploadedFiles"), array("fld"));

        $field->expects( $this->once() )
            ->method("getUploadedFiles")
            ->will( $this->returnValue(array()) );

        $this->assertNull($field->getRawValue());
    }

    public function testGetRawValue_withFile ()
    {
        $field = $this->getMock("r8\Form\Field\File", array("getUploadedFiles"), array("fld"));

        $field->expects( $this->once() )
            ->method("getUploadedFiles")
            ->will( $this->returnValue(array("fld" => array(
                    "tmp_name" => "/dir/to/file.txt"
                ))) );

        $this->assertSame("/dir/to/file.txt", $field->getRawValue());
    }

    public function testValidate_invalidUpload ()
    {

        // Set up the FileUpload validator to return an invalid uploaded file
        $valid = $this->getMock("r8\Validator\FileUpload", array("getUploadedFiles", "isUploadedFile"));

        $valid->expects( $this->once() )
            ->method("getUploadedFiles")
            ->will( $this->returnValue(array("fld" => array(
                    "error" => UPLOAD_ERR_INI_SIZE,
                    "tmp_name" => "/dir/to/file.txt"
                ))) );


        // Set up file upload field to use the mock FileUpload validator
        $field = $this->getMock("r8\Form\Field\File", array("getFileUploadValidator"), array("fld"));

        $field->expects( $this->once() )
            ->method("getFileUploadValidator")
            ->will( $this->returnValue( $valid ) );


        // Run the simulation
        $result = $field->validate();
        $this->assertThat( $result, $this->isInstanceOf("r8\Validator\Result"));
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("File exceeds the server's maximum allowed size"),
                $result->getErrors()
            );

    }

    public function testGetTag ()
    {
        $field = new \r8\Form\Field\File("fld");
        $field->setName("fldName");

        $tag = $field->getTag();

        $this->assertThat( $tag, $this->isInstanceOf("r8\HTML\Tag") );
        $this->assertSame( "input", $tag->getTag() );

        $this->assertTrue( isset($tag['name']) );
        $this->assertSame( "fldName", $tag['name'] );

        $this->assertFalse( isset($tag['value']) );

        $this->assertTrue( isset($tag['type']) );
        $this->assertSame( "file", $tag['type'] );
    }

}

/**
 * Unit tests the need an actual file
 */
class classes_form_field_file_withFile extends PHPUnit_TestFile_Framework_TestCase
{

    public function testValidate_valid ()
    {

        // Set up the FileUpload validator to return a valid uploaded file
        $valid = $this->getMock("r8\Validator\FileUpload", array("getUploadedFiles", "isUploadedFile"));

        $valid->expects( $this->once() )
            ->method("isUploadedFile")
            ->will( $this->returnValue( TRUE ) );

        $valid->expects( $this->once() )
            ->method("getUploadedFiles")
            ->will( $this->returnValue(array("fld" => array(
                    "error" => 0,
                    "tmp_name" => $this->file
                ))) );


        // Set up file upload field to use the mock validator and mock $_FILES
        $field = $this->getMock("r8\Form\Field\File", array("getUploadedFiles", "getFileUploadValidator"), array("fld"));

        $field->expects( $this->once() )
            ->method("getUploadedFiles")
            ->will( $this->returnValue(array("fld" => array(
                    "tmp_name" => $this->file
                ))) );

        $field->expects( $this->once() )
            ->method("getFileUploadValidator")
            ->will( $this->returnValue( $valid ) );


        $this->assertTrue( $field->isValid() );
    }

    public function testValidate_otherValidator ()
    {
        // Set up the FileUpload validator to return a valid uploaded file
        $valid = $this->getMock("r8\Validator\FileUpload", array("getUploadedFiles", "isUploadedFile"));

        $valid->expects( $this->once() )
            ->method("isUploadedFile")
            ->will( $this->returnValue( TRUE ) );

        $valid->expects( $this->once() )
            ->method("getUploadedFiles")
            ->will( $this->returnValue(array("fld" => array(
                    "error" => 0,
                    "tmp_name" => $this->file
                ))) );


        // This result will be returned by the second validator
        $result = new \r8\Validator\Result( $this->file );

        // Set up another validator that should receive the filename
        $otherValid = $this->getMock("r8\iface\Validator", array("isValid", "validate"));
        $otherValid->expects( $this->once() )
            ->method("validate")
            ->with( $this->equalTo($this->file) )
            ->will( $this->returnValue( $result ) );


        // Set up the mock field to use the FileUpload validator and mock $_FILES
        $field = $this->getMock(
                '\r8\Form\Field\File',
                array("getUploadedFiles", "getFileUploadValidator"),
                array("fld")
            );

        $field->expects( $this->once() )
            ->method("getUploadedFiles")
            ->will( $this->returnValue(array("fld" => array(
                    "tmp_name" => $this->file
                ))) );

        $field->expects( $this->once() )
            ->method("getFileUploadValidator")
            ->will( $this->returnValue( $valid ) );


        $field->setValidator( $otherValid );

        $this->assertSame( $result, $field->validate() );
    }

}

?>