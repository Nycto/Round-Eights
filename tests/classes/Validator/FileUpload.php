<?php
/**
 * Unit Test File
 *
 * PHP version 5.3
 *
 * This source file is subject to version 2.0 of the Artistic License. A copy
 * of the license should have been bundled with this source file inside a file
 * named LICENSE.txt. It is also available through the world-wide-web at one
 * of the following URIs:
 * http://www.commonphp.com/license.php
 * http://www.opensource.org/licenses/artistic-license-2.0.php
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @author James Frasca <james@commonphp.com>
 * @license Artistic License 2.0 http://www.commonphp.com/license.php
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
        $suite = new cPHP_Base_TestSuite;
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
        $valid = new ::cPHP::Validator::FileUpload;

        try {
            $valid->validate("1234");
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::Argument $err ) {
            $this->assertSame( "Must be a valid PHP variable name", $err->getMessage() );
        }
    }

    public function testNoUploads()
    {
        $valid = $this->getMock("cPHP::Validator::FileUpload", array("getUploadedFiles"));

        $result = $valid->validate("fld");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("No file was uploaded"),
                $result->getErrors()->get()
            );
    }

    public function testUploadErrors_iniSize ()
    {
        $valid = $this->getMock("cPHP::Validator::FileUpload", array("getUploadedFiles"));
        $valid->expects( $this->once() )
            ->method("getUploadedFiles")
            ->will( $this->returnValue(array("fld" => array( "error" => UPLOAD_ERR_INI_SIZE ))) );

        $result = $valid->validate("fld");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("File exceeds the server's maximum allowed size"),
                $result->getErrors()->get()
            );
    }

    public function testUploadErrors_formSize ()
    {
        $valid = $this->getMock("cPHP::Validator::FileUpload", array("getUploadedFiles"));
        $valid->expects( $this->once() )
            ->method("getUploadedFiles")
            ->will( $this->returnValue(array("fld" => array( "error" => UPLOAD_ERR_FORM_SIZE ))) );

        $result = $valid->validate("fld");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("File exceeds the maximum allowed size"),
                $result->getErrors()->get()
            );
    }

    public function testUploadErrors_partial ()
    {
        $valid = $this->getMock("cPHP::Validator::FileUpload", array("getUploadedFiles"));
        $valid->expects( $this->once() )
            ->method("getUploadedFiles")
            ->will( $this->returnValue(array("fld" => array( "error" => UPLOAD_ERR_PARTIAL ))) );

        $result = $valid->validate("fld");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("File was only partially uploaded"),
                $result->getErrors()->get()
            );
    }

    public function testUploadErrors_noFile ()
    {
        $valid = $this->getMock("cPHP::Validator::FileUpload", array("getUploadedFiles"));
        $valid->expects( $this->once() )
            ->method("getUploadedFiles")
            ->will( $this->returnValue(array("fld" => array( "error" => UPLOAD_ERR_NO_FILE ))) );

        $result = $valid->validate("fld");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("No file was uploaded"),
                $result->getErrors()->get()
            );
    }

    public function testUploadErrors_noTmp ()
    {
        $valid = $this->getMock("cPHP::Validator::FileUpload", array("getUploadedFiles"));
        $valid->expects( $this->once() )
            ->method("getUploadedFiles")
            ->will( $this->returnValue(array("fld" => array( "error" => UPLOAD_ERR_NO_TMP_DIR ))) );

        $result = $valid->validate("fld");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("No temporary directory was defined on the server"),
                $result->getErrors()->get()
            );
    }

    public function testUploadErrors_cantWrite ()
    {
        $valid = $this->getMock("cPHP::Validator::FileUpload", array("getUploadedFiles"));
        $valid->expects( $this->once() )
            ->method("getUploadedFiles")
            ->will( $this->returnValue(array("fld" => array( "error" => UPLOAD_ERR_CANT_WRITE ))) );

        $result = $valid->validate("fld");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Unable to write the uploaded file to the server"),
                $result->getErrors()->get()
            );
    }

    public function testUploadErrors_extension ()
    {
        $valid = $this->getMock("cPHP::Validator::FileUpload", array("getUploadedFiles"));
        $valid->expects( $this->once() )
            ->method("getUploadedFiles")
            ->will( $this->returnValue(array("fld" => array( "error" => UPLOAD_ERR_EXTENSION ))) );

        $result = $valid->validate("fld");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("A PHP extension has restricted this upload"),
                $result->getErrors()->get()
            );
    }

    public function testUploadErrors_other ()
    {
        $valid = $this->getMock("cPHP::Validator::FileUpload", array("getUploadedFiles"));
        $valid->expects( $this->once() )
            ->method("getUploadedFiles")
            ->will( $this->returnValue(array("fld" => array( "error" => 9999 ))) );

        $result = $valid->validate("fld");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("An unknown error occured"),
                $result->getErrors()->get()
            );
    }

    public function testRestrictedFile ()
    {
        $valid = $this->getMock("cPHP::Validator::FileUpload", array("getUploadedFiles"));
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
                $result->getErrors()->get()
            );
    }

}

class classes_validator_fileupload_emptyFile extends PHPUnit_EmptyFile_Framework_TestCase
{

    public function testEmptyFile ()
    {
        $valid = $this->getMock("cPHP::Validator::FileUpload", array("getUploadedFiles", "isUploadedFile"));

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
                $result->getErrors()->get()
            );
    }

}

class classes_validator_fileupload_withFile extends PHPUnit_TestFile_Framework_TestCase
{

    public function testUnreadable ()
    {
        chmod($this->file, 0200);

        $valid = $this->getMock("cPHP::Validator::FileUpload", array("getUploadedFiles", "isUploadedFile"));

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
                $result->getErrors()->get()
            );
    }

    public function testValid()
    {
        $valid = $this->getMock("cPHP::Validator::FileUpload", array("getUploadedFiles", "isUploadedFile"));

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