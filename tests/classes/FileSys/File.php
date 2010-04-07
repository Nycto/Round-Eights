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
 * Unit test for running both file test suites
 */
class classes_filesys_file
{

    public static function suite()
    {
        $suite = new \r8\Test\Suite;
        $suite->addTestSuite( 'classes_filesystem_file_noFile' );
        $suite->addTestSuite( 'classes_filesystem_file_withFile' );
        return $suite;
    }

}

/**
 * unit tests that don't require a temporary file
 */
class classes_filesystem_file_noFile extends PHPUnit_Framework_TestCase
{

    public function testSetPath ()
    {
        $file = new \r8\FileSys\File;

        $this->assertSame( $file, $file->setPath("/dir/to/example.php") );
        $this->assertSame( "/dir/to/", $file->getRawDir() );
        $this->assertSame( "example", $file->getFilename() );
        $this->assertSame( "php", $file->getExt() );

        $this->assertSame( $file, $file->setPath("/dir/to/example.php.BAK") );
        $this->assertSame( "/dir/to/", $file->getRawDir() );
        $this->assertSame( "example.php", $file->getFilename() );
        $this->assertSame( "BAK", $file->getExt() );

        $this->assertSame( $file, $file->setPath("dir/to/example") );
        $this->assertSame( "dir/to/", $file->getRawDir() );
        $this->assertSame( "example", $file->getFilename() );
        $this->assertNull( $file->getExt() );

        $this->assertSame( $file, $file->setPath("example.php") );
        $this->assertSame( "./", $file->getRawDir() );
        $this->assertSame( "example", $file->getFilename() );
        $this->assertSame( "php", $file->getExt() );

        $this->assertSame( $file, $file->setPath("example") );
        $this->assertSame( "./", $file->getRawDir() );
        $this->assertSame( "example", $file->getFilename() );
        $this->assertNull( $file->getExt() );

        $this->assertSame( $file, $file->setPath("") );
        $this->assertNull( $file->getRawDir() );
        $this->assertNull( $file->getFilename() );
        $this->assertNull( $file->getExt() );

        $this->assertSame( $file, $file->setPath("/.ignore") );
        $this->assertSame( "/", $file->getRawDir() );
        $this->assertSame( ".ignore", $file->getFilename() );
        $this->assertNull( $file->getExt() );
    }

    public function testGetPath ()
    {
        $file = new \r8\FileSys\File;

        $this->assertNull( $file->getPath() );

        $file->setDir("dir/to");
        $this->assertSame( "dir/to/", $file->getPath() );

        $file->setDir("/dir/to/");
        $this->assertSame( "/dir/to/", $file->getPath() );

        $file->setExt("php");
        $this->assertSame( "/dir/to/", $file->getPath() );

        $file->setFilename("Example");
        $this->assertSame( "/dir/to/Example.php", $file->getPath() );

        $file->clearExt();
        $this->assertSame( "/dir/to/Example", $file->getPath() );

        $file->clearDir();
        $this->assertSame( "Example", $file->getPath() );

        $file->setExt("php");
        $this->assertSame( "Example.php", $file->getPath() );

        $file->clearFilename()->clearExt();
        $this->assertNull( $file->getPath() );
    }

    public function testGetDir ()
    {
        $file = new \r8\FileSys\File;

        $dir = $file->getDir();
        $this->assertThat( $dir, $this->isInstanceOf('\r8\FileSys\Dir') );
        $this->assertNull( $dir->getRawDir() );

        $file->setPath("/dir/to/file.php");
        $dir = $file->getDir();
        $this->assertThat( $dir, $this->isInstanceOf('\r8\FileSys\Dir') );
        $this->assertSame( "/dir/to/", $dir->getRawDir() );
    }

    public function testExtAccessors ()
    {
        $file = new \r8\FileSys\File;

        $this->assertNull( $file->getExt() );
        $this->assertFalse( $file->extExists() );

        $this->assertSame( $file, $file->setExt(".ext") );
        $this->assertSame( "ext", $file->getExt() );
        $this->assertTrue( $file->extExists() );

        $this->assertSame( $file, $file->setExt(".") );
        $this->assertNull( $file->getExt() );
        $this->assertFalse( $file->extExists() );

        $this->assertSame( $file, $file->setExt("") );
        $this->assertNull( $file->getExt() );
        $this->assertFalse( $file->extExists() );

        $this->assertSame( $file, $file->setExt("php.BAK") );
        $this->assertSame( "php.BAK", $file->getExt() );
        $this->assertTrue( $file->extExists() );

        $this->assertSame( $file, $file->clearExt() );
        $this->assertNull( $file->getExt() );
        $this->assertFalse( $file->extExists() );
    }

    public function testFilenameAccessors ()
    {
        $file = new \r8\FileSys\File;

        $this->assertNull( $file->getFilename() );
        $this->assertFalse( $file->filenameExists() );

        $this->assertSame( $file, $file->setFilename("filename.") );
        $this->assertSame( "filename", $file->getFilename() );
        $this->assertTrue( $file->filenameExists() );

        $this->assertSame( $file, $file->setFilename(".") );
        $this->assertNull( $file->getFilename() );
        $this->assertFalse( $file->filenameExists() );

        $this->assertSame( $file, $file->setFilename("") );
        $this->assertNull( $file->getFilename() );
        $this->assertFalse( $file->filenameExists() );

        $this->assertSame( $file, $file->setFilename("Filename.2008") );
        $this->assertSame( "Filename.2008", $file->getFilename() );
        $this->assertTrue( $file->filenameExists() );

        $this->assertSame( $file, $file->clearFilename() );
        $this->assertNull( $file->getFilename() );
        $this->assertFalse( $file->filenameExists() );
    }

    public function testSetBasename ()
    {
        $file = new \r8\FileSys\File;

        $this->assertSame( $file, $file->setBasename("example.php") );
        $this->assertSame( "example", $file->getFilename() );
        $this->assertSame( "php", $file->getExt() );

        $this->assertSame( $file, $file->setBasename("example") );
        $this->assertSame( "example", $file->getFilename() );
        $this->assertNull( $file->getExt() );

        $this->assertSame( $file, $file->setBasename(".htaccess") );
        $this->assertSame( ".htaccess", $file->getFilename() );
        $this->assertNull( $file->getExt() );

        $this->assertSame( $file, $file->setBasename(".htaccess.2008.bak") );
        $this->assertSame( ".htaccess.2008", $file->getFilename() );
        $this->assertSame( "bak", $file->getExt() );

        $this->assertSame( $file, $file->setBasename(".") );
        $this->assertNull( $file->getFilename() );
        $this->assertNull( $file->getExt() );

        $this->assertSame( $file, $file->setBasename("dir/to/example.php") );
        $this->assertSame( "example", $file->getFilename() );
        $this->assertSame( "php", $file->getExt() );

        $this->assertSame( $file, $file->setBasename("") );
        $this->assertNull( $file->getFilename() );
        $this->assertNull( $file->getExt() );
    }

    public function testGetBasename ()
    {
        $file = new \r8\FileSys\File;
        $this->assertNull( $file->getBasename() );

        $file->setExt("php");
        $this->assertNull( $file->getBasename() );

        $file->setFilename("example");
        $this->assertSame( "example.php", $file->getBasename() );

        $file->clearExt();
        $this->assertSame( "example", $file->getBasename() );

        $file->clearFilename();
        $this->assertNull( $file->getBasename() );
    }

}

/**
 * Unit Tests that use a temporary file
 */
class classes_filesystem_file_withFile extends \r8\Test\TestCase\File
{

    public function testExists ()
    {
        $file = new \r8\FileSys\File( $this->file );
        $this->assertTrue( $file->exists() );

        $file = new \r8\FileSys\File( __DIR__ );
        $this->assertFalse( $file->exists() );

        $file = new \r8\FileSys\File( "/path/to/missing/file" );
        $this->assertFalse( $file->exists() );
    }

    public function testGet ()
    {
        $file = new \r8\FileSys\File( $this->file );
        $this->assertSame(
                "This is a string\n"
                ."of data that is put\n"
                ."in the test file",
                $file->get()
            );


        chmod( $this->file, 0000 );
        $file = new \r8\FileSys\File( $this->file );
        try {
            $file->get();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\FileSystem $err ) {
            $this->assertSame( "Unable read data from file", $err->getMessage() );
        }


        $file = new \r8\FileSys\File( "/path/to/missing/file" );
        try {
            $file->get();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\FileSystem\Missing $err ) {
            $this->assertSame( "Path does not exist", $err->getMessage() );
        }
    }

    public function testSet ()
    {
        $file = new \r8\FileSys\File( $this->file );
        $this->assertSame( $file, $file->set("This is a new chunk of info") );
        $this->assertSame(
                "This is a new chunk of info",
                $file->get()
            );

        chmod( $this->file, 0400 );

        try {
            $file->set( "data" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\FileSystem $err ) {
            $this->assertSame( "Unable write data to file", $err->getMessage() );
        }
    }

    public function testAppend ()
    {
        $file = new \r8\FileSys\File( $this->file );
        $this->assertSame( $file, $file->append("\nnew data") );
        $this->assertSame(
                "This is a string\n"
                ."of data that is put\n"
                ."in the test file\n"
                ."new data",
                $file->get()
            );

        $this->assertSame( $file, $file->append("\nAnother snippet") );
        $this->assertSame(
                "This is a string\n"
                ."of data that is put\n"
                ."in the test file\n"
                ."new data\n"
                ."Another snippet",
                $file->get()
            );

        chmod( $this->file, 0400 );

        try {
            $file->append( "data" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\FileSystem $err ) {
            $this->assertSame( "Unable write data to file", $err->getMessage() );
        }
    }

    public function testToArray ()
    {
        $file = new \r8\FileSys\File( $this->file );
        $this->assertSame(
                array(
                        "This is a string\n",
                        "of data that is put\n",
                        "in the test file"
                    ),
                $file->toArray()
            );

        chmod( $this->file, 0000 );
        $file = new \r8\FileSys\File( $this->file );
        try {
            $file->toArray();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\FileSystem $err ) {
            $this->assertSame( "Unable to read data from file", $err->getMessage() );
        }


        $file = new \r8\FileSys\File( "/path/to/missing/file" );
        try {
            $file->toArray();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\FileSystem\Missing $err ) {
            $this->assertSame( "Path does not exist", $err->getMessage() );
        }
    }

    public function testGetSize ()
    {
        $file = new \r8\FileSys\File( $this->file );
        $this->assertType( 'integer', $file->getSize() );
        $this->assertGreaterThan( 0, $file->getSize() );


        $file = new \r8\FileSys\File( "/path/to/missing/file" );
        try {
            $file->getSize();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\FileSystem\Missing $err ) {
            $this->assertSame( "Path does not exist", $err->getMessage() );
        }
    }

    public function testTruncate ()
    {
        $file = new \r8\FileSys\File( $this->file );
        $this->assertSame( $file, $file->truncate() );
        $this->assertSame( "", file_get_contents($this->file) );
    }

    public function testDelete ()
    {
        $file = new \r8\FileSys\File( $this->file );

        $this->assertSame( $file, $file->delete() );

        if ( file_exists($this->file) )
            $this->fail("File deletion failed");

        $this->assertSame( $file, $file->delete() );
    }

    public function testGetMimeType ()
    {
        $file = new \r8\FileSys\File;

        try {
            $file->getMimeType();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\FileSystem\Missing $err ) {
            $this->assertSame( "Path does not exist", $err->getMessage() );
        }

        $file->setPath( $this->file );

        $this->assertSame( "text/plain", $file->getMimeType() );

        // Copy the contents of a gif in to the file
        file_put_contents(
                $this->file,
                base64_decode("R0lGODdhAQABAIAAAP///////ywAAAAAAQABAAACAkQBADs="),
                FILE_BINARY
            );

        $this->assertSame( "image/gif", $file->getMimeType() );
    }

    public function testCopy ()
    {
        $file = new \r8\FileSys\File( $this->file );
        $newPath = $this->getTempFileName();

        $copied = $file->copy( $newPath );

        $this->assertThat( $copied, $this->isInstanceOf("r8\FileSys\File") );
        $this->assertNotSame( $file, $copied );

        $this->assertSame( $newPath, $copied->getPath() );

        $this->assertTrue( is_file( $this->file ) );
        $this->assertTrue( is_file( $newPath ) );

        $this->assertSame(
                "This is a string\nof data that is put\nin the test file",
                file_get_contents( $this->file )
            );

        $this->assertSame(
                "This is a string\nof data that is put\nin the test file",
                file_get_contents( $newPath )
            );
    }

    public function testCopy_missing ()
    {

        $file = new \r8\FileSys\File( "/path/to/missing/file" );
        try {
            $file->copy( "/some/new/location" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\FileSystem\Missing $err ) {
            $this->assertSame( "Path does not exist", $err->getMessage() );
        }
    }

    public function testCopy_noPerms ()
    {
        $file = new \r8\FileSys\File( $this->file );

        chmod( $this->file, 0000 );

        try {
            $file->copy( $this->getTempFileName() );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\FileSystem $err ) {
            $this->assertSame( "Unable to copy file", $err->getMessage() );
        }

    }

    public function testMove ()
    {
        $file = new \r8\FileSys\File( $this->file );
        $newPath = $this->getTempFileName();

        $this->assertSame( $file, $file->move( $newPath ) );

        $this->assertSame( $newPath, $file->getPath() );

        $this->assertFalse( is_file( $this->file ) );
        $this->assertTrue( is_file( $newPath ) );

        $this->assertSame(
                "This is a string\nof data that is put\nin the test file",
                file_get_contents( $newPath )
            );
    }

    public function testMove_missing ()
    {
        $file = new \r8\FileSys\File( "/path/to/missing/file" );
        try {
            $file->move( $this->getTempFileName() );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\FileSystem\Missing $err ) {
            $this->assertSame( "Path does not exist", $err->getMessage() );
        }
    }

}

?>