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
 * @author James Frasca <james@Raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_random_seed extends PHPUnit_Framework_TestCase
{

    public function testRandom ()
    {
        $this->assertThat(
                \h2o\Random\Seed::random(),
                $this->isInstanceOf("h2o\Random\Seed")
            );

        $this->assertNotEquals(
                \h2o\Random\Seed::random()->getSource(),
                \h2o\Random\Seed::random()->getSource()
            );

        $this->assertNotEquals(
                \h2o\Random\Seed::random()->getSource(),
                \h2o\Random\Seed::random()->getSource()
            );
    }

    public function testSourceAccessors ()
    {
        $seed = new \h2o\Random\Seed("Initial value");

        $this->assertSame( "Initial value", $seed->getSource() );

        $this->assertSame( $seed, $seed->setSource(123456) );
        $this->assertSame( "123456", $seed->getSource() );

        $this->assertSame( $seed, $seed->setSource(array("value")) );
        $this->assertSame( 'a:1:{i:0;s:5:"value";}', $seed->getSource() );

        $this->assertSame( $seed, $seed->setSource(NULL) );
        $this->assertSame( "", $seed->getSource() );

        $this->assertSame( $seed, $seed->setSource(1.98) );
        $this->assertSame( "1.98", $seed->getSource() );

        $this->assertSame( $seed, $seed->setSource(TRUE) );
        $this->assertSame( "1", $seed->getSource() );

        $this->assertSame( $seed, $seed->setSource( new stdClass ) );
        $this->assertSame( 'O:8:"stdClass":0:{}', $seed->getSource() );
    }

    public function testGetString ()
    {
        $seed = new \h2o\Random\Seed("Initial value");
        $this->assertSame( "fcb1ddc45496d5bd9bbb1d0e3e24a58c56f33281", $seed->getString() );

        $this->assertSame( $seed, $seed->setSource(123456) );
        $this->assertSame( "7c4a8d09ca3762af61e59520943dc26494f8941b", $seed->getString() );

        $this->assertSame( $seed, $seed->setSource(array("value")) );
        $this->assertSame( "ea5d1512ad0c5e2b4516bb698c469805ff6ce5ec", $seed->getString() );

        $this->assertSame( $seed, $seed->setSource(NULL) );
        $this->assertSame( "da39a3ee5e6b4b0d3255bfef95601890afd80709", $seed->getString() );

        $this->assertSame( $seed, $seed->setSource(1.98) );
        $this->assertSame( "299ef94535e5fc122da1afbd80be0ba4f6f99c3e", $seed->getString() );

        $this->assertSame( $seed, $seed->setSource(TRUE) );
        $this->assertSame( "356a192b7913b04c54574d18c28d46e6395428ab", $seed->getString() );

        $this->assertSame( $seed, $seed->setSource( new stdClass ) );
        $this->assertSame( "dac1c1d5a67359940473a571ae99ea5a6e1d3505", $seed->getString() );
    }

    public function testGetInteger ()
    {
        if ( !extension_loaded("bcmath") )
            $this->markTestSkipped("BCMath extension is not enabled");

        $seed = new \h2o\Random\Seed("Initial value");
        $this->assertSame( 916932496, $seed->getInteger() );

        $this->assertSame( $seed, $seed->setSource(123456) );
        $this->assertSame( 223640889, $seed->getInteger() );

        $this->assertSame( $seed, $seed->setSource(array("value")) );
        $this->assertSame( 361406471, $seed->getInteger() );

        $this->assertSame( $seed, $seed->setSource(NULL) );
        $this->assertSame( 122581886, $seed->getInteger() );

        $this->assertSame( $seed, $seed->setSource(1.98) );
        $this->assertSame( 2064395321, $seed->getInteger() );

        $this->assertSame( $seed, $seed->setSource(TRUE) );
        $this->assertSame( 375809508, $seed->getInteger() );

        $this->assertSame( $seed, $seed->setSource( new stdClass ) );
        $this->assertSame( 1596549548, $seed->getInteger() );
    }

    public function testGetFloat ()
    {
        if ( !extension_loaded("bcmath") )
            $this->markTestSkipped("BCMath extension is not enabled");

        $seed = new \h2o\Random\Seed("Initial value");
        $this->assertSame( 0.42697996666049, $seed->getFloat() );

        $this->assertSame( $seed, $seed->setSource(123456) );
        $this->assertSame( 0.10414090431488, $seed->getFloat() );

        $this->assertSame( $seed, $seed->setSource(array("value")) );
        $this->assertSame( 0.16829300260558, $seed->getFloat() );

        $this->assertSame( $seed, $seed->setSource(NULL) );
        $this->assertSame( 0.05708163886195, $seed->getFloat() );

        $this->assertSame( $seed, $seed->setSource(1.98) );
        $this->assertSame( 0.96130898313658, $seed->getFloat() );

        $this->assertSame( $seed, $seed->setSource(TRUE) );
        $this->assertSame( 0.17499993935926, $seed->getFloat() );

        $this->assertSame( $seed, $seed->setSource( new stdClass ) );
        $this->assertSame( 0.74345131811847, $seed->getFloat() );
    }

}

?>