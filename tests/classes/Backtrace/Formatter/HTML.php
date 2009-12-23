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
class classes_Backtrace_Formatter_HTML extends PHPUnit_Framework_TestCase
{

    public function testPrefix ()
    {
        $formatter = new \r8\Backtrace\Formatter\HTML;

        $this->assertSame(
            "<ol style='list-style-type: decimal;'>\n",
            $formatter->prefix()
        );
    }

    public function testSuffix ()
    {
        $formatter = new \r8\Backtrace\Formatter\HTML;
        $this->assertSame( "</ol>\n", $formatter->suffix() );
    }

    public function testEvent_Empty ()
    {
        $formatter = new \r8\Backtrace\Formatter\HTML;

        $this->assertSame(
            "    <li>\n"
            ."        <em>#1:</em> <strong>method</strong>\n"
            ."    </li>\n",
            $formatter->event( 1, "method", array(), NULL, NULL )
        );
    }

    public function testEvent_Closure ()
    {
        $formatter = new \r8\Backtrace\Formatter\HTML;

        $this->assertSame(
            "    <li>\n"
            ."        <em>#1:</em> <strong><em>{closure}</em></strong>\n"
            ."    </li>\n",
            $formatter->event( 1, NULL, array(), NULL, NULL )
        );
    }

    public function testEvent_Full ()
    {
        $formatter = new \r8\Backtrace\Formatter\HTML;

        $this->assertSame(
            "    <li>\n"
            ."        <em>#1:</em> <strong>method</strong>\n"
            ."        <ul>\n"
            ."            <li>File: example.php</li>\n"
            ."            <li>Line: 25</li>\n"
            ."            <li>Arguments:<ul>\n"
            ."                <li>string('arg1')</li>\n"
            ."                <li>int(2)</li>\n"
            ."            </ul></li>\n"
            ."        </ul>\n"
            ."    </li>\n",
            $formatter->event( 1, "method", array( "arg1", 2 ), "example.php", 25 )
        );
    }

    public function testMain ()
    {
        $formatter = new \r8\Backtrace\Formatter\HTML;

        $this->assertSame(
            "    <li>\n"
            ."        <em>#10:</em> Main: <strong>example.php</strong>\n"
            ."    </li>\n",
            $formatter->main( 10, "example.php" )
        );
    }

}

?>