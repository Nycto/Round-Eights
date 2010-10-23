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

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * Unit Tests
 */
class classes_query extends PHPUnit_Framework_TestCase
{

    public function testParseSQLName ()
    {
        $this->assertSame(
                array( "part" ),
                \r8\Query::parseSQLName( "part" )
            );

        $this->assertSame(
                array( "part" ),
                \r8\Query::parseSQLName( "  `  part  `  " )
            );

        $this->assertSame(
                array( "part1", "part2" ),
                \r8\Query::parseSQLName( "part1.part2" )
            );

        $this->assertSame(
                array( "part1", "part2", "part3" ),
                \r8\Query::parseSQLName( "   part1   .   part2 . part3  " )
            );

        $this->assertSame(
                array( "part1", "part2" ),
                \r8\Query::parseSQLName( "`part1`.`part2`" )
            );

        $this->assertSame(
                array( "part1", "part2", "part3" ),
                \r8\Query::parseSQLName( "  `part1`  .  `part2`  .   `part3`  " )
            );

        $this->assertSame(
                array(),
                \r8\Query::parseSQLName( "  .  .  " )
            );
    }

    public function testParseSQLAlias ()
    {
        $this->assertSame(
                array( null, null ),
                \r8\Query::parseSQLAlias( "  " )
            );

        $this->assertSame(
                array( "Expr", null ),
                \r8\Query::parseSQLAlias( "Expr" )
            );

        $this->assertSame(
                array( "Expr", "alias" ),
                \r8\Query::parseSQLAlias( "Expr AS alias" )
            );

        $this->assertSame(
                array( "`Expr`", "alias" ),
                \r8\Query::parseSQLAlias( "`Expr` AS `alias`" )
            );

        $this->assertSame(
                array( "`Expr AS `", "ASalias" ),
                \r8\Query::parseSQLAlias( "`Expr AS ` AS ` AS alias`" )
            );

        $this->assertSame(
                array( "Expr", null ),
                \r8\Query::parseSQLAlias( "Expr AS " )
            );

        $this->assertSame(
                array( "Expr", "ASalias" ),
                \r8\Query::parseSQLAlias( "Expr AS AS alias" )
            );

        $this->assertSame(
                array( "`Expr\\`quoted`", null ),
                \r8\Query::parseSQLAlias( "`Expr\\`quoted` AS " )
            );
    }

    public function testSelect ()
    {
        $this->assertThat(
                \r8\Query::select(),
                $this->isInstanceOf('\r8\Query\Select')
            );
    }

}

