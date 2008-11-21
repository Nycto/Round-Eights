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

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * Unit test for running both filesystem test suites
 */
class classes_form_field_file
{

    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite;
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
        $field = $this->getMock("cPHP::Form::Field::File", array("getUploadedFiles"), array("fld"));

        $field->expects( $this->once() )
            ->method("getUploadedFiles")
            ->will( $this->returnValue(array()) );

        $this->assertNull($field->getRawValue());
    }

    public function testGetRawValue_withFile ()
    {
        $field = $this->getMock("cPHP::Form::Field::File", array("getUploadedFiles"), array("fld"));

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
        $valid = $this->getMock("cPHP::Validator::FileUpload", array("getUploadedFiles", "isUploadedFile"));

        $valid->expects( $this->once() )
            ->method("getUploadedFiles")
            ->will( $this->returnValue(array("fld" => array(
                    "error" => UPLOAD_ERR_INI_SIZE,
                    "tmp_name" => "/dir/to/file.txt"
                ))) );


        // Set up file upload field to use the mock FileUpload validator
        $field = $this->getMock("cPHP::Form::Field::File", array("getFileUploadValidator"), array("fld"));

        $field->expects( $this->once() )
            ->method("getFileUploadValidator")
            ->will( $this->returnValue( $valid ) );


        // Run the simulation
        $result = $field->validate();
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result"));
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("File exceeds the server's maximum allowed size"),
                $result->getErrors()->get()
            );

    }

    public function testGetTag ()
    {
        $field = new ::cPHP::Form::Field::File("fld");
        $field->setName("fldName");

        $tag = $field->getTag();

        $this->assertThat( $tag, $this->isInstanceOf("cPHP::Tag") );
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


        // Set up file upload field to use the mock validator and mock $_FILES
        $field = $this->getMock("cPHP::Form::Field::File", array("getUploadedFiles", "getFileUploadValidator"), array("fld"));

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


        // This result will be returned by the second validator
        $result = new ::cPHP::Validator::Result( $this->file );

        // Set up another validator that should receive the filename
        $otherValid = $this->getMock("cPHP::iface::Validator", array("isValid", "validate"));
        $otherValid->expects( $this->once() )
            ->method("validate")
            ->with( $this->equalTo($this->file) )
            ->will( $this->returnValue( $result ) );


        // Set up the mock field to use the FileUpload validator and mock $_FILES
        $field = $this->getMock(
                "cPHP::Form::Field::File",
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