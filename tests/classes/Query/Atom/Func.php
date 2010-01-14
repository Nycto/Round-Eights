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
 * unit tests
 */
class classes_query_atom_func extends PHPUnit_Framework_TestCase
{

    public function testConstruct ()
    {
        $func = new \r8\Query\Atom\Func( "now" );
        $this->assertSame( "NOW", $func->getFunc() );

        $func = new \r8\Query\Atom\Func( "!@#$ IF   .." );
        $this->assertSame( "IF", $func->getFunc() );

        $func = new \r8\Query\Atom\Func( "ABC_123" );
        $this->assertSame( "ABC_123", $func->getFunc() );
    }

    public function testConstruct_args ()
    {
        $arg1 = $this->getMock('\r8\iface\Query\Atom');
        $arg2 = $this->getMock('\r8\iface\Query\Atom');

        $func = new \r8\Query\Atom\Func( "func", $arg1, $arg2 );
        $this->assertSame( "FUNC", $func->getFunc() );
        $this->assertSame( array( $arg1, $arg2 ), $func->getArgs() );
    }

    public function testAddArg ()
    {
        $func = new \r8\Query\Atom\Func( "test" );
        $this->assertSame( array(), $func->getArgs() );

        $arg1 = $this->getMock('\r8\iface\Query\Atom');
        $this->assertSame( $func, $func->addArg($arg1) );
        $this->assertSame( array( $arg1 ), $func->getArgs() );

        $arg2 = $this->getMock('\r8\iface\Query\Atom');
        $this->assertSame( $func, $func->addArg($arg2) );
        $this->assertSame( array( $arg1, $arg2 ), $func->getArgs() );

        $this->assertSame( $func, $func->addArg($arg1) );
        $this->assertSame( array( $arg1, $arg2, $arg1 ), $func->getArgs() );
    }

    public function testSetArgs ()
    {
        $func = new \r8\Query\Atom\Func( "test" );
        $this->assertSame( array(), $func->getArgs() );

        $arg1 = $this->getMock('\r8\iface\Query\Atom');
        $arg2 = $this->getMock('\r8\iface\Query\Atom');

        $this->assertSame(
                $func,
                $func->setArgs( array( $arg1, $arg2 ) )
            );
        $this->assertSame( array( $arg1, $arg2 ), $func->getArgs() );
    }

    public function testToAtomSQL_noArgs ()
    {
        $link = $this->getMock("r8\iface\DB\Link");

        $fld = new \r8\Query\Atom\Func("now");
        $this->assertSame( "NOW()", $fld->toAtomSQL( $link ) );
    }

    public function testToAtomSQL_withArgs ()
    {
        $link = new \r8\DB\Link( new \r8\DB\BlackHole\Link );

        $fld = new \r8\Query\Atom\Func(
        		"Func",
                new \r8\Query\Atom\Primitive(5),
                new \r8\Query\Atom\Field("fld")
            );

        $this->assertSame( "FUNC(5, `fld`)", $fld->toAtomSQL( $link ) );
    }

}

?>