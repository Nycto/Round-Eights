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
class classes_Transform_Lifetime extends PHPUnit_Framework_TestCase
{

    public function testTo ()
    {
        $life = new \r8\Transform\Lifetime( 60 );
        $this->assertRegExp( '/^[0-9a-z]+:Data$/', $life->to( "Data" ) );
    }

    public function testFrom_NoDelimiter ()
    {
        $life = new \r8\Transform\Lifetime( 60 );

        try {
            $life->from( "Data" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Data $err ) {
            $this->assertSame( "Data does not contain a timestamp", $err->getMessage() );
        }
    }

    public function testFrom_NonTimestamp ()
    {
        $life = new \r8\Transform\Lifetime( 60 );

        try {
            $life->from( "#!blah^:Data" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Data $err ) {
            $this->assertSame( "Data does not contain a timestamp", $err->getMessage() );
        }
    }

    public function testFrom_Expired ()
    {
        $life = new \r8\Transform\Lifetime( 60 );

        try {
            $life->from( "a99:Data" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Data $err ) {
            $this->assertSame( "Data has expired", $err->getMessage() );
        }
    }

    public function testFrom_Future ()
    {
        $data = base_convert( time() + 10000, 10, 36 ) .":Data";

        $life = new \r8\Transform\Lifetime( 60 );

        try {
            $life->from( $data );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Data $err ) {
            $this->assertSame( "Timestamp is in the future", $err->getMessage() );
        }
    }

    public function testFrom_Valid ()
    {
        $data = base_convert( time() - 5, 10, 36 ) .":Data";

        $life = new \r8\Transform\Lifetime( 60 );
        $this->assertSame( "Data",  $life->from( $data ) );
    }

}

