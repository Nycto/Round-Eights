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
class classes_query_Expr_Aliased extends PHPUnit_Framework_TestCase
{

    public function testFromString ()
    {
        $this->assertEquals(
            new \cPHP\Query\Expr\Aliased(
                new \cPHP\Query\Atom\Field("fld"),
                "Ailee"
            ),
            \cPHP\Query\Expr\Aliased::fromString("fld AS Ailee")
        );

        $this->assertEquals(
            new \cPHP\Query\Expr\Aliased(
                new \cPHP\Query\Atom\Field("fld")
            ),
            \cPHP\Query\Expr\Aliased::fromString("fld")
        );

        $this->assertEquals(
            new \cPHP\Query\Expr\Aliased(
                new \cPHP\Query\Atom\Field("fld", "db"),
                "Ailee"
            ),
            \cPHP\Query\Expr\Aliased::fromString("db.fld AS Ailee")
        );

        $this->assertEquals(
            new \cPHP\Query\Expr\Aliased(
                new \cPHP\Query\Atom\Field("fld", "db"),
                "Ailee"
            ),
            \cPHP\Query\Expr\Aliased::fromString("`db`.`fld` AS `Ailee`")
        );
    }

    public function testConstruct ()
    {
        $atom = new \cPHP\Query\Atom\Field("fld");
        $alias = new \cPHP\Query\Expr\Aliased( $atom );
        $this->assertSame( $atom, $alias->getAtom() );
        $this->assertNull( $alias->getAlias() );

        $atom = new \cPHP\Query\Atom\Field("fld");
        $alias = new \cPHP\Query\Expr\Aliased( $atom, "alias" );
        $this->assertSame( $atom, $alias->getAtom() );
        $this->assertSame( "alias", $alias->getAlias() );
    }

    public function testSetAlias ()
    {
        $atom = new \cPHP\Query\Atom\Field("fld");
        $alias = new \cPHP\Query\Expr\Aliased( $atom );

        $this->assertSame( $alias, $alias->setAlias("Als") );
        $this->assertSame( "Als", $alias->getAlias() );

        $this->assertSame( $alias, $alias->setAlias(" !@#$ F l: d") );
        $this->assertSame( "Fld", $alias->getAlias() );
    }

    public function testToSelectSQL ()
    {
        $link = new \cPHP\DB\BlackHole\Link;

        $atom = new \cPHP\Query\Atom\Field("fld");
        $alias = new \cPHP\Query\Expr\Aliased( $atom );

        $this->assertSame( "`fld`", $alias->toSelectSQL( $link ) );

        $alias->setAlias( "wakka");
        $this->assertSame( "`fld` AS wakka", $alias->toSelectSQL( $link ) );

    }

}

?>