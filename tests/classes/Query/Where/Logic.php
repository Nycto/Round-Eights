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
class classes_query_where_logic extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a mock logic object
     *
     * @return \h2o\Query\Where\Logic
     */
    public function getTestLogic ()
    {
        $logic = $this->getMock(
        		'h2o\Query\Where\Logic',
                array( "getPrecedence", "getDelimiter" )
            );

        $logic->expects( $this->once() )
            ->method( "getPrecedence" )
            ->will( $this->returnValue(20) );

        $logic->expects( $this->once() )
            ->method( "getDelimiter" )
            ->will( $this->returnValue( "DELIM" ) );

        return $logic;
    }

    /**
     * Returns a test WHERE clause
     *
     * @return \h2o\iface\Query\Where
     */
    public function getTestClause ( $precedence, $sql )
    {
        $where = $this->getMock('h2o\iface\Query\Where');
        $where->expects( $this->any() )
            ->method( "getPrecedence" )
            ->will( $this->returnValue( $precedence ) );
        $where->expects( $this->any() )
            ->method( "toWhereSQL" )
            ->will( $this->returnValue( $sql ) );

        return $where;
    }

    public function testConstruct ()
    {
        $where1 = $this->getTestClause( 20, "Where" );
        $where2 = $this->getTestClause( 20, "Where" );
        $where3 = $this->getTestClause( 20, "Where" );

        $logic = $this->getMock(
        		'h2o\Query\Where\Logic',
                array( "getPrecedence", "getDelimiter" ),
                array(
                    $where1, "invalid", $where2, new stdClass, $where3
                )
            );

        $this->assertSame(
                array( $where1, $where2, $where3 ),
                $logic->getClauses()
            );
    }

    public function testClauseAccessors ()
    {
        $logic = $this->getMock(
        		'h2o\Query\Where\Logic',
                array( "getPrecedence", "getDelimiter" )
            );

        $this->assertSame( array(), $logic->getClauses() );

        $where1 = $this->getMock('h2o\iface\Query\Where');
        $this->assertSame( $logic, $logic->addClause($where1) );
        $this->assertSame( array( $where1 ), $logic->getClauses() );

        $where2 = $this->getMock('h2o\iface\Query\Where');
        $this->assertSame( $logic, $logic->addClause($where2) );
        $this->assertSame( array( $where1, $where2 ), $logic->getClauses() );

        $this->assertSame( $logic, $logic->addClause($where1) );
        $this->assertSame( array( $where1, $where2 ), $logic->getClauses() );

        $this->assertSame( $logic, $logic->clearClauses() );
        $this->assertSame( array(), $logic->getClauses() );
    }

    public function testCountClauses ()
    {
        $logic = $this->getMock(
        		'h2o\Query\Where\Logic',
                array( "getPrecedence", "getDelimiter" )
            );
        $this->assertSame( 0, $logic->countClauses() );

        $logic->addClause( $this->getMock('h2o\iface\Query\Where') );
        $this->assertSame( 1, $logic->countClauses() );

        $logic->addClause( $this->getMock('h2o\iface\Query\Where') );
        $this->assertSame( 2, $logic->countClauses() );

        $logic->addClause( $this->getMock('h2o\iface\Query\Where') );
        $this->assertSame( 3, $logic->countClauses() );

        $logic->clearClauses();
        $this->assertSame( 0, $logic->countClauses() );
    }

    public function testToWhereSQL_lowHigh ()
    {
        $logic = $this->getTestLogic();

        $logic->addClause( $this->getTestClause( 30, "Higher" ) );
        $logic->addClause( $this->getTestClause( 10, "Lower" ) );
        $logic->addClause( $this->getTestClause( 30, "Higher" ) );
        $logic->addClause( $this->getTestClause( 10, "Lower" ) );

        // Run the actual conversion
        $link = new \h2o\DB\BlackHole\Link;
        $this->assertSame(
        		"Higher DELIM (Lower) DELIM Higher DELIM (Lower)",
                $logic->toWhereSQL( $link )
            );
    }

    public function testToWhereSQL_equals ()
    {
        $logic = $this->getTestLogic();

        // Create an equal precedence WHERE clause
        $logic->addClause( $this->getTestClause( 20, "Equals1" ) );
        $logic->addClause( $this->getTestClause( 20, "Equals2" ) );
        $logic->addClause( $this->getTestClause( 20, "Equals3" ) );

        // Run the actual conversion
        $link = new \h2o\DB\BlackHole\Link;
        $this->assertSame(
        		"Equals1 DELIM Equals2 DELIM Equals3",
                $logic->toWhereSQL( $link )
            );
    }

    public function testToWhereSQL_blank ()
    {
        $logic = $this->getTestLogic();

        // Create an equal precedence WHERE clause
        $logic->addClause( $this->getTestClause( 10, "Lower" ) );
        $logic->addClause( $this->getTestClause( 20, "   " ) );
        $logic->addClause( $this->getTestClause( 30, "Higher" ) );

        // Run the actual conversion
        $link = new \h2o\DB\BlackHole\Link;
        $this->assertSame(
        		"(Lower) DELIM Higher",
                $logic->toWhereSQL( $link )
            );
    }

}

?>