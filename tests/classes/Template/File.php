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
 * unit tests
 */
class classes_Template_File extends PHPUnit_Framework_TestCase
{

    public function getMockFinder ()
    {
        return $this->getMock('\r8\iface\Finder');
    }

    public function getMockTpl ( \r8\iface\Finder $finder = null, $file = null )
    {
        if ( empty($finder) )
            $finder = $this->getMockFinder();

        if ( empty($file) )
            $file = '/example.txt';

        return $this->getMock(
                '\r8\Template\File',
                array('display'),
                array( $finder, $file )
            );
    }

    public function testFinderAccessors ()
    {
        $finder = $this->getMockFinder();

        $tpl = $this->getMockTpl( $finder );

        $this->assertSame( $finder, $tpl->getFinder() );

        $finder = $this->getMockFinder();
        $this->assertSame( $tpl, $tpl->setFinder( $finder ) );
        $this->assertSame( $finder, $tpl->getFinder() );

        $finder2 = $this->getMockFinder();
        $this->assertSame( $tpl, $tpl->setFinder( $finder2 ) );
        $this->assertSame( $finder2, $tpl->getFinder() );
    }

    public function testFileAccessors ()
    {
        $tpl = $this->getMockTpl( null, "/file.php");

        $file = $tpl->getFile();
        $this->assertThat( $file, $this->isInstanceOf('\r8\FileSys\File') );
        $this->assertSame( "/file.php", $file->getPath() );


        // Set the file from a string
        $this->assertSame( $tpl, $tpl->setFile( "/path/to/file.php" ) );

        $file = $tpl->getFile();
        $this->assertThat( $file, $this->isInstanceOf('\r8\FileSys\File') );
        $this->assertSame( "/path/to/file.php", $file->getPath() );


        // Modify the file directly and make sure it sticks
        $tpl->getFile()->setPath("/new/path.php");

        $file2 = $tpl->getFile();
        $this->assertSame( $file, $file2 );
        $this->assertSame( "/new/path.php", $file2->getPath() );


        // Set the file from an object
        $fileObj = new \r8\FileSys\File('/dir/tpl.php');
        $this->assertSame( $tpl, $tpl->setFile( $fileObj ) );
        $this->assertSame( $fileObj, $tpl->getFile() );
        $this->assertSame( '/dir/tpl.php', $tpl->getFile()->getPath() );
    }

    public function testFindFile ()
    {
        $file = new \r8\FileSys\File('/dir/tpl.php');

        $foundFile = new \r8\FileSys\File('/path/to/tpl.php');

        $finder = $this->getMock( '\r8\iface\Finder', array('internalFind', 'find') );
        $finder->expects( $this->once() )
            ->method('find')
            ->with( $this->equalTo($file) )
            ->will( $this->returnValue($foundFile) );

        $tpl = $this->getMockTpl( $finder, $file );

        $this->assertSame( $foundFile, $tpl->findFile() );
    }

    public function testRender ()
    {
        $tpl = $this->getMockTpl();

        $tpl->expects( $this->once() )
            ->method("display")
            ->will( $this->returnCallback(function () {
                    echo "This is the output";
                }) );

        $this->assertSame("This is the output", $tpl->render());
    }

    public function testToString ()
    {
        $tpl = $this->getMockTpl();

        $tpl->expects( $this->once() )
            ->method("display")
            ->will( $this->returnCallback(function () {
                    echo "This is the output";
                }) );

        $this->assertSame("This is the output", "$tpl");
    }

}

?>