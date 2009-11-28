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
class classes_Backtrace_Formatter_JSON extends PHPUnit_Framework_TestCase
{

    public function testPrefix ()
    {
        $formatter = new \r8\Backtrace\Formatter\JSON;
        $this->assertSame( "[", $formatter->prefix() );
    }

    public function testSuffix ()
    {
        $formatter = new \r8\Backtrace\Formatter\JSON;
        $this->assertSame( "]", $formatter->suffix() );
    }

    public function testEvent_Empty ()
    {
        $formatter = new \r8\Backtrace\Formatter\JSON;

        $this->assertSame(
            '{"Stack":1,"Name":"method"}, ',
            $formatter->event( 1, "method", array(), NULL, NULL )
        );
    }

    public function testEvent_Closure ()
    {
        $formatter = new \r8\Backtrace\Formatter\JSON;

        $this->assertSame(
            '{"Stack":1,"Closure":true}, ',
            $formatter->event( 1, NULL, array(), NULL, NULL )
        );
    }

    public function testEvent_Full ()
    {
        $formatter = new \r8\Backtrace\Formatter\JSON;

        $this->assertSame(
            '{"Stack":1,"Name":"method","File":"example.php","Line":25,"Args":["string(\'arg1\')","int(2)"]}, ',
            $formatter->event( 1, "method", array( "arg1", 2 ), "example.php", 25 )
        );
    }

    public function testMain ()
    {
        $formatter = new \r8\Backtrace\Formatter\JSON;

        $this->assertSame(
            '{"Stack":10,"Main":true,"File":"example.php"}',
            $formatter->main( 10, "example.php" )
        );
    }

}

?>