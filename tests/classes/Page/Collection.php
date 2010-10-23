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
class classes_page_collection extends PHPUnit_Framework_TestCase
{

    public function testPageAccessors ()
    {
        $page = new \r8\Page\Collection;

        $sub1 = $this->getMock('r8\iface\Page', array('getContent'));
        $sub2 = $this->getMock('r8\iface\Page', array('getContent'));

        $this->assertEquals( array(), $page->getPages() );

        $this->assertSame( $page, $page->addPage($sub1) );
        $this->assertSame( array($sub1), $page->getPages() );

        $this->assertSame( $page, $page->addPage($sub2) );
        $this->assertSame( array($sub1, $sub2), $page->getPages() );


        $this->assertSame( $page, $page->addPage($sub1) );
        $this->assertSame( array($sub1, $sub2, $sub1), $page->getPages() );

        $this->assertSame( $page, $page->clearPages() );
        $this->assertSame( array(), $page->getPages() );
    }

    public function testCreateContent_empty ()
    {
        $page = new \r8\Page\Collection;

        $result = $page->getContent( new \r8\Page\Context );

        $this->assertThat( $result, $this->isInstanceOf('r8\Template\Collection') );

        $this->assertEquals( array(), $result->getTemplates() );
    }

    public function testCreateContent_templates ()
    {
        $tpl1 = $this->getMock('r8\iface\Template', array('render', 'display', '__toString'));
        $tpl2 = $this->getMock('r8\iface\Template', array('render', 'display', '__toString'));


        $page = new \r8\Page\Collection;
        $page->addPage( new \r8\Page\Template($tpl1) );
        $page->addPage( new \r8\Page\Template($tpl2) );


        $result = $page->getContent( new \r8\Page\Context );
        $this->assertThat( $result, $this->isInstanceOf('r8\Template\Collection') );
        $this->assertEquals(
                array($tpl1, $tpl2),
                $result->getTemplates()
            );

        $tpls = $result->getTemplates();

        $this->assertSame( $tpl1, $tpls[0] );
        $this->assertSame( $tpl2, $tpls[1] );
    }

    public function testCreateContent_string ()
    {
        $sub = $this->getMock('r8\iface\Page', array('getContent'));

        $sub->expects( $this->once() )
            ->method( 'getContent' )
            ->will( $this->returnValue('Chunk of data') );

        $page = new \r8\Page\Collection;
        $page->addPage( $sub );

        $result = $page->getContent( new \r8\Page\Context );

        $this->assertThat( $result, $this->isInstanceOf('r8\Template\Collection') );

        $tpls = $result->getTemplates();
        $this->assertSame( 1, count( $tpls ) );
        $this->assertThat(
                $tpls[0],
                $this->isInstanceOf('r8\Template\Raw')
            );
        $this->assertSame(
                "Chunk of data",
                $tpls[0]->getContent()
            );
    }

}

