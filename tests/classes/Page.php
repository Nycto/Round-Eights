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

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * unit tests
 */
class classes_page extends PHPUnit_Framework_TestCase
{

    public function testgetContent_template ()
    {
        $tpl = $this->getMock(
                'cPHP\iface\Template',
                array('render', 'display', '__toString')
            );

        $page = $this->getMock('cPHP\Page', array('createContent'));

        $page->expects( $this->once() )
            ->method('createContent')
            ->will( $this->returnValue($tpl) );

        $this->assertSame( $tpl, $page->getContent() );
    }

    public function testgetContent_string ()
    {
        $page = $this->getMock('cPHP\Page', array('createContent'));

        $page->expects( $this->once() )
            ->method('createContent')
            ->will( $this->returnValue("data chunk") );

        $content = $page->getContent();

        $this->assertThat( $content, $this->isInstanceOf('cPHP\Template\Raw') );
        $this->assertSame( "data chunk", $content->getContent() );
    }

    public function testgetContent_integer ()
    {
        $page = $this->getMock('cPHP\Page', array('createContent'));

        $page->expects( $this->once() )
            ->method('createContent')
            ->will( $this->returnValue(404) );

        $content = $page->getContent();

        $this->assertThat( $content, $this->isInstanceOf('cPHP\Template\Raw') );
        $this->assertSame( "404", $content->getcontent() );
    }

    public function testgetContent_float ()
    {
        $page = $this->getMock('cPHP\Page', array('createContent'));

        $page->expects( $this->once() )
            ->method('createContent')
            ->will( $this->returnValue( 10.5 ) );

        $content = $page->getContent();

        $this->assertThat( $content, $this->isInstanceOf('cPHP\Template\Raw') );
        $this->assertSame( "10.5", $content->getcontent() );
    }

    public function testgetContent_null ()
    {
        $page = $this->getMock('cPHP\Page', array('createContent'));

        $page->expects( $this->once() )
            ->method('createContent')
            ->will( $this->returnValue( null ) );

        $content = $page->getContent();

        $this->assertThat( $content, $this->isInstanceOf('cPHP\Template\Raw') );
        $this->assertSame( "", $content->getcontent() );
    }

    public function testgetContent_boolean ()
    {
        $page = $this->getMock('cPHP\Page', array('createContent'));

        $page->expects( $this->once() )
            ->method('createContent')
            ->will( $this->returnValue( TRUE ) );

        $content = $page->getContent();

        $this->assertThat( $content, $this->isInstanceOf('cPHP\Template\Raw') );
        $this->assertSame( "1", $content->getcontent() );
    }

    public function testgetContent_toString ()
    {
        $obj = $this->getMock( 'stdClass', array('__toString') );

        $obj->expects( $this->once() )
            ->method('__toString')
            ->will( $this->returnValue("data chunk") );

        $page = $this->getMock('cPHP\Page', array('createContent'));

        $page->expects( $this->once() )
            ->method('createContent')
            ->will( $this->returnValue($obj) );

        $content = $page->getContent();

        $this->assertThat( $content, $this->isInstanceOf('cPHP\Template\Raw') );
        $this->assertSame( "data chunk", $content->getcontent() );
    }

    public function testgetContent_obj ()
    {
        $obj = new stdClass;
        $obj->data = "data chunk";

        $page = $this->getMock('cPHP\Page', array('createContent'));

        $page->expects( $this->once() )
            ->method('createContent')
            ->will( $this->returnValue($obj) );

        $content = $page->getContent();

        $this->assertThat( $content, $this->isInstanceOf('cPHP\Template\Raw') );
        $this->assertSame( "data chunk", $content->getcontent() );
    }

    public function testgetContent_array ()
    {
        $page = $this->getMock('cPHP\Page', array('createContent'));

        $page->expects( $this->once() )
            ->method('createContent')
            ->will( $this->returnValue(array("data chunk")) );

        $content = $page->getContent();

        $this->assertThat( $content, $this->isInstanceOf('cPHP\Template\Raw') );
        $this->assertSame( "data chunk", $content->getcontent() );
    }

    public function testLayoutAccessors ()
    {
        $page = $this->getMock('cPHP\Page', array('createContent'));

        $this->assertNull( $page->getLayout() );
        $this->assertFalse( $page->layoutExists() );


        $tpl = $this->getMock(
                'cPHP\Template',
                array('render', 'display', '__toString')
            );
        $this->assertSame( $page, $page->setLayout($tpl) );
        $this->assertSame( $tpl, $page->getLayout() );
        $this->assertTrue( $page->layoutExists() );


        $this->assertSame( $page, $page->clearLayout() );
        $this->assertNull( $page->getLayout() );
        $this->assertFalse( $page->layoutExists() );
    }

}

?>