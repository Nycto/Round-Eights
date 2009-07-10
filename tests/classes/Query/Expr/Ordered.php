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

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_query_expr_ordered extends PHPUnit_Framework_TestCase
{
    public function testFromString ()
    {
        $this->assertEquals(
            new \h2o\Query\Expr\Ordered(
                new \h2o\Query\Atom\Field("fld"),
                "ASC"
            ),
            \h2o\Query\Expr\Ordered::fromString("fld ASC")
        );

        $this->assertEquals(
            new \h2o\Query\Expr\Ordered(
                new \h2o\Query\Atom\Field("fld")
            ),
            \h2o\Query\Expr\Ordered::fromString("fld")
        );

        $this->assertEquals(
            new \h2o\Query\Expr\Ordered(
                new \h2o\Query\Atom\Field("fld", "db"),
                "DESC"
            ),
            \h2o\Query\Expr\Ordered::fromString("db.fld DESC")
        );

        $this->assertEquals(
            new \h2o\Query\Expr\Ordered(
                new \h2o\Query\Atom\Field("fld", "db"),
                "desc"
            ),
            \h2o\Query\Expr\Ordered::fromString("`db`.`fld` desc")
        );
    }

    public function testConstruct ()
    {
        $atom = new \h2o\Query\Atom\Field("fld");
        $alias = new \h2o\Query\Expr\Ordered( $atom );
        $this->assertSame( $atom, $alias->getAtom() );
        $this->assertNull( $alias->getOrder() );

        $atom = new \h2o\Query\Atom\Field("fld");
        $alias = new \h2o\Query\Expr\Ordered( $atom, "asc" );
        $this->assertSame( $atom, $alias->getAtom() );
        $this->assertSame( "ASC", $alias->getOrder() );
    }

    public function testSetOrder()
    {
        $atom = new \h2o\Query\Atom\Field("fld");
        $alias = new \h2o\Query\Expr\Ordered( $atom );

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
        $link = new \h2o\DB\BlackHole\Link;

        $atom = new \h2o\Query\Atom\Field("fld");
        $alias = new \h2o\Query\Expr\Ordered( $atom );

        $this->assertSame( "`fld`", $alias->toOrderedSQL( $link ) );

        $alias->setOrder( "ASC" );
        $this->assertSame( "`fld` ASC", $alias->toOrderedSQL( $link ) );

        $alias->setOrder( "DESC" );
        $this->assertSame( "`fld` DESC", $alias->toOrderedSQL( $link ) );
    }

}

?>