<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of RaindropPHP.
 *
 * RaindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * RaindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with RaindropPHP. If not, see <http://www.RaindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@Raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_page_form extends PHPUnit_Framework_TestCase
{

    public function getTestPage ()
    {
        return $this->getMock(
                'h2o\iface\Page',
                array('getContent')
            );
    }

    public function testConstruct ()
    {
        $form = new \h2o\Form;
        $display = $this->getTestPage();
        $success = $this->getTestPage();

        $page = new \h2o\Page\Form( $form, $display, $success );

        $this->assertSame( $form, $page->getForm() );
        $this->assertSame( $display, $page->getDisplay() );
        $this->assertSame( $success, $page->getSuccess() );
    }

    public function testInitialsAccess ()
    {
        $page = new \h2o\Page\Form(
                new \h2o\Form,
                $this->getTestPage(),
                $this->getTestPage()
            );

        $this->assertSame( array(), $page->getInitials() );

        $this->assertSame( $page, $page->setInitials(array("one" => 1, "two" =>2)) );
        $this->assertSame( array("one" => 1, "two" =>2), $page->getInitials() );
    }

    public function testSourceAccess ()
    {
        $page = new \h2o\Page\Form(
                new \h2o\Form,
                $this->getTestPage(),
                $this->getTestPage()
            );

        $this->assertSame( array(), $page->getSource() );

        $this->assertSame( $page, $page->setSource(array("one" => 1, "two" =>2)) );
        $this->assertSame( array("one" => 1, "two" =>2), $page->getSource() );
    }

    public function testGetPage_display ()
    {
        $form = $this->getMock( 'h2o\Form', array('anyIn', 'fill') );

        $form->expects( $this->once() )
            ->method('anyIn')
            ->with( $this->equalTo( array() ) )
            ->will( $this->returnValue( FALSE ) );

        $form->expects( $this->once() )
            ->method('fill')
            ->with( $this->equalTo( array('field' => 'value') ) )
            ->will( $this->returnValue( $form ) );

        $display = $this->getTestPage();
        $success = $this->getTestPage();

        $page = new \h2o\Page\Form( $form, $display, $success );
        $page->setInitials( array('field' => 'value') );
        $page->setSource(array());

        $this->assertSame( $display, $page->getPage() );
    }

    public function testGetPage_invalid ()
    {
        $form = $this->getMock( 'h2o\Form', array('fill', 'anyIn', 'isValid') );

        $form->expects( $this->once() )
            ->method('anyIn')
            ->with( $this->equalTo( array('source' => 'data') ) )
            ->will( $this->returnValue( TRUE ) );

        $form->expects( $this->once() )
            ->method('fill')
            ->with( $this->equalTo( array('source' => 'data') ) )
            ->will( $this->returnValue( $form ) );

        $form->expects( $this->once() )
            ->method('isValid')
            ->will( $this->returnValue( FALSE ) );

        $display = $this->getTestPage();
        $success = $this->getTestPage();

        $page = new \h2o\Page\Form( $form, $display, $success );
        $page->setInitials( array('field' => 'value') );
        $page->setSource( array('source' => 'data') );

        $this->assertSame( $display, $page->getPage() );
    }

    public function testGetPage_valid ()
    {
        $form = $this->getMock( 'h2o\Form', array('fill', 'anyIn', 'isValid') );

        $form->expects( $this->once() )
            ->method('anyIn')
            ->with( $this->equalTo( array('source' => 'data') ) )
            ->will( $this->returnValue( TRUE ) );

        $form->expects( $this->once() )
            ->method('fill')
            ->with( $this->equalTo( array('source' => 'data') ) )
            ->will( $this->returnValue( $form ) );

        $form->expects( $this->once() )
            ->method('isValid')
            ->will( $this->returnValue( TRUE ) );

        $display = $this->getTestPage();
        $success = $this->getTestPage();

        $page = new \h2o\Page\Form( $form, $display, $success );
        $page->setInitials( array('field' => 'value') );
        $page->setSource( array('source' => 'data') );

        $this->assertSame( $success, $page->getPage() );
    }

    public function testGetContent ()
    {
        $form = new \h2o\Form;
        $display = $this->getTestPage();
        $success = $this->getTestPage();

        $page = $this->getMock(
                'h2o\Page\Form',
                array('getPage'),
                array( $form, $display, $success )
            );

        $page->expects( $this->once() )
            ->method( 'getPage' )
            ->will( $this->returnValue($display) );

        $context = new \h2o\Page\Context;

        $tpl = $this->getMock('h2o\iface\Template', array('display', 'render', '__toString'));

        $display->expects( $this->once() )
            ->method( 'getContent' )
            ->with( $this->equalTo($context) )
            ->will( $this->returnValue($tpl) );

        $this->assertSame( $tpl, $page->getContent($context) );

        //$this->assertSame( $success, $page->getPage() );

    }

}

?>