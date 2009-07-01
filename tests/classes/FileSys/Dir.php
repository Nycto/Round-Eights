<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of raindropPHP.
 *
 * raindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * raindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with raindropPHP. If not, see <http://www.raindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * Unit test for running both file test suites
 */
class classes_filesys_dir
{

    public static function suite()
    {
        $suite = new h2o_Base_TestSuite;
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
        $dir = new \h2o\FileSys\Dir;

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
        $dir = new \h2o\FileSys\Dir;

        $dir->setPath( __DIR__ );
        $this->assertTrue( $dir->exists() );

        $dir->setPath( __FILE__ );
        $this->assertFalse( $dir->exists() );

        $dir->setPath( "/this/is/not/a/real/path" );
        $this->assertFalse( $dir->exists() );
    }

    public function testGetBasename ()
    {
        $dir = new \h2o\FileSys\Dir;
        $this->assertNull( $dir->getBasename() );

        $dir->setPath( "/dir/to/path" );
        $this->assertSame( "path", $dir->getBasename() );

        $dir->setDir( "/This/is/aPath/" );
        $this->assertSame( "aPath", $dir->getBasename() );

        $dir->clearDir();
        $this->assertNull( $dir->getBasename() );
    }

    public function testGetUniqueFile ()
    {
        $dir = new \h2o\FileSys\Dir( sys_get_temp_dir() );

        // No settings
        $file = $dir->getUniqueFile();

        $this->assertThat( $file, $this->isInstanceOf("h2o\FileSys\File") );
        $this->assertSame( $dir->getRawDir(), $file->getRawDir() );
        $this->assertFalse( $file->extExists() );

        $this->assertSame( 15, strlen( $file->getFileName() ) );
        $this->assertRegExp( '/^[a-z0-9]{15}$/i', $file->getFileName() );

        $this->assertFalse( $file->exists() );
    }

    public function testGetUniqueFile_prefix ()
    {
        $dir = new \h2o\FileSys\Dir( sys_get_temp_dir() );

        $file = $dir->getUniqueFile("h2o_");

        $this->assertThat( $file, $this->isInstanceOf("h2o\FileSys\File") );
        $this->assertSame( $dir->getRawDir(), $file->getRawDir() );
        $this->assertFalse( $file->extExists() );

        $this->assertSame( 19, strlen( $file->getFileName() ) );
        $this->assertRegExp( '/^h2o_[a-z0-9]{15}$/i', $file->getFileName() );

        $this->assertFalse( $file->exists() );
    }

    public function testGetUniqueFile_ext ()
    {
        $dir = new \h2o\FileSys\Dir( sys_get_temp_dir() );

        $file = $dir->getUniqueFile( null, "php" );

        $this->assertThat( $file, $this->isInstanceOf("h2o\FileSys\File") );
        $this->assertSame( $dir->getRawDir(), $file->getRawDir() );
        $this->assertSame( "php", $file->getExt() );

        $this->assertSame( 15, strlen( $file->getFileName() ) );
        $this->assertRegExp( '/^[a-z0-9]{15}$/i', $file->getFileName() );

        $this->assertFalse( $file->exists() );
    }

    public function testGetUniqueFile_moreEntropy ()
    {
        $dir = new \h2o\FileSys\Dir( sys_get_temp_dir() );

        $file = $dir->getUniqueFile( "h2o_", "php", TRUE );

        $this->assertThat( $file, $this->isInstanceOf("h2o\FileSys\File") );
        $this->assertSame( $dir->getRawDir(), $file->getRawDir() );
        $this->assertSame( "php", $file->getExt() );

        $this->assertSame( 36, strlen( $file->getFileName() ) );
        $this->assertRegExp( '/^h2o_[a-z0-9]{32}$/i', $file->getFileName() );

        $this->assertFalse( $file->exists() );
    }

    public function testIncludeDotsAccessors ()
    {
        $dir = new \h2o\FileSys\Dir;
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
        $dir = new \h2o\FileSys\Dir("/path/to/a/dir/that/isnt/real");

        try {
            foreach( $dir AS $item ) {}
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\FileSystem\Missing $err ) {
            $this->assertSame( "Path does not exist", $err->getMessage() );
        }
    }

    public function testIteration_noRewind ()
    {
        $dir = new \h2o\FileSys\Dir("/path/to/a/dir/that/isnt/real");

        try {
            $dir->current();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\Interaction $err ) {
            $this->assertSame( "Iteration has not been rewound", $err->getMessage() );
        }

        $this->assertFalse( $dir->valid() );

        try {
            $dir->current();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\Interaction $err ) {
            $this->assertSame( "Iteration has not been rewound", $err->getMessage() );
        }

        try {
            $dir->key();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\Interaction $err ) {
            $this->assertSame( "Iteration has not been rewound", $err->getMessage() );
        }

        try {
            $dir->hasChildren();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\Interaction $err ) {
            $this->assertSame( "Iteration has not been rewound", $err->getMessage() );
        }

        try {
            $dir->getChildren();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\Interaction $err ) {
            $this->assertSame( "Iteration has not been rewound", $err->getMessage() );
        }

    }

    public function testTemp ()
    {
        $dir = \h2o\FileSys\Dir::getTemp();

        $this->assertThat( $dir, $this->isInstanceOf("h2o\FileSys\Dir") );
        $this->assertSame(
                rtrim( sys_get_temp_dir(), "/" ),
                rtrim( $dir->getPath(), "/" )
            );
    }

}

/**
 * unit tests that use temporary files/directories
 */
class classes_filesystem_dir_withData extends PHPUnit_Dir_Framework_TestCase
{

    public function testToArray ()
    {
        $dir = new \h2o\FileSys\Dir( $this->dir );

        $list = $dir->toArray();

        $this->assertSame(
                array(0, 1, 2, 3, 4, 5, 6, 7, 8),
                array_keys( $list )
            );


        $files = array();
        $dirs = array();

        foreach ( $list AS $item ) {

            if ( $item instanceof \h2o\FileSys\Dir)
                $dirs[] = $item->getBasename();

            else if ( $item instanceof \h2o\FileSys\File)
                $files[] = $item->getBasename();

        }

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

    public function testToArray_noDots ()
    {
        $dir = new \h2o\FileSys\Dir( $this->dir );
        $dir->setIncludeDots(FALSE);

        $list = $dir->toArray();

        $this->assertSame(
                array(0, 1, 2, 3, 4, 5, 6),
                array_keys( $list )
            );


        $files = array();
        $dirs = array();

        foreach ( $list AS $item ) {

            if ( $item instanceof \h2o\FileSys\Dir)
                $dirs[] = $item->getBasename();

            else if ( $item instanceof \h2o\FileSys\File)
                $files[] = $item->getBasename();

        }

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

    public function testMake ()
    {
        $dir = new \h2o\FileSys\Dir;

        try {
            $dir->make();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\Variable $err ) {
            $this->assertSame( "No Path has been set", $err->getMessage() );
        }

        $dir->setDir( $this->dir ."/this/is/a/new/dir" );

        $this->assertSame( $dir, $dir->make() );
        $this->assertTrue( is_dir($this->dir ."/this/is/a/new/dir") );

        $this->assertSame( $dir, $dir->make() );
        $this->assertTrue( is_dir($this->dir ."/this/is/a/new/dir") );
    }

    public function testMake_noPerms ()
    {
        $dir = new \h2o\FileSys\Dir( $this->dir ."/this/is/a/new/dir" );
        chmod( $this->dir, 0000 );

        try {
            $dir->make();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\FileSystem $err ) {
            $this->assertSame( "Unable to create directory", $err->getMessage() );
        }
    }

    public function testPurge ()
    {
        $dir = new \h2o\FileSys\Dir;

        try {
            $dir->purge();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\FileSystem\Missing $err ) {
            $this->assertSame( "Path does not exist", $err->getMessage() );
        }

        $dir->setDir( $this->dir );

        $this->assertSame( $dir, $dir->purge() );
        $this->assertTrue( is_dir( $this->dir) );

        $content = glob( $this->dir ."/*" );
        $this->assertSame( array(), $content );
    }

    public function testPurge_cantOpenDir ()
    {
        $dir = new \h2o\FileSys\Dir( $this->dir );
        chmod( $this->dir, 0000 );

        try {
            $dir->purge();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\FileSystem $err ) {
            $this->assertSame( "Unable to open directory", $err->getMessage() );
        }
    }

    public function testPurge_cantDeleteDir ()
    {
        $dir = new \h2o\FileSys\Dir( $this->dir );
        chmod( $this->dir ."/second", 4440 );

        try {
            $dir->purge();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\FileSystem $err ) {
            $this->assertSame( "Unable to delete path", $err->getMessage() );
        }
    }

    public function testDelete ()
    {
        $dir = new \h2o\FileSys\Dir( $this->dir ."/first" );

        $this->assertSame( $dir, $dir->delete() );
        $this->assertFalse( is_dir($dir) );

        $this->assertSame( $dir, $dir->delete() );
        $this->assertFalse( is_dir($dir) );
    }

    public function testDelete_filled ()
    {
        $dir = new \h2o\FileSys\Dir( $this->dir );

        try {
            $dir->delete();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\FileSystem $err ) {
            $this->assertSame( "Unable to delete directory", $err->getMessage() );
        }

        $this->assertTrue( is_dir($dir) );
    }

    public function testContains ()
    {
        $dir = new \h2o\FileSys\Dir;

        try {
            $dir->contains("file");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\Variable $err ) {
            $this->assertSame( "No directory has been set for this instance", $err->getMessage() );
        }

        $dir->setPath( $this->dir );

        $this->assertTrue( $dir->contains("first") );
        $this->assertTrue( $dir->contains("third/fourth/") );
        $this->assertTrue( $dir->contains("./third/fourth/") );
        $this->assertTrue( $dir->contains( new \h2o\FileSys\Dir("third") ) );

        $this->assertTrue( $dir->contains("one") );
        $this->assertTrue( $dir->contains("second/second-one") );
        $this->assertTrue( $dir->contains("third/fourth/fourth-one") );
        $this->assertTrue( $dir->contains("./one") );
        $this->assertTrue( $dir->contains( new \h2o\FileSys\File("third/fourth/fourth-one") ) );

        $this->assertTrue( $dir->contains( $this->dir ) );

        $this->assertFalse( $dir->contains("notAFile") );
        $this->assertFalse( $dir->contains("/dir/to/a/non/existant/file") );
    }

    public function testGetSubPath_noDir ()
    {
        $dir = new \h2o\FileSys\Dir;

        try {
            $dir->getSubPath("file");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\Variable $err ) {
            $this->assertSame( "No directory has been set for this instance", $err->getMessage() );
        }
    }

    public function testGetSubPath_dirs ()
    {
        $dir = new \h2o\FileSys\Dir( $this->dir );

        $result = $dir->getSubPath("first");
        $this->assertThat( $result, $this->isInstanceOf('h2o\FileSys\Dir') );
        $this->assertSame( $this->dir ."/first/", $result->getPath() );

        $result = $dir->getSubPath("third/fourth/");
        $this->assertThat( $result, $this->isInstanceOf('h2o\FileSys\Dir') );
        $this->assertSame( $this->dir ."/third/fourth/", $result->getPath() );

        $result = $dir->getSubPath("./third/fourth/");
        $this->assertThat( $result, $this->isInstanceOf('h2o\FileSys\Dir') );
        $this->assertSame( $this->dir ."/third/fourth/", $result->getPath() );

        $result = $dir->getSubPath( new \h2o\FileSys\Dir("third") );
        $this->assertThat( $result, $this->isInstanceOf('h2o\FileSys\Dir') );
        $this->assertSame( $this->dir ."/third/", $result->getPath() );

        $result = $dir->getSubPath( $this->dir );
        $this->assertThat( $result, $this->isInstanceOf('h2o\FileSys\Dir') );
        $this->assertSame( $this->dir ."/", $result->getPath() );
    }

    public function testGetSubPath_files ()
    {
        $dir = new \h2o\FileSys\Dir( $this->dir );

        $result = $dir->getSubPath("one");
        $this->assertThat( $result, $this->isInstanceOf('h2o\FileSys\File') );
        $this->assertSame( $this->dir ."/one", $result->getPath() );

        $result = $dir->getSubPath("second/second-one");
        $this->assertThat( $result, $this->isInstanceOf('h2o\FileSys\File') );
        $this->assertSame( $this->dir ."/second/second-one", $result->getPath() );

        $result = $dir->getSubPath("third/fourth/fourth-one");
        $this->assertThat( $result, $this->isInstanceOf('h2o\FileSys\File') );
        $this->assertSame( $this->dir ."/third/fourth/fourth-one", $result->getPath() );

        $result = $dir->getSubPath("./one");
        $this->assertThat( $result, $this->isInstanceOf('h2o\FileSys\File') );
        $this->assertSame( $this->dir ."/one", $result->getPath() );

        $result = $dir->getSubPath( new \h2o\FileSys\File("third/fourth/fourth-one") );
        $this->assertThat( $result, $this->isInstanceOf('h2o\FileSys\File') );
        $this->assertSame( $this->dir ."/third/fourth/fourth-one", $result->getPath() );
    }

    public function testGetSubPath_doesntExist ()
    {
        $dir = new \h2o\FileSys\Dir( $this->dir );

        $result = $dir->getSubPath("notAFile");
        $this->assertThat( $result, $this->isInstanceOf('h2o\FileSys\File') );
        $this->assertSame( $this->dir ."/notAFile", $result->getPath() );

        $result = $dir->getSubPath("/dir/to/a/non/existant/file");
        $this->assertThat( $result, $this->isInstanceOf('h2o\FileSys\File') );
        $this->assertSame( "/dir/to/a/non/existant/file", $result->getPath() );
    }

    public function testIteration ()
    {
        $dir = new \h2o\FileSys\Dir( $this->dir );

        $files = array();
        $dirs = array();
        $keys = array();
        $i = 0;

        foreach ( $dir AS $key => $item ) {

            $keys[] = $key;

            if ( $item instanceof \h2o\FileSys\Dir)
                $dirs[] = $item->getBasename();
            else if ( $item instanceof \h2o\FileSys\File)
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
        $dir = new \h2o\FileSys\Dir( $this->dir );

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

            if ( $item instanceof \h2o\FileSys\Dir)
                $dirs[] = $item->getBasename();
            else if ( $item instanceof \h2o\FileSys\File)
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
        $dir = new \h2o\FileSys\Dir( $this->dir );

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

            if ( $item instanceof \h2o\FileSys\Dir)
                $dirs[] = $item->getBasename();
            else if ( $item instanceof \h2o\FileSys\File)
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
        $dir = new \h2o\FileSys\Dir( $this->dir );
        $dir->setIncludeDots( FALSE );

        $files = array();
        $dirs = array();
        $keys = array();
        $i = 0;

        foreach ( $dir AS $key => $item ) {

            $keys[] = $key;

            if ( $item instanceof \h2o\FileSys\Dir)
                $dirs[] = $item->getBasename();
            else if ( $item instanceof \h2o\FileSys\File)
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
        $dir = new \h2o\FileSys\Dir( $this->dir );
        chmod( $dir, 0000 );

        try {
            foreach( $dir AS $item ) {}
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\FileSystem $err ) {
            $this->assertSame( "Unable to open directory for iteration", $err->getMessage() );
        }
    }

    public function testRecursiveIteration ()
    {
        $dir = new \h2o\FileSys\Dir( $this->dir );

        $files = array();
        $dirs = array();
        $keys = array();
        $i = 0;

        foreach ( new RecursiveIteratorIterator($dir) AS $key => $item ) {

            $keys[] = $key;

            if ( $item instanceof \h2o\FileSys\Dir)
                $dirs[] = $item->getBasename();
            else if ( $item instanceof \h2o\FileSys\File)
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
        $dir = new \h2o\FileSys\Dir( $this->dir );
        $dir->setIncludeDots( FALSE );

        $files = array();
        $dirs = array();
        $keys = array();
        $i = 0;

        foreach ( new RecursiveIteratorIterator($dir) AS $key => $item ) {

            $keys[] = $key;

            if ( $item instanceof \h2o\FileSys\Dir)
                $dirs[] = $item->getBasename();
            else if ( $item instanceof \h2o\FileSys\File)
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

}

?>