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

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_quoter_section_quoted extends PHPUnit_Framework_TestCase
{
    public function testConstruct ()
    {
        $section = new \cPHP\Quoter\Section\Quoted("snip", '"', "'");
        $this->assertSame( "snip", $section->getContent() );
        $this->assertSame( '"', $section->getOpenQuote() );
        $this->assertSame( "'", $section->getCloseQuote() );
    }

    public function testIsQuoted ()
    {
        $section = new \cPHP\Quoter\Section\Quoted(null, '"', "'");

        $this->assertTrue( $section->isQuoted() );
    }

    public function testSetOpenQuote ()
    {
        $section = new \cPHP\Quoter\Section\Quoted(null, '"', "'");

        $this->assertEquals( '"', $section->getOpenQuote() );

        $this->assertSame( $section, $section->setOpenQuote("newQuote") );

        $this->assertEquals( 'newQuote', $section->getOpenQuote() );

        $this->assertSame( $section, $section->setOpenQuote(null) );

        $this->assertNull( $section->getOpenQuote() );
    }

    public function testClearOpenQuote ()
    {
        $section = new \cPHP\Quoter\Section\Quoted(null, '"', "'");

        $this->assertEquals( '"', $section->getOpenQuote() );

        $this->assertSame( $section, $section->clearOpenQuote() );

        $this->assertNull( $section->getOpenQuote() );
    }

    public function testOpenQuoteExists ()
    {
        $section = new \cPHP\Quoter\Section\Quoted(null, '"', "'");

        $this->assertTrue( $section->openQuoteExists() );

        $section->clearOpenQuote();

        $this->assertFalse( $section->openQuoteExists() );

        $section->setOpenQuote("newQuote");

        $this->assertTrue( $section->openQuoteExists() );
    }

    public function testSetCloseQuote ()
    {
        $section = new \cPHP\Quoter\Section\Quoted(null, '"', "'");

        $this->assertEquals( "'", $section->getCloseQuote() );

        $this->assertSame( $section, $section->setCloseQuote("newQuote") );

        $this->assertEquals( 'newQuote', $section->getCloseQuote() );

        $this->assertSame( $section, $section->setCloseQuote(null) );

        $this->assertNull( $section->getCloseQuote() );
    }

    public function testClearCloseQuote ()
    {
        $section = new \cPHP\Quoter\Section\Quoted(null, '"', "'");

        $this->assertEquals( "'", $section->getCloseQuote() );

        $this->assertSame( $section, $section->clearCloseQuote() );

        $this->assertNull( $section->getCloseQuote() );
    }

    public function testCloseQuoteExists ()
    {
        $section = new \cPHP\Quoter\Section\Quoted(null, '"', "'");

        $this->assertTrue( $section->closeQuoteExists() );

        $section->clearCloseQuote();

        $this->assertFalse( $section->closeQuoteExists() );

        $section->setCloseQuote("newQuote");

        $this->assertTrue( $section->closeQuoteExists() );
    }

    public function testToString ()
    {
        $section = new \cPHP\Quoter\Section\Quoted("snip", "(", ")");

        $this->assertSame( "(snip)", $section->__toString() );
        $this->assertSame( "(snip)", "$section" );
    }

}

?>