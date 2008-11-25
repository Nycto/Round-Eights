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
        $mock = new ::cPHP::FileSystem::File;

        $this->assertSame( $mock, $mock->setPath("/dir/to/example.php") );
        $this->assertSame( "/dir/to/", $mock->getRawDir() );
        $this->assertSame( "example", $mock->getFilename() );
        $this->assertSame( "php", $mock->getExt() );

        $this->assertSame( $mock, $mock->setPath("/dir/to/example.php.BAK") );
        $this->assertSame( "/dir/to/", $mock->getRawDir() );
        $this->assertSame( "example.php", $mock->getFilename() );
        $this->assertSame( "BAK", $mock->getExt() );

        $this->assertSame( $mock, $mock->setPath("dir/to/example") );
        $this->assertSame( "dir/to/", $mock->getRawDir() );
        $this->assertSame( "example", $mock->getFilename() );
        $this->assertNull( $mock->getExt() );

        $this->assertSame( $mock, $mock->setPath("example.php") );
        $this->assertSame( "./", $mock->getRawDir() );
        $this->assertSame( "example", $mock->getFilename() );
        $this->assertSame( "php", $mock->getExt() );

        $this->assertSame( $mock, $mock->setPath("example") );
        $this->assertSame( "./", $mock->getRawDir() );
        $this->assertSame( "example", $mock->getFilename() );
        $this->assertNull( $mock->getExt() );

        $this->assertSame( $mock, $mock->setPath("") );
        $this->assertNull( $mock->getRawDir() );
        $this->assertNull( $mock->getFilename() );
        $this->assertNull( $mock->getExt() );
    }

    public function testGetPath ()
    {
        $mock = new ::cPHP::FileSystem::File;

        $this->assertNull( $mock->getPath() );

        $mock->setDir("dir/to");
        $this->assertSame( "dir/to/", $mock->getPath() );

        $mock->setDir("/dir/to/");
        $this->assertSame( "/dir/to/", $mock->getPath() );

        $mock->setExt("php");
        $this->assertSame( "/dir/to/", $mock->getPath() );

        $mock->setFilename("Example");
        $this->assertSame( "/dir/to/Example.php", $mock->getPath() );

        $mock->clearExt();
        $this->assertSame( "/dir/to/Example", $mock->getPath() );

        $mock->clearDir();
        $this->assertSame( "Example", $mock->getPath() );

        $mock->setExt("php");
        $this->assertSame( "Example.php", $mock->getPath() );

        $mock->clearFilename()->clearExt();
        $this->assertNull( $mock->getPath() );
    }

    public function testExtAccessors ()
    {
        $mock = new ::cPHP::FileSystem::File;

        $this->assertNull( $mock->getExt() );
        $this->assertFalse( $mock->extExists() );

        $this->assertSame( $mock, $mock->setExt(".ext") );
        $this->assertSame( "ext", $mock->getExt() );
        $this->assertTrue( $mock->extExists() );

        $this->assertSame( $mock, $mock->setExt(".") );
        $this->assertNull( $mock->getExt() );
        $this->assertFalse( $mock->extExists() );

        $this->assertSame( $mock, $mock->setExt("") );
        $this->assertNull( $mock->getExt() );
        $this->assertFalse( $mock->extExists() );

        $this->assertSame( $mock, $mock->setExt("php.BAK") );
        $this->assertSame( "php.BAK", $mock->getExt() );
        $this->assertTrue( $mock->extExists() );

        $this->assertSame( $mock, $mock->clearExt() );
        $this->assertNull( $mock->getExt() );
        $this->assertFalse( $mock->extExists() );
    }

    public function testFilenameAccessors ()
    {
        $mock = new ::cPHP::FileSystem::File;

        $this->assertNull( $mock->getFilename() );
        $this->assertFalse( $mock->filenameExists() );

        $this->assertSame( $mock, $mock->setFilename("filename.") );
        $this->assertSame( "filename", $mock->getFilename() );
        $this->assertTrue( $mock->filenameExists() );

        $this->assertSame( $mock, $mock->setFilename(".") );
        $this->assertNull( $mock->getFilename() );
        $this->assertFalse( $mock->filenameExists() );

        $this->assertSame( $mock, $mock->setFilename("") );
        $this->assertNull( $mock->getFilename() );
        $this->assertFalse( $mock->filenameExists() );

        $this->assertSame( $mock, $mock->setFilename("Filename.2008") );
        $this->assertSame( "Filename.2008", $mock->getFilename() );
        $this->assertTrue( $mock->filenameExists() );

        $this->assertSame( $mock, $mock->clearFilename() );
        $this->assertNull( $mock->getFilename() );
        $this->assertFalse( $mock->filenameExists() );
    }

    public function testSetBasename ()
    {
        $mock = new ::cPHP::FileSystem::File;

        $this->assertSame( $mock, $mock->setBasename("example.php") );
        $this->assertSame( "example", $mock->getFilename() );
        $this->assertSame( "php", $mock->getExt() );

        $this->assertSame( $mock, $mock->setBasename("example") );
        $this->assertSame( "example", $mock->getFilename() );
        $this->assertNull( $mock->getExt() );

        $this->assertSame( $mock, $mock->setBasename(".php") );
        $this->assertNull( $mock->getFilename() );
        $this->assertSame( "php", $mock->getExt() );

        $this->assertSame( $mock, $mock->setBasename("dir/to/example.php") );
        $this->assertSame( "example", $mock->getFilename() );
        $this->assertSame( "php", $mock->getExt() );

        $this->assertSame( $mock, $mock->setBasename("") );
        $this->assertNull( $mock->getFilename() );
        $this->assertNull( $mock->getExt() );
    }

    public function testGetBasename ()
    {
        $mock = new ::cPHP::FileSystem::File;
        $this->assertNull( $mock->getBasename() );

        $mock->setExt("php");
        $this->assertNull( $mock->getBasename() );

        $mock->setFilename("example");
        $this->assertSame( "example.php", $mock->getBasename() );

        $mock->clearExt();
        $this->assertSame( "example", $mock->getBasename() );

        $mock->clearFilename();
        $this->assertNull( $mock->getBasename() );
    }

}

/**
 * Unit Tests that use a temporary file
 */
class classes_filesystem_file_withFile extends PHPUnit_TestFile_Framework_TestCase
{

    public function testExists ()
    {
        $mock = new ::cPHP::FileSystem::File( $this->file );
        $this->assertTrue( $mock->exists() );

        $mock = new ::cPHP::FileSystem::File( __DIR__ );
        $this->assertFalse( $mock->exists() );

        $mock = new ::cPHP::FileSystem::File( "/path/to/missing/file" );
        $this->assertFalse( $mock->exists() );
    }

    public function testGet ()
    {
        $mock = new ::cPHP::FileSystem::File( $this->file );
        $this->assertSame(
                "This is a string\n"
                ."of data that is put\n"
                ."in the test file",
                $mock->get()
            );


        chmod( $this->file, 0000 );
        $mock = new ::cPHP::FileSystem::File( $this->file );
        try {
            $mock->get();
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::FileSystem $err ) {
            $this->assertSame( "Unable read data from file", $err->getMessage() );
        }


        $mock = new ::cPHP::FileSystem::File( "/path/to/missing/file" );
        try {
            $mock->get();
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::FileSystem::Missing $err ) {
            $this->assertSame( "Path does not exist", $err->getMessage() );
        }
    }

    public function testSet ()
    {
        $mock = new ::cPHP::FileSystem::File( $this->file );
        $this->assertSame( $mock, $mock->set("This is a new chunk of info") );
        $this->assertSame(
                "This is a new chunk of info",
                $mock->get()
            );

        chmod( $this->file, 0400 );

        try {
            $mock->set( "data" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::FileSystem $err ) {
            $this->assertSame( "Unable write data to file", $err->getMessage() );
        }
    }

    public function testAppend ()
    {
        $mock = new ::cPHP::FileSystem::File( $this->file );
        $this->assertSame( $mock, $mock->append("\nnew data") );
        $this->assertSame(
                "This is a string\n"
                ."of data that is put\n"
                ."in the test file\n"
                ."new data",
                $mock->get()
            );

        $this->assertSame( $mock, $mock->append("\nAnother snippet") );
        $this->assertSame(
                "This is a string\n"
                ."of data that is put\n"
                ."in the test file\n"
                ."new data\n"
                ."Another snippet",
                $mock->get()
            );

        chmod( $this->file, 0400 );

        try {
            $mock->append( "data" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::FileSystem $err ) {
            $this->assertSame( "Unable write data to file", $err->getMessage() );
        }
    }

    public function testToArray ()
    {
        $mock = new ::cPHP::FileSystem::File( $this->file );
        $this->assertSame(
                array(
                        "This is a string\n",
                        "of data that is put\n",
                        "in the test file"
                    ),
                $mock->toArray()
            );

        chmod( $this->file, 0000 );
        $mock = new ::cPHP::FileSystem::File( $this->file );
        try {
            $mock->toArray();
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::FileSystem $err ) {
            $this->assertSame( "Unable to read data from file", $err->getMessage() );
        }


        $mock = new ::cPHP::FileSystem::File( "/path/to/missing/file" );
        try {
            $mock->toArray();
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::FileSystem::Missing $err ) {
            $this->assertSame( "Path does not exist", $err->getMessage() );
        }
    }

    public function testGetSize ()
    {
        $mock = new ::cPHP::FileSystem::File( $this->file );
        $this->assertType( 'integer', $mock->getSize() );
        $this->assertGreaterThan( 0, $mock->getSize() );


        $mock = new ::cPHP::FileSystem::File( "/path/to/missing/file" );
        try {
            $mock->getSize();
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::FileSystem::Missing $err ) {
            $this->assertSame( "Path does not exist", $err->getMessage() );
        }
    }

    public function testTruncate ()
    {
        $mock = new ::cPHP::FileSystem::File( $this->file );
        $this->assertSame( $mock, $mock->truncate() );
        $this->assertSame( "", file_get_contents($this->file) );
    }

    public function testDelete ()
    {
        $mock = new ::cPHP::FileSystem::File( $this->file );

        $this->assertSame( $mock, $mock->delete() );

        if ( file_exists($this->file) )
            $this->fail("File deletion failed");

        $this->assertSame( $mock, $mock->delete() );
    }

    public function testGetMimeType ()
    {
        $mock = new ::cPHP::FileSystem::File;

        try {
            $mock->getMimeType();
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::FileSystem::Missing $err ) {
            $this->assertSame( "Path does not exist", $err->getMessage() );
        }

        $mock->setPath( $this->file );

        $this->assertSame( "text/plain", $mock->getMimeType() );

        // Copy the contents of a gif in to the file
        file_put_contents(
                $this->file,
                base64_decode("R0lGODdhAQABAIAAAP///////ywAAAAAAQABAAACAkQBADs="),
                FILE_BINARY
            );

        $this->assertSame( "image/gif", $mock->getMimeType() );
    }

    public function testCopy ()
    {
        $mock = new ::cPHP::FileSystem::File( $this->file );
        $newPath = $this->getTempFileName();

        $copied = $mock->copy( $newPath );

        $this->assertThat( $copied, $this->isInstanceOf("cPHP::FileSystem::File") );
        $this->assertNotSame( $mock, $copied );

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

        $mock = new ::cPHP::FileSystem::File( "/path/to/missing/file" );
        try {
            $mock->copy( "/some/new/location" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::FileSystem::Missing $err ) {
            $this->assertSame( "Path does not exist", $err->getMessage() );
        }
    }

    public function testCopy_noPerms ()
    {
        $mock = new ::cPHP::FileSystem::File( $this->file );

        chmod( $this->file, 0000 );

        try {
            $mock->copy( $this->getTempFileName() );
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::FileSystem $err ) {
            $this->assertSame( "Unable to copy file", $err->getMessage() );
        }
        
    }

    public function testMove ()
    {
        $this->markTestIncomplete("To be written");
    }

}

?>