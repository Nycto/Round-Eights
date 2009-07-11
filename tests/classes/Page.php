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
 * @author James Frasca <James@RaindropPHP.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * unit tests
 */
class classes_page extends PHPUnit_Framework_TestCase
{

    public function getTestPage ()
    {
        return $this->getMock("h2o\iface\Page", array("getContent"));
    }

    public function testPageAccessors ()
    {
        $page = $this->getTestPage();

        $root = new \h2o\Page( $page );

        $this->assertSame( $page, $root->getPage() );
        $this->assertSame( $page, $root->getPage() );
    }

    public function testContextAccessors ()
    {
        $root = new \h2o\Page( $this->getTestPage() );

        $context = $root->getContext();

        $this->assertThat( $context, $this->isInstanceOf("h2o\Page\Context") );
        $this->assertSame( $context, $root->getContext() );
        $this->assertSame( $context, $root->getContext() );

        $context = $this->getMock( 'h2o\Page\Context' );

        $this->assertSame( $root, $root->setcontext( $context ) );

        $this->assertSame( $context, $root->getContext() );
        $this->assertSame( $context, $root->getContext() );
    }

    public function testResponseAccessors ()
    {
        $root = new \h2o\Page( $this->getTestPage() );

        $this->assertSame( \h2o\Env::Response(), $root->getResponse() );
        $this->assertSame( \h2o\Env::Response(), $root->getResponse() );
        $this->assertSame( \h2o\Env::Response(), $root->getResponse() );

        $response = $this->getMock(
                'h2o\iface\Env\Response',
                array('headersSent', 'setHeader')
            );

        $this->assertSame( $root, $root->setResponse( $response ) );

        $this->assertSame( $response, $root->getResponse() );
        $this->assertSame( $response, $root->getResponse() );
        $this->assertSame( $response, $root->getResponse() );
    }

    public function testGetTemplate_standard ()
    {
        $tpl = $this->getMock('h2o\iface\Template', array('render', 'display', '__toString'));

        $page = $this->getTestPage();

        $root = new \h2o\Page( $page );

        $context = $root->getContext();

        $page->expects( $this->once() )
            ->method("getContent")
            ->with( $this->equalTo($context) )
            ->will( $this->returnValue($tpl) );

        $this->assertSame( $tpl, $root->getTemplate() );

    }

    public function testGetTemplate_suppress ()
    {
        $tpl = $this->getMock('h2o\iface\Template', array('render', 'display', '__toString'));

        $page = $this->getTestPage();

        $root = new \h2o\Page( $page );

        $context = $root->getContext();
        $context->suppress();

        $page->expects( $this->once() )
            ->method("getContent")
            ->with( $this->equalTo($context) )
            ->will( $this->returnValue($tpl) );

        $result = $root->getTemplate();

        $this->assertNotSame( $tpl, $result );

        $this->assertThat( $result, $this->isInstanceOf("h2o\Template\Blank") );
    }

    public function testGetTemplate_interrupt ()
    {
        $page = $this->getTestPage();

        $root = new \h2o\Page( $page );

        $page->expects( $this->once() )
            ->method("getContent")
            ->with( $this->isInstanceOf('h2o\Page\Context') )
            ->will( $this->throwException(
                    new \h2o\Exception\Interrupt\Page
                ) );

        $result = $root->getTemplate();

        $this->assertThat( $result, $this->isInstanceOf("h2o\Template\Blank") );

        $this->assertTrue( $root->getContext()->isSuppressed() );
    }

    public function testGetTemplate_redirect ()
    {
        $tpl = $this->getMock('h2o\iface\Template', array('render', 'display', '__toString'));

        $page = $this->getTestPage();

        $context = $this->getMock('h2o\Page\Context', array('getRedirect'));
        $context->expects( $this->once() )
            ->method('getRedirect')
            ->will( $this->returnValue('http://www.example.com') );

        $response = $this->getMock(
                'h2o\iface\Env\Response',
                array('headersSent', 'setHeader')
            );
        $response->expects( $this->once() )
            ->method('setHeader')
            ->with( $this->equalTo('Location: http://www.example.com') );

        $root = new \h2o\Page( $page );
        $root->setContext( $context );
        $root->setResponse( $response );

        $page->expects( $this->once() )
            ->method("getContent")
            ->with( $this->isInstanceOf('h2o\Page\Context') )
            ->will( $this->returnValue($tpl) );

        $this->assertSame($tpl, $root->getTemplate());
    }

    public function testDisplay ()
    {
        $tpl = $this->getMock('h2o\iface\Template', array('render', 'display', '__toString'));

        $tpl->expects( $this->once() )
            ->method('display');

        $root = $this->getMock(
                'h2o\Page',
                array('getTemplate'),
                array( $this->getTestPage() )
            );

        $root->expects( $this->once() )
            ->method('getTemplate')
            ->will( $this->returnValue($tpl) );

        $this->assertSame( $root, $root->display() );
    }

}

?>