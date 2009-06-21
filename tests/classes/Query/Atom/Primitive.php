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

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_query_atom_primitive extends PHPUnit_Framework_TestCase
{

    public function testGetValue ()
    {
        $prim = new \cPHP\Query\Atom\Primitive( 20 );
        $this->assertSame( 20, $prim->getValue() );

        $prim = new \cPHP\Query\Atom\Primitive( 3.14 );
        $this->assertSame( 3.14, $prim->getValue() );

        $prim = new \cPHP\Query\Atom\Primitive( "data" );
        $this->assertSame( "data", $prim->getValue() );

        $obj = new stdClass;
        $prim = new \cPHP\Query\Atom\Primitive( $obj );
        $this->assertSame( $obj, $prim->getValue() );
    }

    public function testToAtomSQL ()
    {
        $link = new \cPHP\DB\BlackHole\Link;

        $prim = new \cPHP\Query\Atom\Primitive( 20 );
        $this->assertSame( "20", $prim->toAtomSQL( $link ) );

        $prim = new \cPHP\Query\Atom\Primitive( 3.14 );
        $this->assertSame( "3.14", $prim->toAtomSQL( $link ) );

        $prim = new \cPHP\Query\Atom\Primitive( "data" );
        $this->assertSame( "'data'", $prim->toAtomSQL( $link ) );

        $prim = new \cPHP\Query\Atom\Primitive( null );
        $this->assertSame( "NULL", $prim->toAtomSQL( $link ) );


        $obj = new stdClass;
        $obj->key = "str";

        $prim = new \cPHP\Query\Atom\Primitive( $obj );
        $this->assertSame( "'str'", $prim->toAtomSQL( $link ) );
    }

}

?>