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
class classes_filesystem_dir
{

    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite;
        $suite->addTestSuite( 'classes_filesystem_dir_noData' );
        $suite->addTestSuite( 'classes_filesystem_dir_withData' );
        return $suite;
    }

}

/**
 * unit tests that don't require temporary files/directories to be created
 */
class classes_filesystem_dir_noData extends PHPUnit_Framework_TestCase
{

    public function testSetGetPath ()
    {
        $mock = new ::cPHP::FileSystem::Dir;

        $this->assertNull( $mock->getRawDir() );
        $this->assertFalse( $mock->dirExists() );

        $this->assertSame( $mock, $mock->setPath("/path/to/dir") );
        $this->assertSame( "/path/to/dir/", $mock->getRawDir() );
        $this->assertSame( "/path/to/dir/", $mock->getPath() );
        $this->assertTrue( $mock->dirExists() );

        $this->assertSame( $mock, $mock->setPath("") );
        $this->assertNull( $mock->getRawDir() );
        $this->assertNull( $mock->getPath() );
        $this->assertFalse( $mock->dirExists() );

        $this->assertSame( $mock, $mock->setPath("c:\\path\\to\\\\dir\\\\") );
        $this->assertSame( "c:/path/to/dir/", $mock->getRawDir() );
        $this->assertSame( "c:/path/to/dir/", $mock->getPath() );
        $this->assertTrue( $mock->dirExists() );
    }

    public function testExists ()
    {
        $mock = new ::cPHP::FileSystem::Dir;

        $mock->setPath( __DIR__ );
        $this->assertTrue( $mock->exists() );

        $mock->setPath( __FILE__ );
        $this->assertFalse( $mock->exists() );

        $mock->setPath( "/this/is/not/a/real/path" );
        $this->assertFalse( $mock->exists() );
    }

    public function testGetBasename ()
    {
        $mock = new ::cPHP::FileSystem::Dir;
        $this->assertNull( $mock->getBasename() );

        $mock->setPath( "/dir/to/path" );
        $this->assertSame( "path", $mock->getBasename() );

        $mock->setDir( "/This/is/aPath/" );
        $this->assertSame( "aPath", $mock->getBasename() );

        $mock->clearDir();
        $this->assertNull( $mock->getBasename() );
    }

}

/**
 * unit tests that use temporary files/directories
 */
class classes_filesystem_dir_withData extends PHPUnit_Framework_TestCase
{

    /**
     * The temporary directory that was created
     */
    protected $dir;

    /**
     * Creates a new temporary directory with a set of fake files in it
     */
    public function setUp ()
    {
        $this->dir = rtrim( sys_get_temp_dir(), "/" ) ."/cPHP_". uniqid();

        if (!mkdir( $this->dir ))
            $this->markTestSkipped("Unable to create temporary directory: ". $this->dir);

        $toCreate = array(
                "first/.",
                "second/second-one",
                "third/third-one",
                "third/third-two",
                "third/third-three",
                "third/fourth/.",
                "third/fourth/fourth-one",
                "third/fourth/fourth-two",
                "one",
                "two",
                "three",
                "four",
            );

        foreach ( $toCreate AS $path ) {

            $dirname = dirname($path);

            if ( $dirname != "." ) {

                $dirname = $this->dir ."/". $dirname;

                if ( !is_dir($dirname) && !mkdir($dirname, 0777) )
                    $this->markTestSkipped("Unable to create temporary dir: ". $dirname );

            }

            if ( basename($path) != "." ) {

                $basename = $this->dir ."/". $path;

                if ( !touch($basename) )
                    $this->markTestSkipped("Unable to create temporary file: ". $basename );

                @chmod( $basename, 0777 );
            }

        }

    }

    /**
     * Deletes a given path and everything in it
     */
    public function delete ( $path )
    {

        if ( is_file($path) ) {
            @chmod($path, 0777);
            @unlink($path);
        }

        else if( is_dir($path) ) {

            @chmod($path, 0777);

            foreach( new DirectoryIterator($path) as $item ) {

                if( $item->isDot() )
                    continue;

                if( $item->isFile() )
                    $this->delete( $item->getPathName() );

                else if( $item->isDir() )
                    $this->delete( $item->getRealPath() );

                unset($_res);
            }

            @rmdir( $path );

        }

    }

    /**
     * Deletes the temporary files
     */
    public function tearDown ()
    {
        $this->delete( $this->dir );
    }

    public function testIteration ()
    {
        $dir = new ::cPHP::FileSystem::Dir( $this->dir );

        $files = array();
        $dirs = array();
        $keys = array();
        $i = 0;

        foreach ( $dir AS $key => $item ) {

            $keys[] = $key;

            if ( $item instanceof ::cPHP::FileSystem::Dir)
                $dirs[] = $item->getBasename();
            else if ( $item instanceof ::cPHP::FileSystem::File)
                $files[] = $item->getBasename();

            if ( $i >= 9 )
                $this->fail("Maximum iterations reached");

            $i++;
        }

        $this->assertSame(
                array(0, 1, 2, 3, 4, 5, 6, 7, 8),
                $keys
            );

        // I do it like this because there is no guarantee of the order in which
        // the directories will appear in
        $this->assertContains("first", $dirs);
        $this->assertContains("second", $dirs);
        $this->assertContains("third", $dirs);
        $this->assertContains(".", $dirs);
        $this->assertContains("..", $dirs);
        $this->assertSame( 5, count($dirs) );

        $this->assertContains("one", $files);
        $this->assertContains("two", $files);
        $this->assertContains("three", $files);
        $this->assertContains("four", $files);
        $this->assertSame( 4, count($files) );
    }

    public function testRecursiveIteration ()
    {
        $dir = new ::cPHP::FileSystem::Dir( $this->dir );

        $files = array();
        $dirs = array();
        $keys = array();
        $i = 0;

        foreach ( new RecursiveIteratorIterator($dir) AS $key => $item ) {

            $keys[] = $key;

            if ( $item instanceof ::cPHP::FileSystem::Dir)
                $dirs[] = $item->getBasename();
            else if ( $item instanceof ::cPHP::FileSystem::File)
                $files[] = $item->getBasename();

            if ( $i >= 30 )
                $this->fail("Maximum iterations reached");

            $i++;

        }

        // I do it like this because there is no guarantee of the order in which
        // the directories will appear in
        $this->assertContains(".", $dirs);
        $this->assertContains("..", $dirs);
        $this->assertSame( 10, count($dirs) );
        $this->assertSame( 2, count( array_unique($dirs) ) );

        $this->assertContains("one", $files);
        $this->assertContains("two", $files);
        $this->assertContains("three", $files);
        $this->assertContains("four", $files);
        $this->assertContains("second-one", $files);
        $this->assertContains("third-one", $files);
        $this->assertContains("third-two", $files);
        $this->assertContains("third-three", $files);
        $this->assertContains("fourth-one", $files);
        $this->assertContains("fourth-two", $files);
        $this->assertSame( 10, count($files) );
    }

}

?>