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
 * unit tests
 */
class classes_validator_compare extends PHPUnit_Framework_TestCase
{

    public function testBadOperator ()
    {
        try {
            $compare = new \r8\Validator\Compare("bad", "value");
            $this->fail("An expected exception was not thrown");
        }
        catch( \r8\Exception\Argument $err ) {
            $this->assertSame("Unsupported comparison operator", $err->getMessage());
        }
    }

    public function testLessThan ()
    {
        $valid = new \r8\Validator\Compare( "<", 20 );

        $this->assertFalse( $valid->validate( 25 )->isValid() );
        $this->assertFalse( $valid->validate( 20 )->isValid() );
        $this->assertTrue( $valid->validate( 19 )->isValid() );
    }

    public function testGreaterThan ()
    {
        $valid = new \r8\Validator\Compare( ">", 20 );

        $this->assertTrue( $valid->validate( 25 )->isValid() );
        $this->assertFalse( $valid->validate( 20 )->isValid() );
        $this->assertFalse( $valid->validate( 19 )->isValid() );
    }

    public function testLessThanEquals ()
    {
        $valid = new \r8\Validator\Compare( "<=", 20 );

        $this->assertFalse( $valid->validate( 25 )->isValid() );
        $this->assertTrue( $valid->validate( 20 )->isValid() );
        $this->assertTrue( $valid->validate( 19 )->isValid() );
    }

    public function testGreaterThanEquals ()
    {
        $valid = new \r8\Validator\Compare( ">=", 20 );

        $this->assertTrue( $valid->validate( 25 )->isValid() );
        $this->assertTrue( $valid->validate( 20 )->isValid() );
        $this->assertFalse( $valid->validate( 19 )->isValid() );
    }

    public function testSame ()
    {
        $valid = new \r8\Validator\Compare( "===", 20 );

        $this->assertTrue(  $valid->validate( 20 )->isValid() );
        $this->assertFalse( $valid->validate( 25 )->isValid() );
        $this->assertFalse( $valid->validate( "20" )->isValid() );
    }

    public function testEquals ()
    {
        $valid = new \r8\Validator\Compare( "==", 20 );

        $this->assertTrue(  $valid->validate( 20 )->isValid() );
        $this->assertTrue( $valid->validate( "20" )->isValid() );
        $this->assertFalse( $valid->validate( 25 )->isValid() );


        $valid = new \r8\Validator\Compare( "=", 20 );

        $this->assertTrue(  $valid->validate( 20 )->isValid() );
        $this->assertTrue( $valid->validate( "20" )->isValid() );
        $this->assertFalse( $valid->validate( 25 )->isValid() );
    }

    public function testNotSame ()
    {
        $valid = new \r8\Validator\Compare( "!==", 20 );

        $this->assertFalse( $valid->validate( 20 )->isValid() );
        $this->assertTrue(  $valid->validate( 25 )->isValid() );
        $this->assertTrue(  $valid->validate( "20" )->isValid() );
    }

    public function testNotEquals ()
    {
        $valid = new \r8\Validator\Compare( "!=", 20 );

        $this->assertFalse( $valid->validate( 20 )->isValid() );
        $this->assertTrue(  $valid->validate( 25 )->isValid() );
        $this->assertFalse( $valid->validate( "20" )->isValid() );


        $valid = new \r8\Validator\Compare( "<>", 20 );

        $this->assertFalse( $valid->validate( 20 )->isValid() );
        $this->assertTrue(  $valid->validate( 25 )->isValid() );
        $this->assertFalse( $valid->validate( "20" )->isValid() );
    }

}

