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

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * Unit Tests
 */
class classes_CLIArgs_Input extends PHPUnit_Framework_TestCase
{

    public function testPopOption_EndOfList ()
    {
        $input = new \r8\CLIArgs\Input(array('-a', '-B'));
        $this->assertSame( 'a', $input->popOption() );
        $this->assertSame( 'B', $input->popOption() );

        try {
            $input->popOption();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Index $err ) {}
    }

    public function testPopOption_MissedArgument ()
    {
        $input = new \r8\CLIArgs\Input(array('-a=blah', '-B'));
        $this->assertSame( 'a', $input->popOption() );

        try {
            $input->popOption();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Data $err ) {}
    }

    public function testPopArgument_OptionPadding ()
    {
        $input = new \r8\CLIArgs\Input(array('-a=blah', '-B'));
        $this->assertSame( 'a', $input->popOption() );
        $this->assertSame( 'blah', $input->popArgument() );
        $this->assertNull( $input->popArgument() );
        $this->assertNull( $input->popArgument() );
        $this->assertNull( $input->popArgument() );
        $this->assertSame( 'B', $input->popOption() );
        $this->assertNull( $input->popArgument() );
        $this->assertNull( $input->popArgument() );
        $this->assertNull( $input->popArgument() );
    }

    public function testParse_Empty ()
    {
        $input = new \r8\CLIArgs\Input(array());
        $this->assertFalse( $input->hasNextOption() );
        $this->assertFalse( $input->hasNextArg() );
        $this->assertNull( $input->popArgument() );
    }

    public function testParse_Flag ()
    {
        $input = new \r8\CLIArgs\Input(array('-a', '-B'));
        $this->assertSame( 'a', $input->popOption() );
        $this->assertSame( 'B', $input->popOption() );

        $input = new \r8\CLIArgs\Input(array('-abc', '-de'));
        $this->assertSame( 'a', $input->popOption() );
        $this->assertSame( 'b', $input->popOption() );
        $this->assertSame( 'c', $input->popOption() );
        $this->assertSame( 'd', $input->popOption() );
        $this->assertSame( 'e', $input->popOption() );

        $input = new \r8\CLIArgs\Input(array('-a=value'));
        $this->assertSame( 'a', $input->popOption() );
        $this->assertSame( 'value', $input->popArgument() );

        $input = new \r8\CLIArgs\Input(array('-abC=value'));
        $this->assertSame( 'a', $input->popOption() );
        $this->assertSame( 'b', $input->popOption() );
        $this->assertSame( 'C', $input->popOption() );
        $this->assertSame( 'value', $input->popArgument() );
    }

    public function testParse_EmptyFlag ()
    {
        $input = new \r8\CLIArgs\Input(array('-'));
        try {
            $input->popOption();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Data $err ) {}

        $input = new \r8\CLIArgs\Input(array('-=value'));
        try {
            $input->popOption();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Data $err ) {}
    }

    public function testParse_Switch ()
    {
        $input = new \r8\CLIArgs\Input(array('--word'));
        $this->assertSame( 'word', $input->popOption() );

        $input = new \r8\CLIArgs\Input(array('--Word=Value'));
        $this->assertSame( 'word', $input->popOption() );
        $this->assertSame( 'Value', $input->popArgument() );
    }

    public function testParse_Args ()
    {
        $input = new \r8\CLIArgs\Input(array('one', 'two'));
        $this->assertSame( 'one', $input->popArgument() );
        $this->assertSame( 'two', $input->popArgument() );
    }

    public function testParse_Duplicates ()
    {
        $input = new \r8\CLIArgs\Input(array('-a', 'arg', '-a'));
        $this->assertSame( 'a', $input->popOption() );
        $this->assertSame( 'arg', $input->popArgument() );
        $this->assertSame( 'a', $input->popOption() );

        $input = new \r8\CLIArgs\Input(array('--switch', 'arg', '--switch'));
        $this->assertSame( 'switch', $input->popOption() );
        $this->assertSame( 'arg', $input->popArgument() );
        $this->assertSame( 'switch', $input->popOption() );
    }

    public function testParse_DoubleDash ()
    {
        $input = new \r8\CLIArgs\Input(array('-a', 'one', '--', '-b', 'two'));
        $this->assertSame( 'a', $input->popOption() );
        $this->assertSame( 'one', $input->popArgument() );
        $this->assertSame( '-b', $input->popArgument() );
        $this->assertSame( 'two', $input->popArgument() );
    }

    public function testRewind ()
    {
        $input = new \r8\CLIArgs\Input(array('-a', '-B'));
        $this->assertSame( 'a', $input->popOption() );
        $this->assertSame( 'B', $input->popOption() );

        $this->assertSame( $input, $input->rewind() );
        $this->assertSame( 'a', $input->popOption() );
        $this->assertSame( 'B', $input->popOption() );
    }

}

?>