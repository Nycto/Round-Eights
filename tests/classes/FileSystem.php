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

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * unit tests
 */
class classes_filesystem extends PHPUnit_Framework_TestCase
{

    public function getTestObject ()
    {
        return $this->getMock(
                "cPHP::FileSystem",
                array("getPath", "setPath", "exists")
            );
    }

    public function testRequirePath ()
    {
        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("exists")
            ->will( $this->returnValue( TRUE ) );

        $this->assertSame( $mock, $mock->requirePath() );


        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("exists")
            ->will( $this->returnValue( FALSE ) );

        try {
            $mock->requirePath();
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::FileSystem::Missing $err ) {
            $this->assertSame( "Path does not exist", $err->getMessage() );
        }
    }

    public function testIsDir ()
    {
        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("getPath")
            ->will( $this->returnValue( __FILE__ ) );

        $this->assertFalse( $mock->isDir() );


        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("getPath")
            ->will( $this->returnValue( dirname(__FILE__) ) );

        $this->assertTrue( $mock->isDir() );
    }

    public function testIsFile ()
    {
        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("getPath")
            ->will( $this->returnValue( __FILE__ ) );

        $this->assertTrue( $mock->isFile() );


        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("getPath")
            ->will( $this->returnValue( dirname(__FILE__) ) );

        $this->assertFalse( $mock->isFile() );
    }

    public function testIsLink ()
    {
        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("getPath")
            ->will( $this->returnValue( __FILE__ ) );

        $this->assertFalse( $mock->isLink() );


        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("getPath")
            ->will( $this->returnValue( dirname(__FILE__) ) );

        $this->assertFalse( $mock->isLink() );
    }

    public function testGetCTime_missing ()
    {
        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("exists")
            ->will( $this->returnValue( FALSE ) );

        $mock->expects( $this->once() )
            ->method("getPath")
            ->will( $this->returnValue( dirname(__FILE__) ) );

        try {
            $mock->getCTime();
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::FileSystem::Missing $err ) {
            $this->assertSame( "Path does not exist", $err->getMessage() );
        }
    }

    public function testGetCTime ()
    {
        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("exists")
            ->will( $this->returnValue( TRUE ) );

        $mock->expects( $this->once() )
            ->method("getPath")
            ->will( $this->returnValue( dirname(__FILE__) ) );

        $cTime = $mock->getCTime();

        $this->assertThat( $cTime, $this->isInstanceOf("cPHP::DateTime") );
        $this->assertGreaterThan( 0, $cTime->getTimeStamp() );
    }

    public function testGetATime_missing ()
    {
        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("exists")
            ->will( $this->returnValue( FALSE ) );

        $mock->expects( $this->once() )
            ->method("getPath")
            ->will( $this->returnValue( dirname(__FILE__) ) );

        try {
            $mock->getATime();
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::FileSystem::Missing $err ) {
            $this->assertSame( "Path does not exist", $err->getMessage() );
        }
    }

    public function testGetATime ()
    {
        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("exists")
            ->will( $this->returnValue( TRUE ) );

        $mock->expects( $this->once() )
            ->method("getPath")
            ->will( $this->returnValue( dirname(__FILE__) ) );

        $time = $mock->getATime();

        $this->assertThat( $time, $this->isInstanceOf("cPHP::DateTime") );
        $this->assertGreaterThan( 0, $time->getTimeStamp() );
    }

    public function testGetMTime_missing ()
    {
        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("exists")
            ->will( $this->returnValue( FALSE ) );

        $mock->expects( $this->once() )
            ->method("getPath")
            ->will( $this->returnValue( dirname(__FILE__) ) );

        try {
            $mock->getMTime();
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::FileSystem::Missing $err ) {
            $this->assertSame( "Path does not exist", $err->getMessage() );
        }
    }

    public function testGetMTime ()
    {
        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("exists")
            ->will( $this->returnValue( TRUE ) );

        $mock->expects( $this->once() )
            ->method("getPath")
            ->will( $this->returnValue( dirname(__FILE__) ) );

        $time = $mock->getMTime();

        $this->assertThat( $time, $this->isInstanceOf("cPHP::DateTime") );
        $this->assertGreaterThan( 0, $time->getTimeStamp() );
    }

    public function testGetGroupID_file ()
    {
        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("exists")
            ->will( $this->returnValue( TRUE ) );

        $mock->expects( $this->once() )
            ->method("getPath")
            ->will( $this->returnValue( __FILE__ ) );

        $group = $mock->getGroupID();

        $this->assertType( "integer", $group );
        $this->assertGreaterThan( 0, $group );
    }

    public function testGetGroupID_dir ()
    {
        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("exists")
            ->will( $this->returnValue( TRUE ) );

        $mock->expects( $this->once() )
            ->method("getPath")
            ->will( $this->returnValue( dirname(__FILE__) ) );

        $group = $mock->getGroupID();

        $this->assertType( "integer", $group );
        $this->assertGreaterThan( 0, $group );
    }

    public function testGetGroupID_missing ()
    {
        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("exists")
            ->will( $this->returnValue( FALSE ) );

        $mock->expects( $this->once() )
            ->method("getPath")
            ->will( $this->returnValue( dirname(__FILE__) ) );

        try {
            $mock->getGroupID();
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::FileSystem::Missing $err ) {
            $this->assertSame( "Path does not exist", $err->getMessage() );
        }
    }

    public function testGetOwnerID ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testIsReadable()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testIsWritable()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testIsExecutable ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testGetPerms ()
    {
        $this->markTestIncomplete("To be written");
    }

}

?>