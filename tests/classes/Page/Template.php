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
class classes_page_template extends PHPUnit_Framework_TestCase
{

    public function testTemplateAccessors ()
    {
        $page = new \cPHP\Page\Template;

        $this->assertNull( $page->getTemplate() );
        $this->assertFalse( $page->templateExists() );

        $tpl = $this->getMock(
                'cPHP\iface\Template',
                array('render', 'display', '__toString')
            );

        $this->assertSame( $page, $page->setTemplate($tpl) );
        $this->assertSame( $tpl, $page->getTemplate() );
        $this->assertTrue( $page->templateExists() );

        $this->assertSame( $page, $page->clearTemplate() );
        $this->assertNull( $page->getTemplate() );
        $this->assertFalse( $page->templateExists() );
    }

    public function testConstructor ()
    {
        $tpl = $this->getMock(
                'cPHP\iface\Template',
                array('render', 'display', '__toString')
            );

        $page = new \cPHP\Page\Template( $tpl );

        $this->assertSame( $page, $page->setTemplate($tpl) );
        $this->assertSame( $tpl, $page->getTemplate() );
        $this->assertTrue( $page->templateExists() );
    }

    public function testGetContent_empty ()
    {
        $page = new \cPHP\Page\Template;

        $this->assertThat(
                $page->getContent(),
                $this->isInstanceOf('cPHP\Template\Raw')
            );

        $this->assertNull( $page->getContent()->getContent() );
    }

    public function testGetContent_data ()
    {
        $tpl = $this->getMock(
                'cPHP\iface\Template',
                array('render', 'display', '__toString')
            );

        $page = new \cPHP\Page\Template( $tpl );

        $this->assertSame( $tpl, $page->getContent() );
    }

}

?>