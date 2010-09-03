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

    public function testConstruct ()
    {
        $command = new \r8\CLI\Command('name', 'desc');
        $this->assertSame( 'name', $command->getName() );
        $this->assertSame( 'desc', $command->getDescription() );
        $this->assertEquals( array(new \r8\CLI\Form), $command->getForms() );

        try {
            new \r8\CLI\Command('', 'desc');
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {}

        try {
            new \r8\CLI\Command('name', '');
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {}
    }

    public function testAddArg ()
    {
        $command = new \r8\CLI\Command('name', 'desc');

        $arg1 = $this->getMock('r8\iface\CLI\Arg');
        $arg2 = $this->getMock('r8\iface\CLI\Arg');
        $this->assertSame( $command, $command->addArg($arg1) );
        $this->assertSame( $command, $command->addArg($arg2) );

        $forms = $command->getforms();
        $this->assertArrayHasKey(0, $forms);
        $this->assertSame( array($arg1, $arg2), $forms[0]->getArgs() );
    }

    public function testAddOption ()
    {
        $command = new \r8\CLI\Command('name', 'desc');

        $opt1 = new r8\CLI\Option('a', 'test');
        $opt2 = new r8\CLI\Option('b', 'test');
        $this->assertSame( $command, $command->addOption($opt1) );
        $this->assertSame( $command, $command->addOption($opt2) );

        $forms = $command->getforms();
        $this->assertArrayHasKey(0, $forms);
        $this->assertSame( $opt1, $forms[0]->findByFlag('a') );
        $this->assertSame( $opt2, $forms[0]->findByFlag('b') );
    }

    public function testProcess_WithoutArgs ()
    {
        $command = new \r8\CLI\Command('name', 'desc');

        try {
            // It's okay if this throws an exception, as long as there
            // isn't a PHP error
            $command->process();
        }
        catch ( \r8\Exception\Data $err ) {}
    }

    public function testProcess_OneFormPasses ()
    {
        $command = new \r8\CLI\Command('name', 'desc');

        $this->assertEquals(
            new \r8\CLI\Result,
            $command->process( new \r8\CLI\Input(array('test.php')) )
        );
    }

    public function testProcess_AllFormsFail ()
    {
        $command = new \r8\CLI\Command('name', 'desc');
        $command->addForm( new \r8\CLI\Form );
        $command->addForm( new \r8\CLI\Form );

        try {
            $command->process( new \r8\CLI\Input(array('test.php', 'extra')) );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Data $err ) {}
    }

    public function testProcess_SecondFormReturns ()
    {
        $command = new \r8\CLI\Command('name', 'desc');
        $command->addForm(
            \r8( new \r8\CLI\Form )->addArg( new \r8\CLI\Arg\One('test') )
        );

        $result = $command->process( new \r8\CLI\Input(array('test.php', 'one')) );

        $this->assertThat( $result, $this->isInstanceOf('\r8\CLI\Result') );
        $this->assertSame( array('one'), $result->getArgs() );
    }

    public function testGetHelp_Bare ()
    {
        $command = new \r8\CLI\Command('cmd', 'A description of this command');
        $this->assertSame(
            "USAGE:\n"
            ."    cmd\n\n"
            ."DESCRIPTION:\n"
            ."    A description of this command\n\n",
            $command->getHelp()
        );
    }

    public function testGetHelp_MultipleForms ()
    {
        $command = new \r8\CLI\Command('cmd', 'A description of this command');
        $command->addArg( new \r8\CLI\Arg\One("Arg1") );
        $command->addArg( new \r8\CLI\Arg\Many("Arg2") );

        $form = new \r8\CLI\Form;
        $form->addOption( new \r8\CLI\Option('help', 'Displays the help view') );
        $form->addOption( new \r8\CLI\Option('f', 'Performs an action') );
        $command->addForm( $form );

        $this->assertSame(
            "USAGE:\n"
            ."    cmd [Arg1] [Arg2]...\n"
            ."    cmd [--help,-f]\n\n"
            ."DESCRIPTION:\n"
            ."    A description of this command\n\n"
            ."OPTIONS:\n"
            ."    -f\n"
            ."        Performs an action\n"
            ."    --help\n"
            ."        Displays the help view\n\n",
            $command->getHelp()
        );
    }

    public function testGetHelp_LongDescription ()
    {
        $command = new \r8\CLI\Command(
            'cmd',
            'A particularly long description of this command that will need '
            .'to be wrapped because of its length'
        );
        $this->assertSame(
            "USAGE:\n"
            ."    cmd\n\n"
            ."DESCRIPTION:\n"
            ."    A particularly long description of this command that will need to be wrapped\n"
            ."    because of its length\n\n",
            $command->getHelp()
        );
    }

}

?>