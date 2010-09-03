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
class classes_CLI_Option extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a test argument
     *
     * @return \r8\iface\CLI\Arg
     */
    public function getTestArg ( $greedy = FALSE, $consume = array() )
    {
        $arg = $this->getMock('\r8\iface\CLI\Arg');
        $arg->expects( $this->any() )->method( "isGreedy" )
            ->will( $this->returnValue( $greedy ) );
        $arg->expects( $this->any() )->method( "consume" )
            ->will( $this->returnValue( $consume ) );
        return $arg;
    }

    public function testDescribe ()
    {
        $opt = new \r8\CLI\Option("A", "Testing the description");
        $this->assertSame(
            "    -A\n"
            ."        Testing the description\n",
            $opt->describe()
        );

        $opt->addFlag('b')->addFlag('long');
        $this->assertSame(
            "    -A, -b, --long\n"
            ."        Testing the description\n",
            $opt->describe()
        );

        $opt->addArg( new \r8\CLI\Arg\One("Arg1") );
        $opt->addArg( new \r8\CLI\Arg\Many("Arg2") );
        $this->assertSame(
            "    -A, -b, --long [Arg1] [Arg2]...\n"
            ."        Testing the description\n",
            $opt->describe()
        );

        $opt = new \r8\CLI\Option(
            'opt',
            'A particularly long description of this command that will need '
            .'to be wrapped because of its length'
        );
        $this->assertSame(
            "    --opt\n"
            ."        A particularly long description of this command that will need to be\n"
            ."        wrapped because of its length\n",
            $opt->describe()
        );
    }

    public function testNormalizeFlag ()
    {
        $this->assertSame( "a", \r8\CLI\Option::normalizeFlag("a") );
        $this->assertSame( "A", \r8\CLI\Option::normalizeFlag("A") );
        $this->assertSame( "test", \r8\CLI\Option::normalizeFlag("TesT") );
        $this->assertSame( "with-spaces", \r8\CLI\Option::normalizeFlag("with spaces") );
        $this->assertSame( "trim-this", \r8\CLI\Option::normalizeFlag("--trim-this--") );

        try {
            \r8\CLI\Option::normalizeFlag("   ");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {}

        $this->assertSame( "", \r8\CLI\Option::normalizeFlag(NULL, FALSE) );
    }

    public function testConstruct ()
    {
        $opt = new \r8\CLI\Option("a", "Test", TRUE);
        $this->assertSame( "a", $opt->getPrimaryFlag() );
        $this->assertSame( "Test", $opt->getDescription() );
        $this->assertSame( array("a"), $opt->getFlags() );
        $this->assertTrue( $opt->allowMany() );
    }

    public function testAddFlag ()
    {
        $opt = new \r8\CLI\Option("A", "Test");
        $this->assertSame( array("A"), $opt->getFlags() );

        $this->assertSame( $opt, $opt->addFlag("a") );
        $this->assertSame( array("A", "a"), $opt->getFlags() );

        $this->assertSame( $opt, $opt->addFlag("A") );
        $this->assertSame( array("A", "a"), $opt->getFlags() );

        $this->assertSame( $opt, $opt->addFlag("--o  ") );
        $this->assertSame( array("A", "a", "o"), $opt->getFlags() );

        $this->assertSame( $opt, $opt->addFlag("-o  ") );
        $this->assertSame( array("A", "a", "o"), $opt->getFlags() );

        $this->assertSame( $opt, $opt->addFlag("--Some Flag") );
        $this->assertSame(
            array("A", "a", "o", "some-flag"),
            $opt->getFlags()
        );

        $this->assertSame( $opt, $opt->addFlag("--BAD!@#CHARS--") );
        $this->assertSame(
            array("A", "a", "o", "some-flag", "badchars"),
            $opt->getFlags()
        );

        try {
            $opt->addFlag("  ");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {}
    }

    public function testHasFlag ()
    {
        $opt = new \r8\CLI\Option("A", "Test");
        $opt->addFlag( "switch" );

        $this->assertFalse( $opt->hasFlag("   ") );
        $this->assertFalse( $opt->hasFlag("") );

        $this->assertTrue( $opt->hasFlag("A") );
        $this->assertFalse( $opt->hasFlag("B") );
        $this->assertFalse( $opt->hasFlag("a") );
        $this->assertTrue( $opt->hasFlag("switch") );
        $this->assertTrue( $opt->hasFlag("Switch") );
    }

    public function testAddArg ()
    {
        $opt = new \r8\CLI\Option("A", "Test");
        $this->assertSame( array(), $opt->getArgs() );

        $arg1 = $this->getTestArg();
        $this->assertSame( $opt, $opt->addArg($arg1) );
        $this->assertSame( array($arg1), $opt->getArgs() );

        $arg2 = $this->getTestArg();
        $this->assertSame( $opt, $opt->addArg($arg2) );
        $this->assertSame( array($arg1, $arg2), $opt->getArgs() );
    }

    public function testAddArg_Greedy ()
    {
        $opt = new \r8\CLI\Option("A", "Test");

        // Add a greedy argument
        $opt->addArg( $this->getTestArg(TRUE) );

        try {
            $opt->addArg( $this->getTestArg() );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Data $err ) {}
    }

    public function testConsume_NoArgs ()
    {
        $opt = new \r8\CLI\Option("A", "Test");

        $this->assertSame(
            array(),
            $opt->consume( new \r8\CLI\Input(array('test.php')) )
        );
    }

    public function testConsume_WithArgs ()
    {
        $input = new \r8\CLI\Input(array('test.php'));

        $opt = new \r8\CLI\Option("A", "Test");

        $arg1 = $this->getTestArg(FALSE, array( "one", "two" ));
        $opt->addArg( $arg1 );

        $arg2 = $this->getTestArg(FALSE, array());
        $opt->addArg( $arg2 );

        $arg3 = $this->getTestArg(FALSE, array( "three" ));
        $opt->addArg( $arg3 );

        $this->assertSame(
            array( "one", "two", "three" ),
            $opt->consume( $input )
        );
    }

}

?>