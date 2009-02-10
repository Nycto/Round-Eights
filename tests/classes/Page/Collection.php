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

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_page_collection extends PHPUnit_Framework_TestCase
{

    public function testPageAccessors ()
    {
        $page = new \cPHP\Page\Collection;

        $sub1 = $this->getMock('cPHP\iface\Page', array('render', 'display'));
        $sub2 = $this->getMock('cPHP\iface\Page', array('render', 'display'));


        $this->assertEquals( new cPHP\Ary, $page->getPages() );


        $this->assertSame( $page, $page->addPage($sub1) );
        $this->assertEquals(
                new cPHP\Ary(array($sub1)),
                $page->getPages()
            );
        $this->assertSame( $sub1, $page->getPages()->offsetGet(0) );


        $this->assertSame( $page, $page->addPage($sub2) );
        $this->assertEquals(
                new cPHP\Ary(array($sub1, $sub2)),
                $page->getPages()
            );
        $this->assertSame( $sub1, $page->getPages()->offsetGet(0) );
        $this->assertSame( $sub2, $page->getPages()->offsetGet(1) );


        $this->assertSame( $page, $page->addPage($sub1) );
        $this->assertEquals(
                new cPHP\Ary(array($sub1, $sub2, $sub1)),
                $page->getPages()
            );
        $this->assertSame( $sub1, $page->getPages()->offsetGet(0) );
        $this->assertSame( $sub2, $page->getPages()->offsetGet(1) );
        $this->assertSame( $sub1, $page->getPages()->offsetGet(2) );


        $this->assertSame( $page, $page->clearPages() );
        $this->assertEquals( new cPHP\Ary, $page->getPages() );
    }

    public function testCreateContent_empty ()
    {
        $page = new \cPHP\Page\Collection;

        $result = $page->getContent();
        $this->assertThat(
                $result,
                $this->isInstanceOf('cPHP\Template\Collection')
            );
        $this->assertEquals(
                new \cPHP\Ary,
                $result->getTemplates()
            );
    }

    public function testCreateContent_templates ()
    {
        $tpl1 = $this->getMock('cPHP\iface\Template', array('render', 'display', '__toString'));
        $tpl2 = $this->getMock('cPHP\iface\Template', array('render', 'display', '__toString'));


        $page = new \cPHP\Page\Collection;
        $page->addPage( new \cPHP\Page\Template($tpl1) );
        $page->addPage( new \cPHP\Page\Template($tpl2) );


        $result = $page->getContent();
        $this->assertThat(
                $result,
                $this->isInstanceOf('cPHP\Template\Collection')
            );
        $this->assertEquals(
                new \cPHP\Ary(array($tpl1, $tpl2)),
                $result->getTemplates()
            );

        $this->assertSame( $tpl1, $result->getTemplates()->offsetGet(0) );
        $this->assertSame( $tpl2, $result->getTemplates()->offsetGet(1) );
    }

    public function testCreateContent_string ()
    {
        $sub = $this->getMock('cPHP\iface\Page', array('render', 'display'));

        $sub->expects( $this->once() )
            ->method( 'render' )
            ->will( $this->returnValue('Chunk of data') );

        $page = new \cPHP\Page\Collection;
        $page->addPage( $sub );


        $result = $page->getContent();
        $this->assertThat(
                $result,
                $this->isInstanceOf('cPHP\Template\Collection')
            );
        $this->assertSame( 1, $result->getTemplates()->count() );
        $this->assertThat(
                $result->getTemplates()->offsetGet(0),
                $this->isInstanceOf('cPHP\Template\Raw')
            );
        $this->assertSame(
                "Chunk of data",
                $result->getTemplates()->offsetGet(0)->getContent()
            );
    }

}

?>