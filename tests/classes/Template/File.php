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

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_template_file extends PHPUnit_Framework_TestCase
{

    public function getMockTpl ()
    {
        return $this->getMock(
                'cPHP\Template\File',
                array('display')
            );
    }

    public function setUp ()
    {
        \cPHP\Template\File::clearGlobalFinder();
    }

    public function tearDown ()
    {
        \cPHP\Template\File::clearGlobalFinder();
    }

    public function testFinderAccessors ()
    {
        $tpl = $this->getMockTpl();
        $this->assertNull( $tpl->getFinder() );
        $this->assertFalse( $tpl->finderExists() );

        $finder = $this->getMock( 'cPHP\FileFinder', array('internalFind') );
        $this->assertSame( $tpl, $tpl->setFinder( $finder ) );
        $this->assertSame( $finder, $tpl->getFinder() );
        $this->assertTrue( $tpl->finderExists() );

        $finder2 = $this->getMock( 'cPHP\FileFinder', array('internalFind') );
        $this->assertSame( $tpl, $tpl->setFinder( $finder2 ) );
        $this->assertSame( $finder2, $tpl->getFinder() );
        $this->assertTrue( $tpl->finderExists() );

        $this->assertSame( $tpl, $tpl->clearFinder() );
        $this->assertNull( $tpl->getFinder() );
        $this->assertFalse( $tpl->finderExists() );
    }

    public function testGlobalFinder ()
    {
        $this->assertNull( \cPHP\Template\File::getGlobalFinder() );
        $this->assertFalse( \cPHP\Template\File::globalFinderExists() );

        $finder = $this->getMock( 'cPHP\FileFinder', array('internalFind') );
        $this->assertNull( \cPHP\Template\File::setGlobalFinder( $finder ) );
        $this->assertSame( $finder, \cPHP\Template\File::getGlobalFinder() );
        $this->assertTrue( \cPHP\Template\File::globalFinderExists() );

        $finder2 = $this->getMock( 'cPHP\FileFinder', array('internalFind') );
        $this->assertNull( \cPHP\Template\File::setGlobalFinder( $finder2 ) );
        $this->assertSame( $finder2, \cPHP\Template\File::getGlobalFinder() );
        $this->assertTrue( \cPHP\Template\File::globalFinderExists() );

        $this->assertNull( \cPHP\Template\File::clearGlobalFinder() );
        $this->assertNull( \cPHP\Template\File::getGlobalFinder() );
        $this->assertFalse( \cPHP\Template\File::globalFinderExists() );
    }

    public function testSelectFinder ()
    {
        $tpl = $this->getMockTpl();

        try {
            $tpl->selectFinder();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Variable $err ) {
            $this->assertSame("No global or instance level FileFinder has been set", $err->getMessage());
        }


        $globalFinder = $this->getMock( 'cPHP\FileFinder', array('internalFind') );
        \cPHP\Template\File::setGlobalFinder( $globalFinder );
        $this->assertSame( $globalFinder, $tpl->selectFinder() );


        $instFinder = $this->getMock( 'cPHP\FileFinder', array('internalFind') );
        $tpl->setFinder( $instFinder );
        $this->assertSame( $instFinder, $tpl->selectFinder() );


        \cPHP\Template\File::clearGlobalFinder();
        $this->assertSame( $instFinder, $tpl->selectFinder() );
    }

    public function testFileAccessors ()
    {
        $tpl = $this->getMockTpl();
        $this->assertNull( $tpl->getFile() );
        $this->assertFalse( $tpl->fileExists() );


        // Set the file from a string
        $this->assertSame( $tpl, $tpl->setFile( "/path/to/file.php" ) );
        $this->assertTrue( $tpl->fileExists() );

        $file = $tpl->getFile();
        $this->assertThat( $file, $this->isInstanceOf('\cPHP\FileSys\File') );
        $this->assertSame( "/path/to/file.php", $file->getPath() );


        // Modify the file directly and make sure it sticks
        $tpl->getFile()->setPath("/new/path.php");

        $file2 = $tpl->getFile();
        $this->assertSame( $file, $file2 );
        $this->assertSame( "/new/path.php", $file2->getPath() );


        // Set the file from an object
        $fileObj = new \cPHP\FileSys\File('/dir/tpl.php');
        $this->assertSame( $tpl, $tpl->setFile( $fileObj ) );
        $this->assertTrue( $tpl->fileExists() );
        $this->assertSame( $fileObj, $tpl->getFile() );
        $this->assertSame( '/dir/tpl.php', $tpl->getFile()->getPath() );


        // Test clearing the file
        $this->assertSame( $tpl, $tpl->clearFile() );
        $this->assertNull( $tpl->getFile() );
        $this->assertFalse( $tpl->fileExists() );
    }

    public function testFindFile ()
    {
        $tpl = $this->getMockTpl();

        try {
            $tpl->findFile();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Variable $err ) {
            $this->assertSame("No file has been set in template", $err->getMessage());
        }

        $file = new \cPHP\FileSys\File('/dir/tpl.php');
        $tpl->setFile( $file );

        try {
            $tpl->findFile();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Variable $err ) {
            $this->assertSame("No global or instance level FileFinder has been set", $err->getMessage());
        }

        $result = new \cPHP\FileSys\File('/path/to/tpl.php');

        $finder = $this->getMock( 'cPHP\FileFinder', array('internalFind', 'find') );
        $finder->expects( $this->once() )
            ->method('find')
            ->with( $this->equalTo($file) )
            ->will( $this->returnValue($result) );

        $tpl->setFinder( $finder );

        $this->assertSame( $result, $tpl->findFile() );
    }

}

?>