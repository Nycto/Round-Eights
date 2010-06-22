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
class classes_CLI_Result extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a test result object filled with data
     *
     * @return \r8\CLI\Result
     */
    public function getTestResult ()
    {
        $opt1 = \r8(new \r8\CLI\Option('a', ''))->addFlag('b')->addFlag('A');
        return r8( new \r8\CLI\Result )
            ->addOption( $opt1, array( 'arg1', 'arg2' ) )
            ->addOption( $opt1, array( 'two', 'three' ) )
            ->addOption( $opt1, array( 'four' ) )
            ->addOption(
                \r8(new \r8\CLI\Option('x', ''))->addFlag('LONG'),
                array()
            );
    }

    public function testFlagExists ()
    {
        $result = $this->getTestResult();

        $this->assertTrue( $result->flagExists('a') );
        $this->assertTrue( $result->flagExists('A') );
        $this->assertTrue( $result->flagExists('LONG') );

        $this->assertFalse( $result->flagExists(NULL) );
        $this->assertFalse( $result->flagExists('z') );
        $this->assertFalse( $result->flagExists('Longer') );
    }

    public function testGetArgsForFlag ()
    {
        $result = $this->getTestResult();

        $this->assertSame( array(), $result->getArgsForFlag( NULL ) );
        $this->assertSame( array(), $result->getArgsForFlag('not a flag') );

        $this->assertSame( array(), $result->getArgsForFlag('x') );
        $this->assertSame( array(), $result->getArgsForFlag('long') );
        $this->assertSame( array('arg1', 'arg2'), $result->getArgsForFlag('a') );
        $this->assertSame( array('arg1', 'arg2'), $result->getArgsForFlag('b') );
        $this->assertSame( array('arg1', 'arg2'), $result->getArgsForFlag('A') );
    }

    public function testGetAllArgsForFlag ()
    {
        $result = $this->getTestResult();

        $this->assertSame( array(), $result->getAllArgsForFlag( NULL ) );
        $this->assertSame( array(), $result->getAllArgsForFlag('not a flag') );

        $this->assertSame( array( array() ), $result->getAllArgsForFlag('x') );
        $this->assertSame( array( array() ), $result->getAllArgsForFlag('long') );

        $this->assertSame(
            array( array('arg1', 'arg2'), array('two', 'three'), array('four') ),
            $result->getAllArgsForFlag('a')
        );

        $this->assertSame(
            array( array('arg1', 'arg2'), array('two', 'three'), array('four') ),
            $result->getAllArgsForFlag('A')
        );
    }

    public function testAddArgs ()
    {
        $result = $this->getTestResult();
        $this->assertSame( array(), $result->getArgs() );

        $this->assertSame( $result, $result->addArgs(array("one", "two")) );
        $this->assertSame( array("one", "two"), $result->getArgs() );

        $this->assertSame( $result, $result->addArgs(array()) );
        $this->assertSame( array("one", "two"), $result->getArgs() );

        $this->assertSame( $result, $result->addArgs(array("three")) );
        $this->assertSame( array("one", "two", "three"), $result->getArgs() );
    }

}

?>