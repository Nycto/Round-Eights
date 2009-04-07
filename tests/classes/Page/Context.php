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
class classes_page_context extends PHPUnit_Framework_TestCase
{

    public function testSuppress ()
    {
        $context = new \cPHP\Page\Context;

        $this->assertFalse( $context->isSuppressed() );
        $this->assertSame( $context, $context->suppress() );
        $this->assertTrue( $context->isSuppressed() );
    }

    public function testRedirect ()
    {
        $context = new \cPHP\Page\Context;

        $this->assertNull( $context->getRedirect() );
        $this->assertFalse( $context->isSuppressed() );

        $this->assertSame( $context, $context->redirect("/test.html") );
        $this->assertSame( "/test.html", $context->getRedirect() );
        $this->assertTrue( $context->isSuppressed() );

        $this->assertSame( $context, $context->redirect("http://example.com") );
        $this->assertSame( "http://example.com", $context->getRedirect() );
        $this->assertTrue( $context->isSuppressed() );

        try {
            $context->redirect("bad url");
            $this->fail("An expected exception was not thrown");
        }
        catch ( cPHP\Exception\Data $err ) {
            $this->assertSame( "URL must not contain spaces", $err->getMessage() );
        }
    }

    public function testInterrupt ()
    {
        $context = new \cPHP\Page\Context;

        try {
            $context->interrupt();
            $this->fail("An expected exception was not thrown");
        }
        catch ( cPHP\Exception\Interrupt\Page $err ) {
            $this->assertSame( "Page execution interrupted", $err->getMessage() );
        }
    }

}

?>