<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of commonPHP.
 *
 * commonPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * commonPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with commonPHP. If not, see <http://www.commonphp.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@commonphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
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
            $compare = new \cPHP\Validator\Compare("bad", "value");
            $this->fail("An expected exception was not thrown");
        }
        catch( \cPHP\Exception\Argument $err ) {
            $this->assertSame("Unsupported comparison operator", $err->getMessage());
        }
    }

    public function testLessThan ()
    {
        $valid = new \cPHP\Validator\Compare( "<", 20 );

        $this->assertFalse( $valid->validate( 25 )->isValid() );
        $this->assertFalse( $valid->validate( 20 )->isValid() );
        $this->assertTrue( $valid->validate( 19 )->isValid() );
    }

    public function testGreaterThan ()
    {
        $valid = new \cPHP\Validator\Compare( ">", 20 );

        $this->assertTrue( $valid->validate( 25 )->isValid() );
        $this->assertFalse( $valid->validate( 20 )->isValid() );
        $this->assertFalse( $valid->validate( 19 )->isValid() );
    }

    public function testLessThanEquals ()
    {
        $valid = new \cPHP\Validator\Compare( "<=", 20 );

        $this->assertFalse( $valid->validate( 25 )->isValid() );
        $this->assertTrue( $valid->validate( 20 )->isValid() );
        $this->assertTrue( $valid->validate( 19 )->isValid() );
    }

    public function testGreaterThanEquals ()
    {
        $valid = new \cPHP\Validator\Compare( ">=", 20 );

        $this->assertTrue( $valid->validate( 25 )->isValid() );
        $this->assertTrue( $valid->validate( 20 )->isValid() );
        $this->assertFalse( $valid->validate( 19 )->isValid() );
    }

    public function testSame ()
    {
        $valid = new \cPHP\Validator\Compare( "===", 20 );

        $this->assertTrue(  $valid->validate( 20 )->isValid() );
        $this->assertFalse( $valid->validate( 25 )->isValid() );
        $this->assertFalse( $valid->validate( "20" )->isValid() );
    }

    public function testEquals ()
    {
        $valid = new \cPHP\Validator\Compare( "==", 20 );

        $this->assertTrue(  $valid->validate( 20 )->isValid() );
        $this->assertTrue( $valid->validate( "20" )->isValid() );
        $this->assertFalse( $valid->validate( 25 )->isValid() );


        $valid = new \cPHP\Validator\Compare( "=", 20 );

        $this->assertTrue(  $valid->validate( 20 )->isValid() );
        $this->assertTrue( $valid->validate( "20" )->isValid() );
        $this->assertFalse( $valid->validate( 25 )->isValid() );
    }

    public function testNotSame ()
    {
        $valid = new \cPHP\Validator\Compare( "!==", 20 );

        $this->assertFalse( $valid->validate( 20 )->isValid() );
        $this->assertTrue(  $valid->validate( 25 )->isValid() );
        $this->assertTrue(  $valid->validate( "20" )->isValid() );
    }

    public function testNotEquals ()
    {
        $valid = new \cPHP\Validator\Compare( "!=", 20 );

        $this->assertFalse( $valid->validate( 20 )->isValid() );
        $this->assertTrue(  $valid->validate( 25 )->isValid() );
        $this->assertFalse( $valid->validate( "20" )->isValid() );


        $valid = new \cPHP\Validator\Compare( "<>", 20 );

        $this->assertFalse( $valid->validate( 20 )->isValid() );
        $this->assertTrue(  $valid->validate( 25 )->isValid() );
        $this->assertFalse( $valid->validate( "20" )->isValid() );
    }

}

?>