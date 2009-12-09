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
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_Input_Files extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a test file
     *
     * @return \r8\Input\File
     */
    public function getTestFile ()
    {
        return $this->getMock('\r8\Input\File', array(), array(), '', FALSE);
    }

    public function testConstruct_Flat ()
    {
        $input = array(
            "first" => $this->getTestFile(),
        	"second" => $this->getTestFile(),
        	"third" => $this->getTestFile(),
        );

        $files = new \r8\Input\Files( $input );
        $this->assertSame( $input, $files->getAllFiles() );
    }

    public function testConstruct_Multi ()
    {
        $input = array(
            "first" => array( $this->getTestFile(), $this->getTestFile() ),
        	"second" => array( $this->getTestFile(), $this->getTestFile() ),
        );

        $files = new \r8\Input\Files( $input );
        $this->assertSame( $input, $files->getAllFiles() );
    }

    public function testConstruct_Noise ()
    {
        $input = array(
            "first" => $this->getTestFile(),
            "blah",
        	"second" => array( $this->getTestFile(), "noise", $this->getTestFile() ),
        );

        $files = new \r8\Input\Files( $input );
        $this->assertSame(
            array(
                "first" => $input['first'],
                "second" => array( $input['second'][0], $input['second'][2] )
            ),
            $files->getAllFiles()
        );
    }

    public function testGetFile ()
    {
        $input = array(
            "first" => $this->getTestFile(),
        	"second" => array( $this->getTestFile(), $this->getTestFile() ),
        );
        $files = new \r8\Input\Files( $input );

        $this->assertNull( $files->getFile("zero") );
        $this->assertSame( $input["first"], $files->getFile("first") );
        $this->assertSame( $input["second"][0], $files->getFile("second") );
    }

    public function testGetFileList ()
    {
        $input = array(
            "first" => $this->getTestFile(),
        	"second" => array( $this->getTestFile(), $this->getTestFile() ),
        );
        $files = new \r8\Input\Files( $input );

        $this->assertNull( $files->getFile("zero") );
        $this->assertSame( array($input["first"]), $files->getFileList("first") );
        $this->assertSame( $input["second"], $files->getFileList("second") );
    }

}

?>