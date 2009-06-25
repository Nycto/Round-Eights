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
class classes_query_where_logicand extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a test WHERE clause
     *
     * @return \cPHP\iface\Query\Where
     */
    public function getTestClause ( $precedence, $sql )
    {
        $where = $this->getMock('cPHP\iface\Query\Where');
        $where->expects( $this->any() )
            ->method( "getPrecedence" )
            ->will( $this->returnValue( $precedence ) );
        $where->expects( $this->any() )
            ->method( "toWhereSQL" )
            ->will( $this->returnValue( $sql ) );

        return $where;
    }

    public function testGetPrecedence ()
    {
        $and = new \cPHP\Query\Where\LogicAnd;
        $this->assertSame( 70, $and->getPrecedence() );

        // When there is only one clause, we should be doing some masking
        $and->addClause( $this->getTestClause(50, "Lower") );
        $this->assertSame( 50, $and->getPrecedence() );

        // The masking should disappear when there is more than 1
        $and->addClause( $this->getTestClause(50, "Lower") );
        $this->assertSame( 70, $and->getPrecedence() );

        $and->addClause( $this->getTestClause(50, "Lower") );
        $this->assertSame( 70, $and->getPrecedence() );
    }

    public function testToWhereSQL ()
    {
        $and = new \cPHP\Query\Where\LogicAnd;

        // Create a lower precedence WHERE clause
        $and->addClause( $this->getTestClause(50, "Lower") );

        // Create a higher precedence WHERE clause
        $and->addClause( $this->getTestClause(100, "Higher") );

        // Create an equal precedence WHERE clause
        $and->addClause( $this->getTestClause(70, "Equals") );

        $link = new \cPHP\DB\BlackHole\Link;
        $this->assertSame(
        		"(Lower) AND Higher AND Equals",
                $and->toWhereSQL( $link )
            );
    }

}

?>