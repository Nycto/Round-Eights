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
class classes_Validator_FileUpload extends PHPUnit_Framework_TestCase
{

    public function testProcess_NonFile ()
    {
        $valid = new \r8\Validator\FileUpload;

        $result = $valid->validate( "A String" );

        $this->assertFalse( $result->isValid() );
        $this->assertSame(
            array( "No file was uploaded" ),
            $result->getErrors()
        );
    }

    public function testProcess_Invalid ()
    {
        $valid = new \r8\Validator\FileUpload;

        $file = $this->getMock('\r8\Input\File', array(), array(), '', FALSE);
        $file->expects( $this->once() )
            ->method( "isValid" )
            ->will( $this->returnValue(FALSE) );
        $file->expects( $this->once() )
            ->method( "getMessage" )
            ->will( $this->returnValue("An error was encountered") );

        $result = $valid->validate( $file );

        $this->assertFalse( $result->isValid() );
        $this->assertSame(
            array( "An error was encountered" ),
            $result->getErrors()
        );
    }

    public function testProcess_Valid ()
    {
        $valid = new \r8\Validator\FileUpload;

        $file = $this->getMock('\r8\Input\File', array(), array(), '', FALSE);
        $file->expects( $this->once() )
            ->method( "isValid" )
            ->will( $this->returnValue(TRUE) );
        $file->expects( $this->never() )->method( "getMessage" );

        $result = $valid->validate( $file );

        $this->assertTrue( $result->isValid() );
    }

}

?>