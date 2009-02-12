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
class classes_page_manager extends PHPUnit_Framework_TestCase
{

    public function testSetSubPage ()
    {
        $mgr = new \cPHP\Page\Manager;
        $this->assertEquals( new \cPHP\Ary, $mgr->getSubPages() );


        $page1 = $this->getMock('\cPHP\iface\Page', array('display', 'render', '__toString'));

        $this->assertSame( $mgr, $mgr->setSubPage('first', $page1) );
        $result = $mgr->getSubPages();
        $this->assertEquals(
                new \cPHP\Ary(array('first' => $page1) ),
                $result
            );
        $this->assertSame( $page1, $result['first']);


        $page2 = $this->getMock('\cPHP\iface\Page', array('display', 'render', '__toString'));

        $this->assertSame( $mgr, $mgr->setSubPage('second', $page2) );
        $result = $mgr->getSubPages();
        $this->assertEquals(
                new \cPHP\Ary(array('first' => $page1, 'second' => $page2) ),
                $result
            );
        $this->assertSame( $page1, $result['first']);
        $this->assertSame( $page2, $result['second']);


        // Add the same page twice
        $this->assertSame( $mgr, $mgr->setSubPage('third', $page1) );
        $result = $mgr->getSubPages();
        $this->assertEquals(
                new \cPHP\Ary(array('first' => $page1, 'second' => $page2, 'third' => $page1) ),
                $result
            );
        $this->assertSame( $page1, $result['first']);
        $this->assertSame( $page2, $result['second']);
        $this->assertSame( $page1, $result['third']);


        // Overwrite an existing index
        $this->assertSame( $mgr, $mgr->setSubPage('first', $page2) );
        $result = $mgr->getSubPages();
        $this->assertEquals(
                new \cPHP\Ary(array('first' => $page2, 'second' => $page2, 'third' => $page1) ),
                $result
            );
        $this->assertSame( $page2, $result['first']);
        $this->assertSame( $page2, $result['second']);
        $this->assertSame( $page1, $result['third']);


        try {
            $mgr->setSubPage('', $page1);
            $this->fail('An expected exception was not thrown');
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }
    }

    public function testGetSubPage ()
    {
        $mgr = new \cPHP\Page\Manager;

        $this->assertNull( $mgr->getSubPage('first') );
        $this->assertNull( $mgr->getSubPage('second') );

        $page1 = $this->getMock('\cPHP\iface\Page', array('display', 'render', '__toString'));
        $mgr->setSubPage('first', $page1);

        $this->assertSame( $page1, $mgr->getSubPage('first') );
        $this->assertNull( $mgr->getSubPage('second') );

        $page2 = $this->getMock('\cPHP\iface\Page', array('display', 'render', '__toString'));
        $mgr->setSubPage('second', $page2);

        $this->assertSame( $page1, $mgr->getSubPage('first') );
        $this->assertSame( $page2, $mgr->getSubPage('second') );
    }

    public function testSubPageExists ()
    {
        $mgr = new \cPHP\Page\Manager;

        $this->assertFalse( $mgr->subPageExists('first') );
        $this->assertFalse( $mgr->subPageExists('second') );

        $page1 = $this->getMock('\cPHP\iface\Page', array('display', 'render', '__toString'));
        $mgr->setSubPage('first', $page1);

        $this->assertTrue( $mgr->subPageExists('first') );
        $this->assertFalse( $mgr->subPageExists('second') );

        $page2 = $this->getMock('\cPHP\iface\Page', array('display', 'render', '__toString'));
        $mgr->setSubPage('second', $page2);

        $this->assertTrue( $mgr->subPageExists('first') );
        $this->assertTrue( $mgr->subPageExists('second') );
    }

    public function testRemoveSubPage ()
    {
        $mgr = new \cPHP\Page\Manager;

        $page1 = $this->getMock('\cPHP\iface\Page', array('display', 'render', '__toString'));
        $mgr->setSubPage('first', $page1);

        $page2 = $this->getMock('\cPHP\iface\Page', array('display', 'render', '__toString'));
        $mgr->setSubPage('second', $page2);

        $this->assertTrue( $mgr->subPageExists('first') );
        $this->assertTrue( $mgr->subPageExists('second') );

        $this->assertSame( $mgr, $mgr->removeSubPage('first') );

        $this->assertFalse( $mgr->subPageExists('first') );
        $this->assertTrue( $mgr->subPageExists('second') );

        $this->assertSame( $mgr, $mgr->removeSubPage('second') );

        $this->assertFalse( $mgr->subPageExists('first') );
        $this->assertFalse( $mgr->subPageExists('second') );
    }

    public function testClearSubPages ()
    {
        $mgr = new \cPHP\Page\Manager;

        $page1 = $this->getMock('\cPHP\iface\Page', array('display', 'render', '__toString'));
        $mgr->setSubPage('first', $page1);

        $page2 = $this->getMock('\cPHP\iface\Page', array('display', 'render', '__toString'));
        $mgr->setSubPage('second', $page2);

        $this->assertEquals(
                new \cPHP\Ary(array('first' => $page1, 'second' => $page2) ),
                $mgr->getSubPages()
            );

        $this->assertSame( $mgr, $mgr->clearSubPages() );

        $this->assertEquals( new \cPHP\Ary, $mgr->getSubPages() );
    }

    public function testViewExists ()
    {
        $mgr = new \cPHP\Page\Manager;
        $mgr->setView('first');

        $this->assertFalse( $mgr->viewExists() );

        $page1 = $this->getMock('\cPHP\iface\Page', array('display', 'render', '__toString'));
        $mgr->setSubPage('first', $page1);

        $this->assertTrue( $mgr->viewExists() );

        $page2 = $this->getMock('\cPHP\iface\Page', array('display', 'render', '__toString'));
        $mgr->setSubPage('second', $page2);

        $this->assertTrue( $mgr->viewExists() );

        $mgr->setView('second');

        $this->assertTrue( $mgr->viewExists() );

        $mgr->setView('index');

        $this->assertFalse( $mgr->viewExists() );
    }

}

?>