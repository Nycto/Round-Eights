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
class classes_page_delegator extends PHPUnit_Framework_TestCase
{

    public function testViewAccessors ()
    {
        $page = $this->getMock('cPHP\Page\Delegator', array('createContent', 'viewExists'));

        $this->assertSame( "index", $page->getView() );

        $this->assertSame( $page, $page->setView("stuff") );
        $this->assertSame( "stuff", $page->getView() );

        $this->assertSame( $page, $page->resetView() );
        $this->assertSame( "index", $page->getView() );

        $this->assertSame( $page, $page->setView(505) );
        $this->assertSame( 505, $page->getView() );

        try {
            $page->setView("");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }
    }

}

?>