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
 * Unit test for running both filesystem test suites
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
        $this->markTestIncomplete("To be written");
    }

    public function testGetPath ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testExists ()
    {
        $this->markTestIncomplete("To be written");
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

    public function testGet ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testSet ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testToArray ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testGetSize ()
    {
        $this->markTestIncomplete("To be written");
    }

}

?>