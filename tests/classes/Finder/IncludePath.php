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
class classes_Finder_IncludePath extends PHPUnit_Framework_TestCase
{

    public function testFind_Unfound ()
    {
        $tracker = $this->getMock('\r8\Finder\Tracker');

        $wrapped = $this->getMock('\r8\iface\Finder');
        $wrapped->expects( $this->at(0) )
            ->method( "find" )
            ->with(
                $this->equalTo($tracker),
                $this->equalTo("first"),
                $this->equalTo("file")
            )
            ->will( $this->returnValue( FALSE ) );
        $wrapped->expects( $this->at(1) )
            ->method( "find" )
            ->with(
                $this->equalTo($tracker),
                $this->equalTo("second"),
                $this->equalTo("file")
            )
            ->will( $this->returnValue( NULL ) );

        $ext = new \r8\Finder\IncludePath( $wrapped );

        $this->iniSet("include_path", "first". \PATH_SEPARATOR ."second");

        $this->assertNull( $ext->find( $tracker, "base", "file" ) );
    }

    public function testFind_Found ()
    {
        $result = $this->getMock('\r8\Finder\Result', array(), array(), '', FALSE);

        $tracker = $this->getMock('\r8\Finder\Tracker');

        $wrapped = $this->getMock('\r8\iface\Finder');
        $wrapped->expects( $this->once() )
            ->method( "find" )
            ->with(
                $this->equalTo($tracker),
                $this->equalTo("first"),
                $this->equalTo("file")
            )
            ->will( $this->returnValue( $result ) );

        $ext = new \r8\Finder\IncludePath( $wrapped );

        $this->iniSet("include_path", "first". \PATH_SEPARATOR ."second");

        $this->assertSame( $result, $ext->find( $tracker, "base", "file" ) );
    }

}

