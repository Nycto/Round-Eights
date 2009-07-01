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

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * File Upload unit test suite
 */
class classes_validator_fileupload
{

    public static function suite()
    {
        $suite = new h2o_Base_TestSuite;
        $suite->addTestSuite( 'classes_validator_fileupload_noFile' );
        $suite->addTestSuite( 'classes_validator_fileupload_emptyFile' );
        $suite->addTestSuite( 'classes_validator_fileupload_withFile' );
        return $suite;
    }

}

/**
 * unit tests
 */
class classes_validator_fileupload_noFile extends PHPUnit_Framework_TestCase
{

    public function testInvalidFieldName ()
    {
        $valid = new \h2o\Validator\FileUpload;

        try {
            $valid->validate("1234");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\Argument $err ) {
            $this->assertSame( "Must be a valid PHP variable name", $err->getMessage() );
        }
    }

    public function testNoUploads()
    {
        $valid = $this->getMock("h2o\Validator\FileUpload", array("getUploadedFiles"));

        $result = $valid->validate("fld");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("No file was uploaded"),
                $result->getErrors()
            );
    }

    public function testUploadErrors_iniSize ()
    {
        $valid = $this->getMock("h2o\Validator\FileUpload", array("getUploadedFiles"));
        $valid->expects( $this->once() )
            ->method("getUploadedFiles")
            ->will( $this->returnValue(array("fld" => array( "error" => UPLOAD_ERR_INI_SIZE ))) );

        $result = $valid->validate("fld");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("File exceeds the server's maximum allowed size"),
                $result->getErrors()
            );
    }

    public function testUploadErrors_formSize ()
    {
        $valid = $this->getMock("h2o\Validator\FileUpload", array("getUploadedFiles"));
        $valid->expects( $this->once() )
            ->method("getUploadedFiles")
            ->will( $this->returnValue(array("fld" => array( "error" => UPLOAD_ERR_FORM_SIZE ))) );

        $result = $valid->validate("fld");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("File exceeds the maximum allowed size"),
                $result->getErrors()
            );
    }

    public function testUploadErrors_partial ()
    {
        $valid = $this->getMock("h2o\Validator\FileUpload", array("getUploadedFiles"));
        $valid->expects( $this->once() )
            ->method("getUploadedFiles")
            ->will( $this->returnValue(array("fld" => array( "error" => UPLOAD_ERR_PARTIAL ))) );

        $result = $valid->validate("fld");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("File was only partially uploaded"),
                $result->getErrors()
            );
    }

    public function testUploadErrors_noFile ()
    {
        $valid = $this->getMock("h2o\Validator\FileUpload", array("getUploadedFiles"));
        $valid->expects( $this->once() )
            ->method("getUploadedFiles")
            ->will( $this->returnValue(array("fld" => array( "error" => UPLOAD_ERR_NO_FILE ))) );

        $result = $valid->validate("fld");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("No file was uploaded"),
                $result->getErrors()
            );
    }

    public function testUploadErrors_noTmp ()
    {
        $valid = $this->getMock("h2o\Validator\FileUpload", array("getUploadedFiles"));
        $valid->expects( $this->once() )
            ->method("getUploadedFiles")
            ->will( $this->returnValue(array("fld" => array( "error" => UPLOAD_ERR_NO_TMP_DIR ))) );

        $result = $valid->validate("fld");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("No temporary directory was defined on the server"),
                $result->getErrors()
            );
    }

    public function testUploadErrors_cantWrite ()
    {
        $valid = $this->getMock("h2o\Validator\FileUpload", array("getUploadedFiles"));
        $valid->expects( $this->once() )
            ->method("getUploadedFiles")
            ->will( $this->returnValue(array("fld" => array( "error" => UPLOAD_ERR_CANT_WRITE ))) );

        $result = $valid->validate("fld");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Unable to write the uploaded file to the server"),
                $result->getErrors()
            );
    }

    public function testUploadErrors_extension ()
    {
        $valid = $this->getMock("h2o\Validator\FileUpload", array("getUploadedFiles"));
        $valid->expects( $this->once() )
            ->method("getUploadedFiles")
            ->will( $this->returnValue(array("fld" => array( "error" => UPLOAD_ERR_EXTENSION ))) );

        $result = $valid->validate("fld");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("A PHP extension has restricted this upload"),
                $result->getErrors()
            );
    }

    public function testUploadErrors_other ()
    {
        $valid = $this->getMock("h2o\Validator\FileUpload", array("getUploadedFiles"));
        $valid->expects( $this->once() )
            ->method("getUploadedFiles")
            ->will( $this->returnValue(array("fld" => array( "error" => 9999 ))) );

        $result = $valid->validate("fld");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("An unknown error occured"),
                $result->getErrors()
            );
    }

    public function testRestrictedFile ()
    {
        $valid = $this->getMock("h2o\Validator\FileUpload", array("getUploadedFiles"));
        $valid->expects( $this->once() )
            ->method("getUploadedFiles")
            ->will( $this->returnValue(array("fld" => array(
                    "error" => 0,
                    "tmp_name" => __FILE__
                ))) );

        $result = $valid->validate("fld");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("File is restricted"),
                $result->getErrors()
            );
    }

}

class classes_validator_fileupload_emptyFile extends PHPUnit_EmptyFile_Framework_TestCase
{

    public function testEmptyFile ()
    {
        $valid = $this->getMock("h2o\Validator\FileUpload", array("getUploadedFiles", "isUploadedFile"));

        $valid->expects( $this->once() )
            ->method("isUploadedFile")
            ->will( $this->returnValue( TRUE ) );

        $valid->expects( $this->once() )
            ->method("getUploadedFiles")
            ->will( $this->returnValue(array("fld" => array(
                    "error" => 0,
                    "tmp_name" => $this->file
                ))) );

        $result = $valid->validate("fld");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Uploaded file is empty"),
                $result->getErrors()
            );
    }

}

class classes_validator_fileupload_withFile extends PHPUnit_TestFile_Framework_TestCase
{

    public function testUnreadable ()
    {
        chmod($this->file, 0200);

        $valid = $this->getMock("h2o\Validator\FileUpload", array("getUploadedFiles", "isUploadedFile"));

        $valid->expects( $this->once() )
            ->method("isUploadedFile")
            ->will( $this->returnValue( TRUE ) );

        $valid->expects( $this->once() )
            ->method("getUploadedFiles")
            ->will( $this->returnValue(array("fld" => array(
                    "error" => 0,
                    "tmp_name" => $this->file
                ))) );

        $result = $valid->validate("fld");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Uploaded file is not readable"),
                $result->getErrors()
            );
    }

    public function testValid()
    {
        $valid = $this->getMock("h2o\Validator\FileUpload", array("getUploadedFiles", "isUploadedFile"));

        $valid->expects( $this->once() )
            ->method("isUploadedFile")
            ->will( $this->returnValue( TRUE ) );

        $valid->expects( $this->once() )
            ->method("getUploadedFiles")
            ->will( $this->returnValue(array("fld" => array(
                    "error" => 0,
                    "tmp_name" => $this->file
                ))) );

        $this->assertTrue( $valid->isValid("fld") );
    }

}

?>