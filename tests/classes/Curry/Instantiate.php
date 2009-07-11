<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of RaindropPHP.
 *
 * RaindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * RaindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with RaindropPHP. If not, see <http://www.RaindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <James@RaindropPHP.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * This is a stub class that will take its constructor arguments and save them to
 * a public variable
 */
class stub_curry_instantiate
{

    /**
     * The arguments given to the constructor
     */
    public $args;

    /**
     * Constructor...
     */
    public function __construct ()
    {
        $this->args = func_get_args();
    }

}

/**
 * unit tests
 */
class classes_curry_instantiate extends PHPUnit_Framework_TestCase
{

    public function testConstructError ()
    {
        try {
            new \h2o\Curry\Instantiate( "This is not a real class" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\Argument $err ) {
            $this->assertSame( "Class does not exist", $err->getMessage() );
        }
    }

    public function testConstructArgs ()
    {
        $curry = new \h2o\Curry\Instantiate( "stub_curry_instantiate" );
        $this->assertEquals( array(), $curry->getRight() );
        $this->assertEquals( array(), $curry->getLeft() );

        $curry = new \h2o\Curry\Instantiate( "stub_curry_instantiate", "one", "two" );
        $this->assertEquals( array("one", "two"), $curry->getRight() );
        $this->assertEquals( array(), $curry->getLeft() );
    }

    public function testNoArgs ()
    {
        $curry = new \h2o\Curry\Instantiate("stub_curry_instantiate");

        $result = $curry->exec();
        $this->assertThat( $result, $this->isInstanceOf("stub_curry_instantiate") );

        $this->assertNotSame( $result, $curry() );
    }

    public function testOneArg ()
    {
        $curry = new \h2o\Curry\Instantiate( "stub_curry_instantiate", "one" );

        $result = $curry();

        $this->assertThat( $result, $this->isInstanceOf("stub_curry_instantiate") );
        $this->assertSame( array("one"), $result->args );
    }

    public function testManyArgs ()
    {
        $curry = new \h2o\Curry\Instantiate( "stub_curry_instantiate" );
        $curry->setLeft("one", "two");

        $result = $curry("three");

        $this->assertThat( $result, $this->isInstanceOf("stub_curry_instantiate") );
        $this->assertSame( array("one", "two", "three"), $result->args );
    }

}

?>