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
class classes_Finder_Terminus extends PHPUnit_Framework_TestCase
{

    public function testFind_unfound ()
    {
        $tracker = $this->getMock('\r8\Finder\Tracker');
        $tracker->expects( $this->once() )
            ->method( "test" )
            ->with( $this->equalTo("/base/"), $this->equalTo("file.ext") )
            ->will( $this->returnValue( FALSE ) );

        $find = new \r8\Finder\Terminus;

        $this->assertNull( $find->find( $tracker, "/base/", "file.ext" ) );
    }

    public function testFind_found ()
    {
        $tracker = $this->getMock('\r8\Finder\Tracker');
        $tracker->expects( $this->once() )
            ->method( "test" )
            ->with( $this->equalTo("/base/dir"), $this->equalTo("filename") )
            ->will( $this->returnValue( TRUE ) );

        $find = new \r8\Finder\Terminus;

        $result = $find->find( $tracker, "/base/dir", "filename" );

        $this->assertThat( $result, $this->isInstanceOf( '\r8\Finder\Result' ) );
        $this->assertSame( "/base/dir", $result->getBase() );
        $this->assertSame( "filename", $result->getPath() );
    }

}

?>