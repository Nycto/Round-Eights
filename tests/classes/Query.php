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
                \cPHP\Query::parseSQLName( "part" )
            );

        $this->assertSame(
                array( "part" ),
                \cPHP\Query::parseSQLName( "  `  part  `  " )
            );

        $this->assertSame(
                array( "part1", "part2" ),
                \cPHP\Query::parseSQLName( "part1.part2" )
            );

        $this->assertSame(
                array( "part1", "part2", "part3" ),
                \cPHP\Query::parseSQLName( "   part1   .   part2 . part3  " )
            );

        $this->assertSame(
                array( "part1", "part2" ),
                \cPHP\Query::parseSQLName( "`part1`.`part2`" )
            );

        $this->assertSame(
                array( "part1", "part2", "part3" ),
                \cPHP\Query::parseSQLName( "  `part1`  .  `part2`  .   `part3`  " )
            );

        $this->assertSame(
                array(),
                \cPHP\Query::parseSQLName( "  .  .  " )
            );
    }

    public function testParseSQLAlias ()
    {
        $this->assertSame(
                array( null, null ),
                \cPHP\Query::parseSQLAlias( "  " )
            );

        $this->assertSame(
                array( "Expr", null ),
                \cPHP\Query::parseSQLAlias( "Expr" )
            );

        $this->assertSame(
                array( "Expr", "alias" ),
                \cPHP\Query::parseSQLAlias( "Expr AS alias" )
            );

        $this->assertSame(
                array( "`Expr`", "alias" ),
                \cPHP\Query::parseSQLAlias( "`Expr` AS `alias`" )
            );

        $this->assertSame(
                array( "`Expr AS `", "ASalias" ),
                \cPHP\Query::parseSQLAlias( "`Expr AS ` AS ` AS alias`" )
            );

        $this->assertSame(
                array( "Expr", null ),
                \cPHP\Query::parseSQLAlias( "Expr AS " )
            );

        $this->assertSame(
                array( "Expr", "ASalias" ),
                \cPHP\Query::parseSQLAlias( "Expr AS AS alias" )
            );

        $this->assertSame(
                array( "`Expr\\`quoted`", null ),
                \cPHP\Query::parseSQLAlias( "`Expr\\`quoted` AS " )
            );
    }

}

?>