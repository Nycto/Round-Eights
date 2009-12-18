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
class classes_query_where_logicor extends PHPUnit_Framework_TestCase
{
    /**
     * Returns a test WHERE clause
     *
     * @return \r8\iface\Query\Where
     */
    public function getTestClause ( $precedence, $sql )
    {
        $where = $this->getMock('r8\iface\Query\Where');
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
        $logic = new \r8\Query\Where\LogicOr;
        $this->assertSame( 50, $logic->getPrecedence() );

        // When there is only one clause, we should be doing some masking
        $logic->addClause( $this->getTestClause(30, "Lower") );
        $this->assertSame( 30, $logic->getPrecedence() );

        // The masking should disappear when there is more than 1
        $logic->addClause( $this->getTestClause(30, "Lower") );
        $this->assertSame( 50, $logic->getPrecedence() );

        $logic->addClause( $this->getTestClause(30, "Lower") );
        $this->assertSame( 50, $logic->getPrecedence() );
    }

    public function testToWhereSQL ()
    {
        $or = new \r8\Query\Where\LogicOr;

        // Create a lower precedence WHERE clause
        $or->addClause( $this->getTestClause(30, "Lower") );

        // Create a higher precedence WHERE clause
        $or->addClause( $this->getTestClause(100, "Higher") );

        // Create an equal precedence WHERE clause
        $or->addClause( $this->getTestClause(50, "Equals") );

        $link = new \r8\DB\BlackHole\Link;
        $this->assertSame(
        		"(Lower) OR Higher OR Equals",
                $or->toWhereSQL( $link )
            );
    }

}

?>