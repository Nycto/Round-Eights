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

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * unit tests
 */
class classes_Page extends PHPUnit_Framework_TestCase
{

    public function getTestPage ()
    {
        return $this->getMock("r8\iface\Page", array("getContent"));
    }

    public function testPageAccessors ()
    {
        $page = $this->getTestPage();

        $root = new \r8\Page( $page );

        $this->assertSame( $page, $root->getPage() );
        $this->assertSame( $page, $root->getPage() );
    }

    public function testContextAccessors ()
    {
        $root = new \r8\Page( $this->getTestPage() );

        $context = $root->getContext();

        $this->assertThat( $context, $this->isInstanceOf("r8\Page\Context") );
        $this->assertSame( $context, $root->getContext() );
        $this->assertSame( $context, $root->getContext() );

        $context = $this->getMock( 'r8\Page\Context' );

        $this->assertSame( $root, $root->setcontext( $context ) );

        $this->assertSame( $context, $root->getContext() );
        $this->assertSame( $context, $root->getContext() );
    }

    public function testResponseAccessors ()
    {
        $root = new \r8\Page( $this->getTestPage() );

        $this->assertSame( \r8\Env::Response(), $root->getResponse() );
        $this->assertSame( \r8\Env::Response(), $root->getResponse() );
        $this->assertSame( \r8\Env::Response(), $root->getResponse() );

        $response = $this->getMock(
                'r8\iface\Env\Response',
                array('headersSent', 'setHeader')
            );

        $this->assertSame( $root, $root->setResponse( $response ) );

        $this->assertSame( $response, $root->getResponse() );
        $this->assertSame( $response, $root->getResponse() );
        $this->assertSame( $response, $root->getResponse() );
    }

    public function testGetTemplate_standard ()
    {
        $tpl = $this->getMock('r8\iface\Template', array('render', 'display', '__toString'));

        $page = $this->getTestPage();

        $root = new \r8\Page( $page );

        $context = $root->getContext();

        $page->expects( $this->once() )
            ->method("getContent")
            ->with( $this->equalTo($context) )
            ->will( $this->returnValue($tpl) );

        $this->assertSame( $tpl, $root->getTemplate() );

    }

    public function testGetTemplate_suppress ()
    {
        $tpl = $this->getMock('r8\iface\Template', array('render', 'display', '__toString'));

        $page = $this->getTestPage();

        $root = new \r8\Page( $page );

        $context = $root->getContext();
        $context->suppress();

        $page->expects( $this->once() )
            ->method("getContent")
            ->with( $this->equalTo($context) )
            ->will( $this->returnValue($tpl) );

        $result = $root->getTemplate();

        $this->assertNotSame( $tpl, $result );

        $this->assertThat( $result, $this->isInstanceOf("r8\Template\Blank") );
    }

    public function testGetTemplate_interrupt ()
    {
        $page = $this->getTestPage();

        $root = new \r8\Page( $page );

        $page->expects( $this->once() )
            ->method("getContent")
            ->with( $this->isInstanceOf('r8\Page\Context') )
            ->will( $this->throwException(
                    new \r8\Page\Interrupt
                ) );

        $result = $root->getTemplate();

        $this->assertThat( $result, $this->isInstanceOf("r8\Template\Blank") );

        $this->assertTrue( $root->getContext()->isSuppressed() );
    }

    public function testGetTemplate_redirect ()
    {
        $tpl = $this->getMock('r8\iface\Template', array('render', 'display', '__toString'));

        $page = $this->getTestPage();

        $context = $this->getMock('r8\Page\Context', array('getRedirect'));
        $context->expects( $this->once() )
            ->method('getRedirect')
            ->will( $this->returnValue('http://www.example.com') );

        $response = $this->getMock(
                'r8\iface\Env\Response',
                array('headersSent', 'setHeader')
            );
        $response->expects( $this->once() )
            ->method('setHeader')
            ->with( $this->equalTo('Location: http://www.example.com') );

        $root = new \r8\Page( $page );
        $root->setContext( $context );
        $root->setResponse( $response );

        $page->expects( $this->once() )
            ->method("getContent")
            ->with( $this->isInstanceOf('r8\Page\Context') )
            ->will( $this->returnValue($tpl) );

        $this->assertSame($tpl, $root->getTemplate());
    }

    public function testDisplay ()
    {
        $tpl = $this->getMock('r8\iface\Template', array('render', 'display', '__toString'));

        $tpl->expects( $this->once() )
            ->method('display');

        $root = $this->getMock(
                'r8\Page',
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