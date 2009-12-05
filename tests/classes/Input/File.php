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

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_Input_File extends PHPUnit_Framework_TestCase
{

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

}

?>