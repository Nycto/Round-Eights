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

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_Backtrace_Formatter_Text extends PHPUnit_Framework_TestCase
{

    public function testPrefix ()
    {
        $formatter = new \r8\Backtrace\Formatter\Text;
        $this->assertSame( "", $formatter->prefix() );
    }

    public function testSuffix ()
    {
        $formatter = new \r8\Backtrace\Formatter\Text;
        $this->assertSame( "", $formatter->suffix() );
    }

    public function testEvent_Empty ()
    {
        $formatter = new \r8\Backtrace\Formatter\Text;

        $this->assertSame(
            "#1: method\n",
            $formatter->event( 1, "method", array(), NULL, NULL )
        );
    }

    public function testEvent_Closure ()
    {
        $formatter = new \r8\Backtrace\Formatter\Text;

        $this->assertSame(
            "#1: {closure}\n",
            $formatter->event( 1, NULL, array(), NULL, NULL )
        );
    }

    public function testEvent_Full ()
    {
        $formatter = new \r8\Backtrace\Formatter\Text;

        $this->assertSame(
            "#1: method\n"
            ."    File: example.php\n"
            ."    Line: 25\n"
            ."    Arguments:\n"
            ."        string('arg1')\n"
            ."        int(2)\n",
            $formatter->event( 1, "method", array( "arg1", 2 ), "example.php", 25 )
        );
    }

    public function testMain ()
    {
        $formatter = new \r8\Backtrace\Formatter\Text;

        $this->assertSame(
            "#10: Main: example.php\n",
            $formatter->main( 10, "example.php" )
        );
    }

}

?>