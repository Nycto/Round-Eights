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
class classes_Args_Option extends PHPUnit_Framework_TestCase
{

    public function testGetDescription ()
    {
        $opt = new \r8\Args\Option("Test");
        $this->assertSame( "Test", $opt->getDescription() );
    }

    public function testAddFlag ()
    {
        $opt = new \r8\Args\Option("Test");
        $this->assertSame( array(), $opt->getFlags() );

        $this->assertSame( $opt, $opt->addFlag("A") );
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

}

?>