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
class classes_Input_File extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a test upload with errors
     *
     * @return \r8\Input\File
     */
    public function getTestFile ( $code, $isUploaded, $readable, $size )
    {
        $temp = $this->getMock('\r8\FileSys\File');
        $temp->expects( $this->any() )->method( "requirePath" );
        $temp->expects( $this->any() )
            ->method( "getSize" )
            ->will( $this->returnValue($size) );
        $temp->expects( $this->any() )
            ->method( "isReadable" )
            ->will( $this->returnValue($readable) );

        $file = $this->getMock(
        	'\r8\Input\File',
            array( "isUploadedFile" ),
            array( "FileName", $code, $temp )
        );
        $file->expects( $this->any() )
            ->method( "isUploadedFile" )
            ->will( $this->returnValue($isUploaded) );

        return $file;
    }

    public function testFromArray_flat ()
    {
        $result = \r8\Input\File::fromArray( array(
            'name' => 'File Name',
        	'tmp_name' => __FILE__,
            'error' => 1234
        ) );

        $this->assertThat( $result, $this->isInstanceOf('\r8\Input\File') );

        $this->assertSame( "File Name", $result->getName() );
        $this->assertSame( 1234, $result->getCode() );
        $this->assertEquals(
            new \r8\FileSys\File( __FILE__ ),
            $result->getFile()
        );
    }

    public function testFromArray_Multiple ()
    {
        $result = \r8\Input\File::fromArray( array(
            'name' => array( 'File Name', 'Mismatch', "k" => 'File 2' ),
        	'tmp_name' => array( __FILE__, "k" => r8_DIR_CLASSES ."Autoload.php" ),
            'error' => array( 1234, "k" => 0 )
        ) );

        $this->assertType('array', $result);
        $this->assertSame( 2, count($result) );

        $this->assertArrayHasKey( 0, $result );
        $this->assertArrayHasKey( "k", $result );

        $this->assertThat( $result[0], $this->isInstanceOf('\r8\Input\File') );
        $this->assertThat( $result["k"], $this->isInstanceOf('\r8\Input\File') );

        $this->assertSame( "File Name", $result[0]->getName() );
        $this->assertSame( 1234, $result[0]->getCode() );
        $this->assertEquals(
            new \r8\FileSys\File( __FILE__ ),
            $result[0]->getFile()
        );

        $this->assertSame( "File 2", $result["k"]->getName() );
        $this->assertSame( 0, $result["k"]->getCode() );
        $this->assertEquals(
            new \r8\FileSys\File( r8_DIR_CLASSES ."Autoload.php" ),
            $result["k"]->getFile()
        );
    }

    public function testFromArray_InvalidArray ()
    {
        try {
            \r8\Input\File::fromArray( array() );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "The following indexes are required and missing: name, tmp_name, error", $err->getMessage() );
        }

        try {
            \r8\Input\File::fromArray( array(
                'name' => 'File Name',
            	'tmp_name' => '/tmp/example.txt',
            ) );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "The following indexes are required and missing: error", $err->getMessage() );
        }

        try {
            \r8\Input\File::fromArray( array(
                'name' => 'File Name',
            ) );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "The following indexes are required and missing: tmp_name, error", $err->getMessage() );
        }
    }

    public function testConstruct ()
    {
        $temp = $this->getMock('\r8\FileSys\File');
        $temp->expects( $this->once() )->method( "requirePath" );

        $file = new \r8\Input\File( "FileName", 1234, $temp );

        $this->assertSame( "FileName", $file->getName() );
        $this->assertSame( 1234, $file->getCode() );
        $this->assertSame( $temp, $file->getFile() );
    }

    public function testConstruct_EmptyName ()
    {
        $temp = $this->getMock('\r8\FileSys\File');
        $temp->expects( $this->once() )->method( "requirePath" );

        try {
            new \r8\Input\File( "   ", 1234, $temp );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }
    }

    public function testGetSize ()
    {
        $temp = $this->getMock('\r8\FileSys\File');
        $temp->expects( $this->once() )->method( "requirePath" );
        $temp->expects( $this->once() )
            ->method( 'getSize' )
            ->will( $this->returnValue(1234567) );

        $file = new \r8\Input\File( "FileName", 1234, $temp );

        $this->assertSame( 1234567, $file->getSize() );
        $this->assertSame( 1234567, $file->getSize() );
        $this->assertSame( 1234567, $file->getSize() );
    }

    public function testGetMimeType ()
    {
        $temp = $this->getMock('\r8\FileSys\File');
        $temp->expects( $this->once() )->method( "requirePath" );
        $temp->expects( $this->once() )
            ->method( 'getMimeType' )
            ->will( $this->returnValue("text/plain") );

        $file = new \r8\Input\File( "FileName", 1234, $temp );

        $this->assertSame( "text/plain", $file->getMimeType() );
        $this->assertSame( "text/plain", $file->getMimeType() );
        $this->assertSame( "text/plain", $file->getMimeType() );
    }

    public function testIsUploadedFile ()
    {
        $temp = $this->getMock('\r8\FileSys\File');
        $temp->expects( $this->once() )->method( "requirePath" );
        $temp->expects( $this->once() )
            ->method( 'getPath' )
            ->will( $this->returnValue( __FILE__ ) );

        $file = new \r8\Input\File( "FileName", 1234, $temp );

        $this->assertFalse( $file->isUploadedFile() );
    }

    public function testIsReadable ()
    {
        $temp = $this->getMock('\r8\FileSys\File');
        $temp->expects( $this->once() )->method( "requirePath" );
        $temp->expects( $this->once() )
            ->method( 'isReadable' )
            ->will( $this->returnValue( TRUE ) );

        $file = new \r8\Input\File( "FileName", 1234, $temp );

        $this->assertTrue( $file->isReadable() );
    }

    public function testIsValid_ErrorCode ()
    {
        $file = $this->getTestFile( 100, TRUE, TRUE, 100 );
        $this->assertFalse( $file->isValid() );
    }

    public function testIsValid_NotUploaded ()
    {
        $file = $this->getTestFile( UPLOAD_ERR_OK, FALSE, TRUE, 100 );
        $this->assertFalse( $file->isValid() );
    }

    public function testIsValid_NotReadable ()
    {
        $file = $this->getTestFile( UPLOAD_ERR_OK, TRUE, FALSE, 100 );
        $this->assertFalse( $file->isValid() );
    }

    public function testIsValid_EmptyFile ()
    {
        $file = $this->getTestFile( UPLOAD_ERR_OK, TRUE, TRUE, 0 );
        $this->assertFalse( $file->isValid() );
    }

    public function testIsValid_Valid ()
    {
        $file = $this->getTestFile( UPLOAD_ERR_OK, TRUE, TRUE, 100 );
        $this->assertTrue( $file->isValid() );
    }

    public function testGetMessage_ErrorCode ()
    {
        $file = $this->getTestFile( UPLOAD_ERR_NO_FILE, TRUE, TRUE, 100 );
        $this->assertSame(
            "No file was uploaded",
            $file->getMessage()
        );
    }

    public function testGetMessage_UnknownCode ()
    {
        $file = $this->getTestFile( 505050, TRUE, TRUE, 100 );
        $this->assertSame(
            "An unknown error occurred",
            $file->getMessage()
        );
    }

    public function testGetMessage_NotUploaded ()
    {
        $file = $this->getTestFile( UPLOAD_ERR_OK, FALSE, TRUE, 100 );
        $this->assertSame(
            "Upload validation failed",
            $file->getMessage()
        );
    }

    public function testGetMessage_NotReadable ()
    {
        $file = $this->getTestFile( UPLOAD_ERR_OK, TRUE, FALSE, 100 );
        $this->assertSame(
            "Uploaded file is not readable",
            $file->getMessage()
        );
    }

    public function testGetMessage_Empty ()
    {
        $file = $this->getTestFile( UPLOAD_ERR_OK, TRUE, TRUE, 0 );
        $this->assertSame(
            "Uploaded file is empty",
            $file->getMessage()
        );
    }

    public function testGetMessage_Valid ()
    {
        $file = $this->getTestFile( UPLOAD_ERR_OK, TRUE, TRUE, 199 );
        $this->assertNull( $file->getMessage() );
    }

}

?>