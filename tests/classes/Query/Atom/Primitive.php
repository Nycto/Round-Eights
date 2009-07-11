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
class classes_query_atom_primitive extends PHPUnit_Framework_TestCase
{

    public function testGetValue ()
    {
        $prim = new \h2o\Query\Atom\Primitive( 20 );
        $this->assertSame( 20, $prim->getValue() );

        $prim = new \h2o\Query\Atom\Primitive( 3.14 );
        $this->assertSame( 3.14, $prim->getValue() );

        $prim = new \h2o\Query\Atom\Primitive( "data" );
        $this->assertSame( "data", $prim->getValue() );

        $obj = new stdClass;
        $prim = new \h2o\Query\Atom\Primitive( $obj );
        $this->assertSame( $obj, $prim->getValue() );
    }

    public function testToAtomSQL ()
    {
        $link = new \h2o\DB\BlackHole\Link;

        $prim = new \h2o\Query\Atom\Primitive( 20 );
        $this->assertSame( "20", $prim->toAtomSQL( $link ) );

        $prim = new \h2o\Query\Atom\Primitive( 3.14 );
        $this->assertSame( "3.14", $prim->toAtomSQL( $link ) );

        $prim = new \h2o\Query\Atom\Primitive( "data" );
        $this->assertSame( "'data'", $prim->toAtomSQL( $link ) );

        $prim = new \h2o\Query\Atom\Primitive( null );
        $this->assertSame( "NULL", $prim->toAtomSQL( $link ) );


        $obj = new stdClass;
        $obj->key = "str";

        $prim = new \h2o\Query\Atom\Primitive( $obj );
        $this->assertSame( "'str'", $prim->toAtomSQL( $link ) );
    }

}

?>