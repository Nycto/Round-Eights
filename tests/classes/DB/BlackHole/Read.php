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

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_db_blackhole_read extends PHPUnit_Framework_TestCase
{

    public function testCount ()
    {
        $result = new \h2o\DB\BlackHole\Read( null, "QUERY" );
        $this->assertSame( 0, $result->count() );
    }

    public function testIteration ()
    {
        $result = new \h2o\DB\BlackHole\Read( null, "QUERY" );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array(),
            $result
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array(),
            $result
        );
    }

    public function testFields ()
    {
        $result = new \h2o\DB\BlackHole\Read( null, "QUERY" );
        $this->assertSame( array(), $result->getFields() );
    }

    public function testFree ()
    {
        $result = new \h2o\DB\BlackHole\Read( null, "QUERY" );
        $this->assertSame( $result, $result->free() );
    }

}

?>