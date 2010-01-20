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

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_Query_Join extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a mock join object
     *
     * @return \r8\Query\Join
     */
    public function getMockJoin ( $table, $on = null )
    {
        return $this->getMock(
            '\r8\Query\Join',
            array('getJoinType'),
            array( $table, $on )
        );
    }

    public function testConstruct ()
    {
        $table = $this->getMock('\r8\iface\Query\From', array('toFromSQL'));
        $join = $this->getMockJoin( $table );
        $this->assertSame( $table, $join->getTable() );
        $this->assertNull( $join->getOn() );


        $table = $this->getMock('\r8\iface\Query\From', array('toFromSQL'));
        $on = $this->getMock('\r8\iface\Query\Where', array('toWhereSQL', 'getPrecedence'));
        $join = $this->getMockJoin( $table, $on );
        $this->assertSame( $table, $join->getTable() );
        $this->assertSame( $on, $join->getOn() );
    }

    public function testTableAccessors ()
    {
        $table = $this->getMock('\r8\iface\Query\From', array('toFromSQL'));
        $join = $this->getMockJoin( $table );
        $this->assertSame( $table, $join->getTable() );

        $table2 = $this->getMock('\r8\iface\Query\From', array('toFromSQL'));
        $this->assertSame( $join, $join->setTable($table2) );
        $this->assertSame( $table2, $join->getTable() );
    }

    public function testOnAccessors ()
    {
        $table = $this->getMock('\r8\iface\Query\From', array('toFromSQL'));
        $join = $this->getMockJoin( $table );
        $this->assertNull( $join->getOn() );
        $this->assertFalse( $join->onExists() );

        $on = $this->getMock('\r8\iface\Query\Where', array('toWhereSQL', 'getPrecedence'));
        $this->assertSame( $join, $join->setOn($on) );
        $this->assertSame( $on, $join->getOn() );
        $this->assertTrue( $join->onExists() );

        $this->assertSame( $join, $join->clearOn() );
        $this->assertNull( $join->getOn() );
        $this->assertFalse( $join->onExists() );
    }

    public function testToJoinSQL_WithoutOn ()
    {
        $link = new \r8\DB\Link( new \r8\DB\BlackHole\Link );

        $table = $this->getMock('\r8\iface\Query\From', array('toFromSQL'));
        $table->expects( $this->once() )
            ->method( "toFromSQL" )
            ->with( $this->equalTo( $link ) )
            ->will( $this->returnValue( "Table" ) );

        $join = $this->getMockJoin( $table );
        $join->expects( $this->once() )
            ->method( "getJoinType" )
            ->will( $this->returnValue( "INNER JOIN" ) );

        $this->assertSame(
            "INNER JOIN Table",
            $join->toJoinSQL($link)
        );
    }

    public function testToJoinSQL_WithOn ()
    {
        $link = new \r8\DB\Link( new \r8\DB\BlackHole\Link );

        $table = $this->getMock('\r8\iface\Query\From', array('toFromSQL'));
        $table->expects( $this->once() )
            ->method( "toFromSQL" )
            ->with( $this->equalTo( $link ) )
            ->will( $this->returnValue( "TblName" ) );

        $on = $this->getMock('\r8\iface\Query\Where', array('toWhereSQL', 'getPrecedence'));
        $on->expects( $this->once() )
            ->method( "toWhereSQL" )
            ->with( $this->equalTo( $link ) )
            ->will( $this->returnValue( "a = b" ) );

        $join = $this->getMockJoin( $table, $on );
        $join->expects( $this->once() )
            ->method( "getJoinType" )
            ->will( $this->returnValue( "LEFT JOIN" ) );

        $this->assertSame(
            "LEFT JOIN TblName ON a = b",
            $join->toJoinSQL($link)
        );
    }

}

?>