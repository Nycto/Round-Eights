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

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_Exception_Finder_Missing extends PHPUnit_Framework_TestCase
{

    public function testConstruct ()
    {
        $tracker = $this->getMock('\r8\Finder\Tracker');
        $tracker->expects( $this->once() )
            ->method( "getTested" )
            ->will( $this->returnValue( array(
                "/path1", "/path2", "/path1", "/path3"
            ) ) );


        $err = new \r8\Exception\Finder\Missing(
            '/path/to/file.txt', $tracker
        );

        $this->assertEquals( "Finder was unable to locate file", $err->getMessage() );
        $this->assertEquals( 0, $err->getCode() );
        $this->assertEquals(
            array(
                "Search Path" => "/path/to/file.txt",
                "Tested Paths" => array("/path1", "/path2", "/path1", "/path3")
            ),
            $err->getData()
        );
    }

}

