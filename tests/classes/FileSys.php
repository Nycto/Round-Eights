<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of commonPHP.
 *
 * commonPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * commonPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with commonPHP. If not, see <http://www.commonphp.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@commonphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * unit tests
 */
class classes_filesys extends PHPUnit_Framework_TestCase
{

    public function testCallStatic ()
    {
        $filesys = \cPHP\FileSys::File();
        $this->assertThat( $filesys, $this->isInstanceOf('cPHP\FileSys\File') );
        $this->assertNull( $filesys->getPath() );

        $filesys = \cPHP\FileSys::Dir();
        $this->assertThat( $filesys, $this->isInstanceOf('cPHP\FileSys\Dir') );
        $this->assertNull( $filesys->getPath() );

        $filesys = \cPHP\FileSys::File("/dir/to/file.php");
        $this->assertThat( $filesys, $this->isInstanceOf('cPHP\FileSys\File') );
        $this->assertSame( "/dir/to/file.php", $filesys->getPath() );

        $filesys = \cPHP\FileSys::Dir("/dir/path/");
        $this->assertThat( $filesys, $this->isInstanceOf('cPHP\FileSys\Dir') );
        $this->assertSame( "/dir/path/", $filesys->getPath() );

        try {
            \cPHP\FileSys::NotARealClass();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame(
                    "Class could not be found in \cPHP\FileSys namespace",
                    $err->getMessage()
                );
        }
    }

    public function getTestObject ()
    {
        return $this->getMock(
                "\\cPHP\\FileSys",
                array("getPath", "setPath", "exists")
            );
    }

    public function testToString ()
    {
        $mock = $this->getTestObject();
        $mock->expects( $this->exactly(2) )
            ->method("getPath")
            ->will( $this->returnValue("/path/to/file.txt") );

        $this->assertSame( "/path/to/file.txt", $mock->__toString() );
        $this->assertSame( "/path/to/file.txt", "$mock" );

        $mock = $this->getTestObject();
        $mock->expects( $this->exactly(2) )
            ->method("getPath")
            ->will( $this->returnValue( null ) );

        $this->assertSame( "", $mock->__toString() );
        $this->assertSame( "", "$mock" );
    }

    public function testDirAccessors ()
    {
        $mock = $this->getTestObject();

        $this->assertNull( $mock->getRawDir() );
        $this->assertFalse( $mock->dirExists() );

        $this->assertSame( $mock, $mock->setDir("/path/to/dir") );
        $this->assertSame( "/path/to/dir/", $mock->getRawDir() );
        $this->assertTrue( $mock->dirExists() );

        $this->assertSame( $mock, $mock->setDir("") );
        $this->assertNull( $mock->getRawDir() );
        $this->assertFalse( $mock->dirExists() );

        $this->assertSame( $mock, $mock->setDir("c:\\path\\to\\\\dir\\\\") );
        $this->assertSame( "c:/path/to/dir/", $mock->getRawDir() );
        $this->assertTrue( $mock->dirExists() );

        $this->assertSame( $mock, $mock->clearDir() );
        $this->assertNull( $mock->getRawDir() );
        $this->assertFalse( $mock->dirExists() );
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
        catch ( \cPHP\Exception\FileSystem\Missing $err ) {
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
            ->will( $this->returnValue( __DIR__ ) );

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
            ->will( $this->returnValue( __DIR__ ) );

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
            ->will( $this->returnValue( __DIR__ ) );

        $this->assertFalse( $mock->isLink() );
    }

    public function testIsReadable ()
    {
        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("getPath")
            ->will( $this->returnValue( __FILE__ ) );

        $this->assertType( 'boolean', $mock->isReadable() );


        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("getPath")
            ->will( $this->returnValue( __DIR__ ) );

        $this->assertType( 'boolean', $mock->isReadable() );
    }

    public function testIsWritable()
    {
        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("getPath")
            ->will( $this->returnValue( __FILE__ ) );

        $this->assertType( 'boolean', $mock->isWritable() );


        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("getPath")
            ->will( $this->returnValue( __DIR__ ) );

        $this->assertType( 'boolean', $mock->isWritable() );
    }

    public function testIsExecutable ()
    {
        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("getPath")
            ->will( $this->returnValue( __FILE__ ) );

        $this->assertType( 'boolean', $mock->isExecutable() );


        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("getPath")
            ->will( $this->returnValue( __DIR__ ) );

        $this->assertType( 'boolean', $mock->isExecutable() );
    }

    public function testGetCTime_missing ()
    {
        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("exists")
            ->will( $this->returnValue( FALSE ) );

        try {
            $mock->getCTime();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\FileSystem\Missing $err ) {
            $this->assertSame( "Path does not exist", $err->getMessage() );
        }
    }

    public function testGetCTime_dir ()
    {
        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("exists")
            ->will( $this->returnValue( TRUE ) );

        $mock->expects( $this->once() )
            ->method("getPath")
            ->will( $this->returnValue( __DIR__ ) );

        $cTime = $mock->getCTime();

        $this->assertThat( $cTime, $this->isInstanceOf("cPHP\DateTime") );
        $this->assertGreaterThan( 0, $cTime->getTimeStamp() );
    }

    public function testGetCTime_file ()
    {
        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("exists")
            ->will( $this->returnValue( TRUE ) );

        $mock->expects( $this->once() )
            ->method("getPath")
            ->will( $this->returnValue( __FILE__ ) );

        $cTime = $mock->getCTime();

        $this->assertThat( $cTime, $this->isInstanceOf("cPHP\DateTime") );
        $this->assertGreaterThan( 0, $cTime->getTimeStamp() );
    }

    public function testGetATime_missing ()
    {
        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("exists")
            ->will( $this->returnValue( FALSE ) );

        try {
            $mock->getATime();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\FileSystem\Missing $err ) {
            $this->assertSame( "Path does not exist", $err->getMessage() );
        }
    }

    public function testGetATime_dir ()
    {
        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("exists")
            ->will( $this->returnValue( TRUE ) );

        $mock->expects( $this->once() )
            ->method("getPath")
            ->will( $this->returnValue( __DIR__ ) );

        $time = $mock->getATime();

        $this->assertThat( $time, $this->isInstanceOf("cPHP\DateTime") );
        $this->assertGreaterThan( 0, $time->getTimeStamp() );
    }

    public function testGetATime_file ()
    {
        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("exists")
            ->will( $this->returnValue( TRUE ) );

        $mock->expects( $this->once() )
            ->method("getPath")
            ->will( $this->returnValue( __FILE__ ) );

        $time = $mock->getATime();

        $this->assertThat( $time, $this->isInstanceOf("cPHP\DateTime") );
        $this->assertGreaterThan( 0, $time->getTimeStamp() );
    }

    public function testGetMTime_missing ()
    {
        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("exists")
            ->will( $this->returnValue( FALSE ) );

        try {
            $mock->getMTime();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\FileSystem\Missing $err ) {
            $this->assertSame( "Path does not exist", $err->getMessage() );
        }
    }

    public function testGetMTime_dir ()
    {
        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("exists")
            ->will( $this->returnValue( TRUE ) );

        $mock->expects( $this->once() )
            ->method("getPath")
            ->will( $this->returnValue( __DIR__ ) );

        $time = $mock->getMTime();

        $this->assertThat( $time, $this->isInstanceOf("cPHP\DateTime") );
        $this->assertGreaterThan( 0, $time->getTimeStamp() );
    }

    public function testGetMTime_file ()
    {
        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("exists")
            ->will( $this->returnValue( TRUE ) );

        $mock->expects( $this->once() )
            ->method("getPath")
            ->will( $this->returnValue( __FILE__ ) );

        $time = $mock->getMTime();

        $this->assertThat( $time, $this->isInstanceOf("cPHP\DateTime") );
        $this->assertGreaterThan( 0, $time->getTimeStamp() );
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
        catch ( \cPHP\Exception\FileSystem\Missing $err ) {
            $this->assertSame( "Path does not exist", $err->getMessage() );
        }
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

    public function testGetOwnerID_missing ()
    {
        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("exists")
            ->will( $this->returnValue( FALSE ) );

        $mock->expects( $this->once() )
            ->method("getPath")
            ->will( $this->returnValue( dirname(__FILE__) ) );

        try {
            $mock->getOwnerID();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\FileSystem\Missing $err ) {
            $this->assertSame( "Path does not exist", $err->getMessage() );
        }
    }

    public function testGetOwnerID_dir ()
    {
        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("exists")
            ->will( $this->returnValue( TRUE ) );

        $mock->expects( $this->once() )
            ->method("getPath")
            ->will( $this->returnValue( dirname(__FILE__) ) );

        $owner = $mock->getOwnerID();

        $this->assertType( "integer", $owner );
        $this->assertGreaterThan( 0, $owner );
    }

    public function testGetOwnerID_file ()
    {
        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("exists")
            ->will( $this->returnValue( TRUE ) );

        $mock->expects( $this->once() )
            ->method("getPath")
            ->will( $this->returnValue( __FILE__ ) );

        $owner = $mock->getOwnerID();

        $this->assertType( "integer", $owner );
        $this->assertGreaterThan( 0, $owner );
    }

    public function testGetPerms_missing ()
    {
        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("exists")
            ->will( $this->returnValue( FALSE ) );

        $mock->expects( $this->once() )
            ->method("getPath")
            ->will( $this->returnValue( dirname(__FILE__) ) );

        try {
            $mock->getPerms();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\FileSystem\Missing $err ) {
            $this->assertSame( "Path does not exist", $err->getMessage() );
        }
    }

    public function testGetPerms_dir ()
    {
        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("exists")
            ->will( $this->returnValue( TRUE ) );

        $mock->expects( $this->once() )
            ->method("getPath")
            ->will( $this->returnValue( dirname(__FILE__) ) );

        $perms = $mock->getPerms();

        $this->assertType( "integer", $perms );
        $this->assertGreaterThan( 0, $perms );
    }

    public function testGetPerms_file ()
    {
        $mock = $this->getTestObject();
        $mock->expects( $this->once() )
            ->method("exists")
            ->will( $this->returnValue( TRUE ) );

        $mock->expects( $this->once() )
            ->method("getPath")
            ->will( $this->returnValue( __FILE__ ) );

        $perms = $mock->getPerms();

        $this->assertType( "integer", $perms );
        $this->assertGreaterThan( 0, $perms );
    }

    public function testResolvePath ()
    {
        $this->assertSame( 'test.php', \cPHP\FileSys::resolvePath('test.php') );
        $this->assertSame( 'dir/test.php', \cPHP\FileSys::resolvePath('dir/test.php') );
        $this->assertSame( '/test.php', \cPHP\FileSys::resolvePath('/test.php') );
        $this->assertSame( 'c:/test.php', \cPHP\FileSys::resolvePath('c:/test.php') );
        $this->assertSame( 'c:/dir/test.php', \cPHP\FileSys::resolvePath('c:\\dir\\test.php') );

        $this->assertSame( 'test.php', \cPHP\FileSys::resolvePath('../test.php') );
        $this->assertSame( '/test.php', \cPHP\FileSys::resolvePath('///////test.php') );
        $this->assertSame( 'test.php', \cPHP\FileSys::resolvePath('./test.php') );
        $this->assertSame( 'dir/test.php', \cPHP\FileSys::resolvePath('dir/./test.php') );
        $this->assertSame( 'dir/test.php', \cPHP\FileSys::resolvePath('dir/sub/../test.php') );

        $this->assertSame( '/test', \cPHP\FileSys::resolvePath('/../test') );
        $this->assertSame( '/test/', \cPHP\FileSys::resolvePath('/../.././../test/') );
        $this->assertSame( '/1/2', \cPHP\FileSys::resolvePath('/1/2/3/4/5/6/../../../..') );

        $this->assertSame( '', \cPHP\FileSys::resolvePath('') );
        $this->assertSame( '/', \cPHP\FileSys::resolvePath('/') );
        $this->assertSame( 'c:/', \cPHP\FileSys::resolvePath('c:/') );
        $this->assertSame( 'D:/', \cPHP\FileSys::resolvePath('D:\\') );
    }

    public function getResolveTest ( $original, $resolved, $cwd = "/" )
    {
        $mock = $this->getMock(
                "\cPHP\FileSys",
                array("getPath", "setPath", "exists", "getCWD")
            );

        $mock->expects( $this->once() )
            ->method("getPath")
            ->will( $this->returnValue( $original ) );

        $mock->expects( $this->any() )
            ->method("getCWD")
            ->will( $this->returnValue( $cwd ) );

        $mock->expects( $this->once() )
            ->method("setPath")
            ->with( $this->equalTo($resolved) );

        return $mock;
    }

    public function testResolve_cwd ()
    {
        $resolve = $this->getResolveTest("test.php", "/dir/to/test.php", "/dir/to/");
        $this->assertSame( $resolve, $resolve->resolve() );

        $resolve = $this->getResolveTest("test.php", "/dir/to/test.php", "/dir/to");
        $this->assertSame( $resolve, $resolve->resolve() );

        $resolve = $this->getResolveTest("test.php", "c:/dir/to/test.php", "c:\\dir\\to");
        $this->assertSame( $resolve, $resolve->resolve() );

        $resolve = $this->getResolveTest("/test/sub", "/test/sub", "/dir/to");
        $this->assertSame( $resolve, $resolve->resolve() );

        $resolve = $this->getResolveTest("c:\\test\\sub", "c:/test/sub", "/dir/to");
        $this->assertSame( $resolve, $resolve->resolve() );

        $resolve = $this->getResolveTest("../../test/", "/test/", "/dir/to");
        $this->assertSame( $resolve, $resolve->resolve() );

        $resolve = $this->getResolveTest("./../test", "/dir/test", "/dir/to");
        $this->assertSame( $resolve, $resolve->resolve() );

        $resolve = $this->getResolveTest(".////.//.///test/added/dirs", "/dir/to/test/added/dirs", "/dir/to");
        $this->assertSame( $resolve, $resolve->resolve() );

        $resolve = $this->getResolveTest("./.././../test", "/dir/to/test", "/dir/to");
        $this->assertSame( $resolve, $resolve->resolve(null, TRUE) );

        $resolve = $this->getResolveTest("/test/another/../../", "/dir/to/", "/dir/to");
        $this->assertSame( $resolve, $resolve->resolve(null, TRUE) );

        $resolve = $this->getResolveTest("c:\\test\\", "/dir/to/test/", "/dir/to");
        $this->assertSame( $resolve, $resolve->resolve(null, TRUE) );
    }

    public function testResolve_base ()
    {
        $resolve = $this->getResolveTest("test.php", "/dir/to/test.php" );
        $this->assertSame( $resolve, $resolve->resolve( "/dir/to/" ) );

        $resolve = $this->getResolveTest("test.php", "/dir/to/test.php" );
        $this->assertSame( $resolve, $resolve->resolve( "/dir/to" ) );

        $resolve = $this->getResolveTest("test.php", "c:/dir/to/test.php" );
        $this->assertSame( $resolve, $resolve->resolve("c:\\dir\\to") );

        $resolve = $this->getResolveTest("/test/sub", "/test/sub" );
        $this->assertSame( $resolve, $resolve->resolve( "/dir/to" ) );

        $resolve = $this->getResolveTest("c:\\test\\sub", "c:/test/sub" );
        $this->assertSame( $resolve, $resolve->resolve("/dir/to") );

        $resolve = $this->getResolveTest("../../test/", "/test/" );
        $this->assertSame( $resolve, $resolve->resolve("/dir/to") );

        $resolve = $this->getResolveTest("./../test", "/dir/test" );
        $this->assertSame( $resolve, $resolve->resolve("/dir/to") );

        $resolve = $this->getResolveTest("../../orig", "/cur/orig", "/cur/work/" );
        $this->assertSame( $resolve, $resolve->resolve("base") );

        $resolve = $this->getResolveTest(".////.//.///test/added/dirs", "/dir/to/test/added/dirs" );
        $this->assertSame( $resolve, $resolve->resolve("/dir/to") );

        $resolve = $this->getResolveTest("./.././../test", "/dir/to/test" );
        $this->assertSame( $resolve, $resolve->resolve("/dir/to", TRUE) );

        $resolve = $this->getResolveTest("/test/another/../../", "/dir/to/" );
        $this->assertSame( $resolve, $resolve->resolve("/dir/to", TRUE) );

        $resolve = $this->getResolveTest("c:\\test\\", "/dir/to/test/" );
        $this->assertSame( $resolve, $resolve->resolve("/dir/to", TRUE) );

        $resolve = $this->getResolveTest("c:\\test\\", "/cwd/dir/to/test/", "/cwd" );
        $this->assertSame( $resolve, $resolve->resolve("dir/to", TRUE) );
    }

}

?>