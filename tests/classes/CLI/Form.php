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
class classes_CLI_Form extends PHPUnit_Framework_TestCase
{

    public function testGetOptions ()
    {
        $opt1 = new \r8\CLI\Option('a', 'blah');
        $opt2 = new \r8\CLI\Option('b', 'hork');

        $form = new \r8\CLI\Form;
        $form->addOption( $opt1 )->addOption( $opt2 );

        $this->assertSame(
            array('a' => $opt1, 'b' => $opt2),
            $form->getOptions()
        );
    }

    public function testFindByFlag ()
    {
        $opt1 = \r8( new \r8\CLI\Option('a', 'blah') )->addFlag('C');
        $opt2 = new \r8\CLI\Option('b', 'hork');
        $opt3 = new \r8\CLI\Option('b', 'another');

        $form = new \r8\CLI\Form;
        $form->addOption( $opt1 )
            ->addOption( $opt2 )
            ->addOption( $opt3 );

        $this->assertSame( $opt1, $form->findByFlag('a') );
        $this->assertSame( $opt1, $form->findByFlag('C') );
        $this->assertSame( $opt3, $form->findByFlag('b') );
        $this->assertNull( $form->findbyFlag('switch') );
    }

    public function testAddArg ()
    {
        $form = new \r8\CLI\Form;
        $this->assertSame( array(), $form->getArgs() );

        $arg1 = $this->getMock('r8\iface\CLI\Arg');
        $this->assertSame( $form, $form->addArg($arg1) );
        $this->assertSame( array($arg1), $form->getArgs() );

        $arg2 = $this->getMock('r8\iface\CLI\Arg');
        $this->assertSame( $form, $form->addArg($arg2) );
        $this->assertSame( array($arg1, $arg2), $form->getArgs() );
    }

    public function testAddArg_Greedy ()
    {
        $form = new \r8\CLI\Form;

        // Add a greedy argument
        $form->addArg( new \r8\CLI\Arg\Many('arg') );

        try {
            $form->addArg( new \r8\CLI\Arg\One('arg2') );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Data $err ) {}
    }

    public function testProcess_Empty ()
    {
        $form = new \r8\CLI\Form;

        $this->assertEquals(
            new \r8\CLI\Result,
            $form->process( new \r8\CLI\Input(array()) )
        );
    }

    public function testProcess_WithFlags ()
    {
        $input = new \r8\CLI\Input( array('-a', 'one', 'two') );

        $arg = new \r8\CLI\Arg\Many('test');

        $form = new \r8\CLI\Form;
        $form->addOption(
            \r8( new \r8\CLI\Option('a', 'test') )->addArg( $arg )
        );

        $result = $form->process( $input );

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

        $form = new \r8\CLI\Form;

        try {
            $form->process( $input );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Data $err ) {}
    }

    public function testProcess_WithArgs ()
    {
        $input = new \r8\CLI\Input( array('one', 'two') );

        $form = new \r8\CLI\Form;
        $form->addArg( new \r8\CLI\Arg\Many('input') );

        $result = $form->process( $input );

        $this->assertThat( $result, $this->isInstanceOf('\r8\CLI\Result') );
        $this->assertSame( array('one', 'two'), $result->getArgs() );
    }

    public function testProcess_UnrecognizedArgs ()
    {
        $input = new \r8\CLI\Input( array('one', 'two') );

        $form = new \r8\CLI\Form;

        try {
            $form->process( $input );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Data $err ) {}
    }

    public function testProcess_RepeatedFlag ()
    {
        $input = new \r8\CLI\Input( array('-a', '-a') );

        $form = new \r8\CLI\Form;
        $form->addOption( new \r8\CLI\Option('a', 'test', FALSE) );

        try {
            $form->process( $input );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Data $err ) {}
    }

    public function testDescribe_Bare ()
    {
        $form = new \r8\CLI\Form;
        $this->assertSame( "cmd", $form->describe('cmd') );
    }

    public function testDescribe_WithArgs ()
    {
        $form = new \r8\CLI\Form;
        $form->addArg( new \r8\CLI\Arg\One("Arg1") );
        $form->addArg( new \r8\CLI\Arg\Many("Arg2") );

        $this->assertSame( "cmd [Arg1] [Arg2]...", $form->describe('cmd') );
    }

    public function testDescribe_WithOptions ()
    {
        $form = new \r8\CLI\Form;
        $form->addArg( new \r8\CLI\Arg\One("Arg1") );
        $form->addArg( new \r8\CLI\Arg\Many("Arg2") );
        $form->addOption( new \r8\CLI\Option('help', 'Displays the help view') );
        $form->addOption( new \r8\CLI\Option('f', 'Performs an action') );

        $this->assertSame( "cmd [--help,-f] [Arg1] [Arg2]...", $form->describe('cmd') );
    }

}

?>