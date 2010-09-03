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
 * Unit Tests
 */
class classes_CLI_Arg_One extends PHPUnit_Framework_TestCase
{

    public function testIsGreedy ()
    {
        $arg = new \r8\CLI\Arg\One( "Test" );
        $this->assertFalse( $arg->isGreedy() );
    }

    public function testDescribe ()
    {
        $arg = new \r8\CLI\Arg\One("Test");
        $this->assertSame( "[Test]", $arg->describe() );
    }

    public function testConsume_Empty ()
    {
        $arg = new \r8\CLI\Arg\One(
            "test",
            new \r8\Filter\Identity,
            new \r8\Validator\Compare("===", NULL)
        );

        $this->assertEquals(
            array( NULL ),
            $arg->consume(
                new \r8\CLI\Input(array())
            )
        );
    }

    public function testConsume_Valid ()
    {
        $arg = new \r8\CLI\Arg\One(
            "test",
            new \r8\Curry\Call('strtoupper'),
            new \r8\Validator\NotEmpty
        );

        $this->assertSame(
            array("ONE"),
            $arg->consume(
                new \r8\CLI\Input(array('test.php', "one", "two", "three"))
            )
        );
    }

    public function testConsume_Invalid ()
    {
        $arg = new \r8\CLI\Arg\One(
            "test",
            new \r8\Curry\Call('strtoupper'),
            new \r8\Validator\Compare("=", "yes")
        );

        try {
            $arg->consume(new \r8\CLI\Input(array('test.php', "no", "no")));
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Data $err ) {}
    }

}

?>