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

    public function getTestPage ()
    {
        return $this->getMock("cPHP\iface\Page", array("getContent"));
    }

    public function testPageAccessors ()
    {
        $page = $this->getTestPage();

        $root = new \cPHP\Page( $page );

        $this->assertSame( $page, $root->getPage() );
        $this->assertSame( $page, $root->getPage() );
    }

    public function testContextAccessors ()
    {
        $root = new \cPHP\Page( $this->getTestPage() );

        $context = $root->getContext();

        $this->assertThat( $context, $this->isInstanceOf("cPHP\Page\Context") );
        $this->assertSame( $context, $root->getContext() );
        $this->assertSame( $context, $root->getContext() );

        $context = $this->getMock( 'cPHP\Page\Context' );

        $this->assertSame( $root, $root->setcontext( $context ) );

        $this->assertSame( $context, $root->getContext() );
        $this->assertSame( $context, $root->getContext() );
    }

    public function testResponseAccessors ()
    {
        $root = new \cPHP\Page( $this->getTestPage() );

        $this->assertSame( \cPHP\Env::Response(), $root->getResponse() );
        $this->assertSame( \cPHP\Env::Response(), $root->getResponse() );
        $this->assertSame( \cPHP\Env::Response(), $root->getResponse() );

        $response = $this->getMock(
                'cPHP\iface\Env\Response',
                array('headersSent', 'setHeader')
            );

        $this->assertSame( $root, $root->setResponse( $response ) );

        $this->assertSame( $response, $root->getResponse() );
        $this->assertSame( $response, $root->getResponse() );
        $this->assertSame( $response, $root->getResponse() );
    }

    public function testGetTemplate_standard ()
    {
        $tpl = $this->getMock('cPHP\iface\Template', array('render', 'display', '__toString'));

        $page = $this->getTestPage();

        $root = new \cPHP\Page( $page );

        $context = $root->getContext();

        $page->expects( $this->once() )
            ->method("getContent")
            ->with( $this->equalTo($context) )
            ->will( $this->returnValue($tpl) );

        $this->assertSame( $tpl, $root->getTemplate() );

    }

    public function testGetTemplate_suppress ()
    {
        $tpl = $this->getMock('cPHP\iface\Template', array('render', 'display', '__toString'));

        $page = $this->getTestPage();

        $root = new \cPHP\Page( $page );

        $context = $root->getContext();
        $context->suppress();

        $page->expects( $this->once() )
            ->method("getContent")
            ->with( $this->equalTo($context) )
            ->will( $this->returnValue($tpl) );

        $result = $root->getTemplate();

        $this->assertNotSame( $tpl, $result );

        $this->assertThat( $result, $this->isInstanceOf("cPHP\Template\Blank") );
    }

    public function testGetTemplate_interrupt ()
    {
        $page = $this->getTestPage();

        $root = new \cPHP\Page( $page );

        $page->expects( $this->once() )
            ->method("getContent")
            ->with( $this->isInstanceOf('cPHP\Page\Context') )
            ->will( $this->throwException(
                    new \cPHP\Exception\Interrupt\Page
                ) );

        $result = $root->getTemplate();

        $this->assertThat( $result, $this->isInstanceOf("cPHP\Template\Blank") );

        $this->assertTrue( $root->getContext()->isSuppressed() );
    }

    public function testGetTemplate_redirect ()
    {
        $tpl = $this->getMock('cPHP\iface\Template', array('render', 'display', '__toString'));

        $page = $this->getTestPage();

        $context = $this->getMock('cPHP\Page\Context', array('getRedirect'));
        $context->expects( $this->once() )
            ->method('getRedirect')
            ->will( $this->returnValue('http://www.example.com') );

        $response = $this->getMock(
                'cPHP\iface\Env\Response',
                array('headersSent', 'setHeader')
            );
        $response->expects( $this->once() )
            ->method('setHeader')
            ->with( $this->equalTo('Location: http://www.example.com') );

        $root = new \cPHP\Page( $page );
        $root->setContext( $context );
        $root->setResponse( $response );

        $page->expects( $this->once() )
            ->method("getContent")
            ->with( $this->isInstanceOf('cPHP\Page\Context') )
            ->will( $this->returnValue($tpl) );

        $this->assertSame($tpl, $root->getTemplate());
    }

    public function testDisplay ()
    {
        $tpl = $this->getMock('cPHP\iface\Template', array('render', 'display', '__toString'));

        $tpl->expects( $this->once() )
            ->method('display');

        $root = $this->getMock(
                'cPHP\Page',
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