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
class classes_page_injector extends PHPUnit_Framework_TestCase
{

    public function testPageAccessors ()
    {
        $page = new \r8\Page\Injector(
                $this->getMock('r8\Template', array('display'))
            );

        $sub1 = $this->getMock('r8\iface\Page', array('getContent'));
        $sub2 = $this->getMock('r8\iface\Page', array('getContent'));

        $this->assertEquals( array(), $page->getPages() );

        $this->assertSame( $page, $page->addPage('one', $sub1) );
        $this->assertSame( array('one' => $sub1), $page->getPages() );

        $this->assertSame( $page, $page->addPage('two', $sub2) );
        $this->assertSame(
                array('one' => $sub1, 'two' => $sub2),
                $page->getPages()
            );

        $this->assertSame( $page, $page->addPage('three', $sub1) );
        $this->assertSame(
                array('one' => $sub1, 'two' => $sub2, 'three' => $sub1),
                $page->getPages()
            );

        $this->assertSame( $page, $page->addPage('one', $sub2) );
        $this->assertSame(
                array('one' => $sub2, 'two' => $sub2, 'three' => $sub1),
                $page->getPages()
            );

        $this->assertSame( $page, $page->clearPages() );
        $this->assertSame( array(), $page->getPages() );
    }

    public function testGetContent ()
    {
        $tpl = $this->getMock('r8\Template', array('display', 'set'));

        $page = new \r8\Page\Injector( $tpl );

        $context = new \r8\Page\Context;

        // Set up a page and template that will be injected
        $tpl1 = new \r8\Template\Blank;
        $sub1 = $this->getMock('r8\iface\Page', array('getContent'));
        $sub1->expects( $this->once() )
            ->method('getContent')
            ->with( $this->equalTo($context) )
            ->will( $this->returnValue($tpl1) );

        // Set up a page and template that will be injected
        $tpl2 = new \r8\Template\Blank;
        $sub2 = $this->getMock('r8\iface\Page', array('getContent'));
        $sub2->expects( $this->once() )
            ->method('getContent')
            ->with( $this->equalTo($context) )
            ->will( $this->returnValue($tpl2) );


        // Add the sub pages to the Injector
        $page->addPage('One', $sub1);
        $page->addPage('Two', $sub2);


        // Set up the input template so it expects the injected templates
        $tpl->expects( $this->at(0) )
            ->method('set')
            ->with( $this->equalTo('One'), $this->equalTo($tpl1) );

        $tpl->expects( $this->at(1) )
            ->method('set')
            ->with( $this->equalTo('Two'), $this->equalTo($tpl2) );

        // Run the test
        $this->assertSame( $tpl, $page->getContent( $context ) );
    }

    public function testGetContent_empty ()
    {
        $tpl = $this->getMock('r8\Template', array('display', 'set'));

        $page = new \r8\Page\Injector( $tpl );

        $tpl->expects( $this->never() )
            ->method('set');

        // Run the test
        $this->assertSame( $tpl, $page->getContent( new \r8\Page\Context ) );
    }

}

?>