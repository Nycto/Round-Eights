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
        return $this->getMock('\r8\Finder', array(), array(), '', FALSE);
    }

    public function getMockTpl ( \r8\Finder $finder = null, $file = null )
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
        $tpl = $this->getMockTpl( null, "/file.php" );
        $this->assertSame( "/file.php", $tpl->getFile() );

        // Set the file from a string
        $this->assertSame( $tpl, $tpl->setFile( "/path/to/file.php" ) );
        $this->assertSame( "/path/to/file.php", $tpl->getFile() );

        // Set the file from an object
        $fileObj = new \r8\FileSys\File('/dir/tpl.php');
        $this->assertSame( $tpl, $tpl->setFile( $fileObj ) );
        $this->assertSame( '/dir/tpl.php', $tpl->getFile() );
    }

    public function testFindPath ()
    {
        $finder = $this->getMockFinder();
        $finder->expects( $this->once() )
            ->method('findPath')
            ->with( $this->equalTo("/search.ext") )
            ->will( $this->returnValue("/result.ext") );

        $tpl = $this->getMockTpl( $finder, "/search.ext" );

        $this->assertSame( "/result.ext", $tpl->findPath() );
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

