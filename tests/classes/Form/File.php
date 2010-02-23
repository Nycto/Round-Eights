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
class classes_Form_File extends PHPUnit_Framework_TestCase
{

    public function testGetRawValue_noFile ()
    {
        $validator = $this->getMock('\r8\Validator\FileUpload');

        $files = $this->getMock('\r8\Input\Files');
        $files->expects( $this->once() )
            ->method("getFile")
            ->with( $this->equalTo("fld") )
            ->will( $this->returnValue(NULL) );

        $field = new \r8\Form\File( "fld", $validator, $files );

        $this->assertNull( $field->getRawValue() );
    }

    public function testGetRawValue_withFile ()
    {
        $validator = $this->getMock('\r8\Validator\FileUpload');

        $file = $this->getMock('\r8\Input\File', array(), array(), '', FALSE);

        $files = $this->getMock('\r8\Input\Files');
        $files->expects( $this->once() )
            ->method("getFile")
            ->with( $this->equalTo("fld") )
            ->will( $this->returnValue( $file ) );

        $field = new \r8\Form\File( "fld", $validator, $files );

        $this->assertSame( $file, $field->getRawValue() );
    }

    public function testValidate_invalid ()
    {
        $file = $this->getMock('\r8\Input\File', array(), array(), '', FALSE);

        $result = $this->getMock('\r8\Validator\Result', array(), array(), '', FALSE);
        $result->expects( $this->once() )
            ->method( "isValid" )
            ->will( $this->returnValue(FALSE) );

        $validator = $this->getMock('\r8\Validator\FileUpload');
        $validator->expects( $this->once() )
            ->method('validate')
            ->with( $this->equalTo($file) )
            ->will( $this->returnValue($result) );

        $upperValid = $this->getMock('\r8\Validator\FileUpload');
        $upperValid->expects( $this->never() )->method('validate');

        $files = $this->getMock('\r8\Input\Files');
        $files->expects( $this->once() )
            ->method("getFile")
            ->with( $this->equalTo("fld") )
            ->will( $this->returnValue( $file ) );

        $field = new \r8\Form\File( "fld", $validator, $files );
        $field->setValidator( $upperValid );

        $this->assertSame( $result, $field->validate() );
    }

    public function testValidate_valid ()
    {
        $file = $this->getMock('\r8\Input\File', array(), array(), '', FALSE);


        $result = $this->getMock('\r8\Validator\Result', array(), array(), '', FALSE);
        $result->expects( $this->once() )
            ->method( "isValid" )
            ->will( $this->returnValue(TRUE) );

        $validator = $this->getMock('\r8\Validator\FileUpload');
        $validator->expects( $this->once() )
            ->method('validate')
            ->with( $this->equalTo($file) )
            ->will( $this->returnValue($result) );


        $upperResult = $this->getMock('\r8\Validator\Result', array(), array(), '', FALSE);

        $upperValid = $this->getMock('\r8\Validator\FileUpload');
        $upperValid->expects( $this->once() )
            ->method('validate')
            ->with( $this->equalTo($file) )
            ->will( $this->returnValue($upperResult) );


        $files = $this->getMock('\r8\Input\Files');
        $files->expects( $this->exactly(2) )
            ->method("getFile")
            ->with( $this->equalTo("fld") )
            ->will( $this->returnValue( $file ) );

        $field = new \r8\Form\File( "fld", $validator, $files );
        $field->setValidator( $upperValid );

        $this->assertSame( $upperResult, $field->validate() );
    }

    public function testGetTag ()
    {
        $field = new \r8\Form\File("fld");
        $field->setName("fldName");

        $tag = $field->getTag();

        $this->assertThat( $tag, $this->isInstanceOf("r8\HTML\Tag") );
        $this->assertSame( "input", $tag->getTag() );

        $this->assertTrue( isset($tag['name']) );
        $this->assertSame( "fldName", $tag['name'] );

        $this->assertFalse( isset($tag['value']) );

        $this->assertTrue( isset($tag['type']) );
        $this->assertSame( "file", $tag['type'] );
    }

}

?>