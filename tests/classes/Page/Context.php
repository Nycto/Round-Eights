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
class classes_Page_Context extends PHPUnit_Framework_TestCase
{

    public function testSuppress ()
    {
        $context = new \r8\Page\Context;

        $this->assertFalse( $context->isSuppressed() );
        $this->assertSame( $context, $context->suppress() );
        $this->assertTrue( $context->isSuppressed() );
    }

    public function testRedirect ()
    {
        $context = new \r8\Page\Context;

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
        catch ( r8\Exception\Data $err ) {
            $this->assertSame( "URL must not contain spaces", $err->getMessage() );
        }
    }

    public function testInterrupt ()
    {
        $context = new \r8\Page\Context;

        try {
            $context->interrupt();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Page\Interrupt $err ) {
            $this->assertSame( "Page execution interrupted", $err->getMessage() );
        }
    }

}

?>