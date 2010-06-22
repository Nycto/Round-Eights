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
class classes_CLI_Command extends PHPUnit_Framework_TestCase
{

    public function testFindByFlag ()
    {
        $opt1 = \r8( new \r8\CLI\Option('a', 'blah') )->addFlag('C');
        $opt2 = new \r8\CLI\Option('b', 'hork');
        $opt3 = new \r8\CLI\Option('b', 'another');

        $command = new \r8\CLI\Command;
        $command->addOption( $opt1 )
            ->addOption( $opt2 )
            ->addOption( $opt3 );

        $this->assertSame( $opt1, $command->findByFlag('a') );
        $this->assertSame( $opt1, $command->findByFlag('C') );
        $this->assertSame( $opt3, $command->findByFlag('b') );
        $this->assertNull( $command->findbyFlag('switch') );
    }

    public function testAddArg ()
    {
        $command = new \r8\CLI\Command;
        $this->assertSame( array(), $command->getArgs() );

        $arg1 = $this->getMock('r8\iface\CLI\Arg');
        $this->assertSame( $command, $command->addArg($arg1) );
        $this->assertSame( array($arg1), $command->getArgs() );

        $arg2 = $this->getMock('r8\iface\CLI\Arg');
        $this->assertSame( $command, $command->addArg($arg2) );
        $this->assertSame( array($arg1, $arg2), $command->getArgs() );
    }

    public function testProcess_Empty ()
    {
        $command = new \r8\CLI\Command;

        $this->assertEquals(
            new \r8\CLI\Result,
            $command->process( new \r8\CLI\Input(array()) )
        );
    }

    public function testProcess_WithFlags ()
    {
        $input = new \r8\CLI\Input( array('-a', 'one', 'two') );

        $arg = new \r8\CLI\Arg\Many(
            'test',
            new \r8\Filter\Identity,
            new \r8\Validator\Pass
        );

        $command = new \r8\CLI\Command;
        $command->addOption(
            \r8( new \r8\CLI\Option('a', 'test') )->addArg( $arg )
        );

        $result = $command->process( $input );

        $this->assertThat( $result, $this->isInstanceOf('\r8\CLI\Result') );
        $this->assertTrue( $result->flagExists('a') );
        $this->assertSame( array('one', 'two'), $result->getArgsForFlag('a') );
        $this->assertSame(
            array( array('one', 'two') ),
            $result->getAllArgsForFlag('a')
        );
    }

    public function testProcess_UnrecognizedFlag ()
    {
        $input = new \r8\CLI\Input( array('-a', 'one', 'two') );

        $command = new \r8\CLI\Command;

        try {
            $command->process( $input );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Data $err ) {}
    }

    public function testProcess_WithArgs ()
    {
        $input = new \r8\CLI\Input( array('one', 'two') );

        $command = new \r8\CLI\Command;
        $command->addArg( new \r8\CLI\Arg\Many(
            'input',
            new \r8\Filter\Identity,
            new \r8\Validator\Pass
        ));

        $result = $command->process( $input );

        $this->assertThat( $result, $this->isInstanceOf('\r8\CLI\Result') );
        $this->assertSame( array('one', 'two'), $result->getArgs() );
    }

    public function testProcess_UnrecognizedArgs ()
    {
        $input = new \r8\CLI\Input( array('one', 'two') );

        $command = new \r8\CLI\Command;

        try {
            $command->process( $input );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Data $err ) {}
    }

}

?>