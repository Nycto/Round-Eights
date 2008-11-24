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
        $dir = new ::cPHP::FileSystem::Dir;

        $this->assertNull( $dir->getRawDir() );
        $this->assertFalse( $dir->dirExists() );

        $this->assertSame( $dir, $dir->setPath("/path/to/dir") );
        $this->assertSame( "/path/to/dir/", $dir->getRawDir() );
        $this->assertSame( "/path/to/dir/", $dir->getPath() );
        $this->assertTrue( $dir->dirExists() );

        $this->assertSame( $dir, $dir->setPath("") );
        $this->assertNull( $dir->getRawDir() );
        $this->assertNull( $dir->getPath() );
        $this->assertFalse( $dir->dirExists() );

        $this->assertSame( $dir, $dir->setPath("c:\\path\\to\\\\dir\\\\") );
        $this->assertSame( "c:/path/to/dir/", $dir->getRawDir() );
        $this->assertSame( "c:/path/to/dir/", $dir->getPath() );
        $this->assertTrue( $dir->dirExists() );
    }

    public function testExists ()
    {
        $dir = new ::cPHP::FileSystem::Dir;

        $dir->setPath( __DIR__ );
        $this->assertTrue( $dir->exists() );

        $dir->setPath( __FILE__ );
        $this->assertFalse( $dir->exists() );

        $dir->setPath( "/this/is/not/a/real/path" );
        $this->assertFalse( $dir->exists() );
    }

    public function testGetBasename ()
    {
        $dir = new ::cPHP::FileSystem::Dir;
        $this->assertNull( $dir->getBasename() );

        $dir->setPath( "/dir/to/path" );
        $this->assertSame( "path", $dir->getBasename() );

        $dir->setDir( "/This/is/aPath/" );
        $this->assertSame( "aPath", $dir->getBasename() );

        $dir->clearDir();
        $this->assertNull( $dir->getBasename() );
    }

    public function testIncludeDotsAccessors ()
    {
        $dir = new ::cPHP::FileSystem::Dir;
        $this->assertTrue( $dir->getIncludeDots() );

        $this->assertSame( $dir, $dir->setIncludeDots(FALSE) );
        $this->assertFalse( $dir->getIncludeDots() );

        $this->assertSame( $dir, $dir->setIncludeDots(TRUE) );
        $this->assertTrue( $dir->getIncludeDots() );

        $this->assertSame( $dir, $dir->setIncludeDots(null) );
        $this->assertFalse( $dir->getIncludeDots() );

        $this->assertSame( $dir, $dir->setIncludeDots("string") );
        $this->assertTrue( $dir->getIncludeDots() );
    }

    public function testIteration_missing ()
    {
        $dir = new ::cPHP::FileSystem::Dir("/path/to/a/dir/that/isnt/real");

        try {
            foreach( $dir AS $item ) {}
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::FileSystem::Missing $err ) {
            $this->assertSame( "Path does not exist", $err->getMessage() );
        }
    }

    public function testIteration_noRewind ()
    {
        $dir = new ::cPHP::FileSystem::Dir("/path/to/a/dir/that/isnt/real");

        try {
            $dir->current();
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::Interaction $err ) {
            $this->assertSame( "Iteration has not been rewound", $err->getMessage() );
        }

        $this->assertFalse( $dir->valid() );

        try {
            $dir->current();
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::Interaction $err ) {
            $this->assertSame( "Iteration has not been rewound", $err->getMessage() );
        }

        try {
            $dir->key();
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::Interaction $err ) {
            $this->assertSame( "Iteration has not been rewound", $err->getMessage() );
        }

        try {
            $dir->hasChildren();
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::Interaction $err ) {
            $this->assertSame( "Iteration has not been rewound", $err->getMessage() );
        }

        try {
            $dir->getChildren();
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::Interaction $err ) {
            $this->assertSame( "Iteration has not been rewound", $err->getMessage() );
        }

    }

    public function testTemp ()
    {
        $dir = ::cPHP::FileSystem::Dir::getTemp();

        $this->assertThat( $dir, $this->isInstanceOf("cPHP::FileSystem::Dir") );
        $this->assertSame(
                rtrim( sys_get_temp_dir(), "/" ),
                rtrim( $dir->getPath(), "/" )
            );
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

            $i++;
            if ( $i >= 10 )
                $this->fail("Maximum iterations reached");

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

    public function testIteration_twice ()
    {
        $dir = new ::cPHP::FileSystem::Dir( $this->dir );

        // Iterate through it once
        $i = 0;
        foreach ( $dir AS $key => $item ) {
            $i++;
            if ( $i >= 10 )
                $this->fail("Maximum iterations reached");
        }

        $this->assertSame( 9, $i );


        // Ensure we can do it again
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

            $i++;
            if ( $i >= 10 )
                $this->fail("Maximum iterations reached");

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


    public function testIteration_break ()
    {
        $dir = new ::cPHP::FileSystem::Dir( $this->dir );

        // Iterate through it once and break before iteration completes
        $i = 0;
        foreach ( $dir AS $key => $item ) {
            $i++;
            if ( $i >= 3 )
                break;
        }


        // Ensure we can do it again
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

            $i++;
            if ( $i >= 10 )
                $this->fail("Maximum iterations reached");

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

    public function testIteration_NoDots ()
    {
        $dir = new ::cPHP::FileSystem::Dir( $this->dir );
        $dir->setIncludeDots( FALSE );

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

            $i++;
            if ( $i >= 8 )
                $this->fail("Maximum iterations reached");

        }

        $this->assertSame(
                array(0, 1, 2, 3, 4, 5, 6),
                $keys
            );

        // I do it like this because there is no guarantee of the order in which
        // the directories will appear in
        $this->assertContains("first", $dirs);
        $this->assertContains("second", $dirs);
        $this->assertContains("third", $dirs);
        $this->assertSame( 3, count($dirs) );

        $this->assertContains("one", $files);
        $this->assertContains("two", $files);
        $this->assertContains("three", $files);
        $this->assertContains("four", $files);
        $this->assertSame( 4, count($files) );
    }

    public function testIteration_NoPerms ()
    {
        $dir = new ::cPHP::FileSystem::Dir( $this->dir );
        chmod( $dir, 0000 );

        try {
            foreach( $dir AS $item ) {}
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::FileSystem $err ) {
            $this->assertSame( "Unable to open directory for iteration", $err->getMessage() );
        }
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

            $i++;
            if ( $i >= 21 )
                $this->fail("Maximum iterations reached");


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

    public function testRecursiveIteration_noDots ()
    {
        $dir = new ::cPHP::FileSystem::Dir( $this->dir );
        $dir->setIncludeDots( FALSE );

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

            $i++;
            if ( $i >= 21 )
                $this->fail("Maximum iterations reached");


        }

        $this->assertSame( 0, count($dirs) );

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

    public function testToArray ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testMake ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testPurge ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testDelete ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testGetUniqueFile ()
    {
        $this->markTestIncomplete("To be written");
    }

}

?>