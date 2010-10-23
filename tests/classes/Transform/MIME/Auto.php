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

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_Transform_MIME_Auto extends PHPUnit_Framework_TestCase
{

    public function setUp ()
    {
        $this->iniSet("iconv.internal_encoding", "ISO-8859-1");
    }

    public function testTo_bare ()
    {
        $mime = new \r8\Transform\MIME\Auto;

        $this->assertSame(
                "A sample string",
                $mime->to("A sample string")
            );

        $this->assertSame(
                "=?ISO-8859-1?Q?A=09sample=09string?=",
                $mime->to("A\tsample\tstring")
            );

        $this->assertSame(
                "=?ISO-8859-1?B?CUEJc3RyaW5nCQ==?=",
                $mime->to("\tA\tstring\t")
            );
    }

    public function testTo_Customized ()
    {
        $mime = new \r8\Transform\MIME\Auto;
        $mime->setLineLength(30);
        $mime->setHeader("name");
        $mime->setOutputEncoding("UTF-8");
        $mime->setInputEncoding("UTF-8");
        $mime->setEOL( "---" );

        $this->assertSame(
                "name: A sample string that---\twraps to a new line",
                $mime->to("A sample string that wraps to a new line")
            );

        $this->assertSame(
                "name: =?UTF-8?Q?A=09sample=09?=---\t=?UTF-8?Q?string_that_wraps?=",
                $mime->to("A\tsample\tstring that wraps")
            );

        $this->assertSame(
                "name: =?UTF-8?B?CUEJc3RyaW5n?=---\t=?UTF-8?B?CQ==?=",
                $mime->to("\tA\tstring\t")
            );
    }

}

