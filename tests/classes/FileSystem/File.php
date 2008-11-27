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
 * Unit test for running both file test suites
 */
class classes_filesystem_file
{

    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite;
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
        $file = new ::cPHP::FileSystem::File;

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
    }

    public function testGetPath ()
    {
        $file = new ::cPHP::FileSystem::File;

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

    public function testExtAccessors ()
    {
        $file = new ::cPHP::FileSystem::File;

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
        $file = new ::cPHP::FileSystem::File;

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
        $file = new ::cPHP::FileSystem::File;

        $this->assertSame( $file, $file->setBasename("example.php") );
        $this->assertSame( "example", $file->getFilename() );
        $this->assertSame( "php", $file->getExt() );

        $this->assertSame( $file, $file->setBasename("example") );
        $this->assertSame( "example", $file->getFilename() );
        $this->assertNull( $file->getExt() );

        $this->assertSame( $file, $file->setBasename(".php") );
        $this->assertNull( $file->getFilename() );
        $this->assertSame( "php", $file->getExt() );

        $this->assertSame( $file, $file->setBasename("dir/to/example.php") );
        $this->assertSame( "example", $file->getFilename() );
        $this->assertSame( "php", $file->getExt() );

        $this->assertSame( $file, $file->setBasename("") );
        $this->assertNull( $file->getFilename() );
        $this->assertNull( $file->getExt() );
    }

    public function testGetBasename ()
    {
        $file = new ::cPHP::FileSystem::File;
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
class classes_filesystem_file_withFile extends PHPUnit_TestFile_Framework_TestCase
{

    public function testExists ()
    {
        $file = new ::cPHP::FileSystem::File( $this->file );
        $this->assertTrue( $file->exists() );

        $file = new ::cPHP::FileSystem::File( __DIR__ );
        $this->assertFalse( $file->exists() );

        $file = new ::cPHP::FileSystem::File( "/path/to/missing/file" );
        $this->assertFalse( $file->exists() );
    }

    public function testGet ()
    {
        $file = new ::cPHP::FileSystem::File( $this->file );
        $this->assertSame(
                "This is a string\n"
                ."of data that is put\n"
                ."in the test file",
                $file->get()
            );


        chmod( $this->file, 0000 );
        $file = new ::cPHP::FileSystem::File( $this->file );
        try {
            $file->get();
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::FileSystem $err ) {
            $this->assertSame( "Unable read data from file", $err->getMessage() );
        }


        $file = new ::cPHP::FileSystem::File( "/path/to/missing/file" );
        try {
            $file->get();
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::FileSystem::Missing $err ) {
            $this->assertSame( "Path does not exist", $err->getMessage() );
        }
    }

    public function testSet ()
    {
        $file = new ::cPHP::FileSystem::File( $this->file );
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
        catch ( ::cPHP::Exception::FileSystem $err ) {
            $this->assertSame( "Unable write data to file", $err->getMessage() );
        }
    }

    public function testAppend ()
    {
        $file = new ::cPHP::FileSystem::File( $this->file );
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
        catch ( ::cPHP::Exception::FileSystem $err ) {
            $this->assertSame( "Unable write data to file", $err->getMessage() );
        }
    }

    public function testToArray ()
    {
        $file = new ::cPHP::FileSystem::File( $this->file );
        $this->assertSame(
                array(
                        "This is a string\n",
                        "of data that is put\n",
                        "in the test file"
                    ),
                $file->toArray()
            );

        chmod( $this->file, 0000 );
        $file = new ::cPHP::FileSystem::File( $this->file );
        try {
            $file->toArray();
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::FileSystem $err ) {
            $this->assertSame( "Unable to read data from file", $err->getMessage() );
        }


        $file = new ::cPHP::FileSystem::File( "/path/to/missing/file" );
        try {
            $file->toArray();
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::FileSystem::Missing $err ) {
            $this->assertSame( "Path does not exist", $err->getMessage() );
        }
    }

    public function testGetSize ()
    {
        $file = new ::cPHP::FileSystem::File( $this->file );
        $this->assertType( 'integer', $file->getSize() );
        $this->assertGreaterThan( 0, $file->getSize() );


        $file = new ::cPHP::FileSystem::File( "/path/to/missing/file" );
        try {
            $file->getSize();
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::FileSystem::Missing $err ) {
            $this->assertSame( "Path does not exist", $err->getMessage() );
        }
    }

    public function testTruncate ()
    {
        $file = new ::cPHP::FileSystem::File( $this->file );
        $this->assertSame( $file, $file->truncate() );
        $this->assertSame( "", file_get_contents($this->file) );
    }

    public function testDelete ()
    {
        $file = new ::cPHP::FileSystem::File( $this->file );

        $this->assertSame( $file, $file->delete() );

        if ( file_exists($this->file) )
            $this->fail("File deletion failed");

        $this->assertSame( $file, $file->delete() );
    }

    public function testGetMimeType ()
    {
        $file = new ::cPHP::FileSystem::File;

        try {
            $file->getMimeType();
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::FileSystem::Missing $err ) {
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
        $file = new ::cPHP::FileSystem::File( $this->file );
        $newPath = $this->getTempFileName();

        $copied = $file->copy( $newPath );

        $this->assertThat( $copied, $this->isInstanceOf("cPHP::FileSystem::File") );
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

        $file = new ::cPHP::FileSystem::File( "/path/to/missing/file" );
        try {
            $file->copy( "/some/new/location" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::FileSystem::Missing $err ) {
            $this->assertSame( "Path does not exist", $err->getMessage() );
        }
    }

    public function testCopy_noPerms ()
    {
        $file = new ::cPHP::FileSystem::File( $this->file );

        chmod( $this->file, 0000 );

        try {
            $file->copy( $this->getTempFileName() );
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::FileSystem $err ) {
            $this->assertSame( "Unable to copy file", $err->getMessage() );
        }

    }

    public function testMove ()
    {
        $file = new ::cPHP::FileSystem::File( $this->file );
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
        $file = new ::cPHP::FileSystem::File( "/path/to/missing/file" );
        try {
            $file->move( $this->getTempFileName() );
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::FileSystem::Missing $err ) {
            $this->assertSame( "Path does not exist", $err->getMessage() );
        }
    }

}

?>