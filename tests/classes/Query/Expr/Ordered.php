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
class classes_Query_Expr_Ordered extends PHPUnit_Framework_TestCase
{

    public function testFromString ()
    {
        $this->assertEquals(
            new \r8\Query\Expr\Ordered(
                new \r8\Query\Atom\Field("fld"),
                "ASC"
            ),
            \r8\Query\Expr\Ordered::fromString("fld ASC")
        );

        $this->assertEquals(
            new \r8\Query\Expr\Ordered(
                new \r8\Query\Atom\Field("fld")
            ),
            \r8\Query\Expr\Ordered::fromString("fld")
        );

        $this->assertEquals(
            new \r8\Query\Expr\Ordered(
                new \r8\Query\Atom\Field("fld", "db"),
                "DESC"
            ),
            \r8\Query\Expr\Ordered::fromString("db.fld DESC")
        );

        $this->assertEquals(
            new \r8\Query\Expr\Ordered(
                new \r8\Query\Atom\Field("fld", "db"),
                "desc"
            ),
            \r8\Query\Expr\Ordered::fromString("`db`.`fld` desc")
        );
    }

    public function testConstruct ()
    {
        $atom = new \r8\Query\Atom\Field("fld");
        $alias = new \r8\Query\Expr\Ordered( $atom );
        $this->assertSame( $atom, $alias->getAtom() );
        $this->assertNull( $alias->getOrder() );

        $atom = new \r8\Query\Atom\Field("fld");
        $alias = new \r8\Query\Expr\Ordered( $atom, "asc" );
        $this->assertSame( $atom, $alias->getAtom() );
        $this->assertSame( "ASC", $alias->getOrder() );
    }

    public function testSetOrder()
    {
        $atom = new \r8\Query\Atom\Field("fld");
        $alias = new \r8\Query\Expr\Ordered( $atom );

        $this->assertSame( $alias, $alias->setOrder("ASC") );
        $this->assertSame( "ASC", $alias->getOrder() );

        $this->assertSame( $alias, $alias->setOrder( null ) );
        $this->assertNull( $alias->getOrder() );

        $this->assertSame( $alias, $alias->setOrder("  d @# e s }{} c ") );
        $this->assertSame( "DESC", $alias->getOrder() );

        $this->assertSame( $alias, $alias->setOrder("non-enum") );
        $this->assertNull( $alias->getOrder() );

        $this->assertSame( $alias, $alias->setOrder( TRUE ) );
        $this->assertSame( "ASC", $alias->getOrder() );

        $this->assertSame( $alias, $alias->setOrder( FALSE ) );
        $this->assertSame( "DESC", $alias->getOrder() );

        $this->assertSame( $alias, $alias->setOrder( 1 ) );
        $this->assertSame( "ASC", $alias->getOrder() );

        $this->assertSame( $alias, $alias->setOrder( 0 ) );
        $this->assertSame( "DESC", $alias->getOrder() );
    }

    public function testToOrderedSQL ()
    {
        $link = new \r8\DB\Link( new \r8\DB\BlackHole\Link );

        $atom = new \r8\Query\Atom\Field("fld");
        $alias = new \r8\Query\Expr\Ordered( $atom );

        $this->assertSame( "fld", $alias->toOrderedSQL( $link ) );

        $alias->setOrder( "ASC" );
        $this->assertSame( "fld ASC", $alias->toOrderedSQL( $link ) );

        $alias->setOrder( "DESC" );
        $this->assertSame( "fld DESC", $alias->toOrderedSQL( $link ) );
    }

}

?>