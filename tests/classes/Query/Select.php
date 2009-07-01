<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of raindropPHP.
 *
 * raindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * raindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with raindropPHP. If not, see <http://www.raindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_query_select extends PHPUnit_Framework_TestCase
{

    public function testDistinctAccessors ()
    {
        $select = new \h2o\Query\Select;
        $this->assertFalse( $select->isDistinct() );

        $this->assertSame( $select, $select->setDistinct(TRUE) );
        $this->assertTrue( $select->isDistinct() );

        $this->assertSame( $select, $select->setDistinct(FALSE) );
        $this->assertFalse( $select->isDistinct() );
    }

    public function testDistinct ()
    {
        $select = new \h2o\Query\Select;

        $this->assertSame( $select, $select->distinct() );
        $this->assertTrue( $select->isDistinct() );
    }

    public function testFoundRows ()
    {
        $select = new \h2o\Query\Select;
        $this->assertFalse( $select->getFoundRows() );

        $this->assertSame( $select, $select->setFoundRows(TRUE) );
        $this->assertTrue( $select->getFoundRows() );

        $this->assertSame( $select, $select->setFoundRows(FALSE) );
        $this->assertFalse( $select->getFoundRows() );

    }

    public function testFieldAccessors ()
    {
        $select = new \h2o\Query\Select;
        $this->assertSame( array(), $select->getFields() );

        $fld1 = $this->getMock('h2o\iface\Query\Selectable');
        $this->assertSame( $select, $select->addField( $fld1 ) );
        $this->assertSame( array( $fld1 ), $select->getFields() );

        // Ensure you can't add the same field twice
        $this->assertSame( $select, $select->addField( $fld1 ) );
        $this->assertSame( array( $fld1 ), $select->getFields() );

        $fld2 = $this->getMock('h2o\iface\Query\Selectable');
        $this->assertSame( $select, $select->addField( $fld2 ) );
        $this->assertSame( array( $fld1, $fld2 ), $select->getFields() );

        $this->assertSame( $select, $select->clearFields() );
        $this->assertSame( array(), $select->getFields() );
    }

    public function testFields ()
    {
        $select = new \h2o\Query\Select;

        $this->assertSame(
                $select,
                $select->fields( "fld1", "tbl.fld2 AS aylee" )
            );

        $this->assertEquals(
            array(
                new \h2o\Query\Expr\Aliased(
                    new \h2o\Query\Atom\Field("fld1")
                ),
                new \h2o\Query\Expr\Aliased(
                    new \h2o\Query\Atom\Field("fld2", "tbl"),
                    "aylee"
                )
            ),
            $select->getFields()
        );

        $field = $this->getMock('h2o\iface\Query\Selectable');
        $this->assertSame( $select, $select->fields( $field ) );

        $this->assertEquals(
            array(
                new \h2o\Query\Expr\Aliased(
                    new \h2o\Query\Atom\Field("fld1")
                ),
                new \h2o\Query\Expr\Aliased(
                    new \h2o\Query\Atom\Field("fld2", "tbl"),
                    "aylee"
                ),
                $field
            ),
            $select->getFields()
        );
    }

    public function testFromAccessors ()
    {
        $obj = new \h2o\Query\Select;
        $this->assertFalse( $obj->fromExists() );
        $this->assertNull( $obj->getFrom() );

        $from = $this->getMock('h2o\iface\Query\From');

        $this->assertSame( $obj, $obj->setFrom( $from ) );
        $this->assertTrue( $obj->fromExists() );
        $this->assertSame( $from, $obj->getFrom() );

        $this->assertSame( $obj, $obj->clearFrom() );
        $this->assertFalse( $obj->fromExists() );
        $this->assertNull( $obj->getFrom() );
    }

    public function testFrom ()
    {
        $obj = new \h2o\Query\Select;

        $this->assertSame( $obj, $obj->from("db.table") );
        $this->assertEquals(
                new \h2o\Query\From\Table("table", "db"),
                $obj->getFrom()
            );

        $table = new \h2o\Query\From\Table("table", "db");
        $this->assertSame( $obj, $obj->from( $table ) );
        $this->assertSame( $table, $obj->getFrom() );
    }

    public function testWhereAccessors ()
    {
        $obj = new \h2o\Query\Select;
        $this->assertFalse( $obj->whereExists() );
        $this->assertNull( $obj->getWhere() );

        $where = $this->getMock('h2o\iface\Query\Where');

        $this->assertSame( $obj, $obj->setWhere( $where ) );
        $this->assertTrue( $obj->whereExists() );
        $this->assertSame( $where, $obj->getWhere() );

        $this->assertSame( $obj, $obj->clearWhere() );
        $this->assertFalse( $obj->whereExists() );
        $this->assertNull( $obj->getWhere() );
    }

    public function testWhere ()
    {
        $obj = new \h2o\Query\Select;

        $where = $this->getMock('h2o\iface\Query\Where');
        $this->assertSame( $obj, $obj->where($where) );
        $this->assertSame( $where, $obj->getWhere() );

        $this->assertSame( $obj, $obj->where("Field = 'string'") );
        $this->assertEquals(
            new \h2o\Query\Where\Raw("Field = 'string'"),
            $obj->getWhere()
        );
    }

    public function testAndWhere ()
    {
        $obj = new \h2o\Query\Select;

        $this->assertSame( $obj, $obj->andWhere("A = B") );

        $and = $obj->getWhere();
        $this->assertEquals(
                new \h2o\Query\Where\LogicAnd(
                        new \h2o\Query\Where\Raw("A = B")
                    ),
                $and
            );

        $clause = $this->getMock('h2o\iface\Query\Where');
        $this->assertSame( $obj, $obj->andWhere( $clause ) );
        $this->assertSame( $and, $obj->getWhere() );
        $this->assertEquals(
                new \h2o\Query\Where\LogicAnd(
                        new \h2o\Query\Where\Raw("A = B"),
                        $clause
                    ),
                $and
            );
    }

    public function testOrWhere ()
    {
        $obj = new \h2o\Query\Select;

        $this->assertSame( $obj, $obj->orWhere("A = B") );

        $and = $obj->getWhere();
        $this->assertEquals(
                new \h2o\Query\Where\LogicOr(
                        new \h2o\Query\Where\Raw("A = B")
                    ),
                $and
            );

        $clause = $this->getMock('h2o\iface\Query\Where');
        $this->assertSame( $obj, $obj->orWhere( $clause ) );
        $this->assertSame( $and, $obj->getWhere() );
        $this->assertEquals(
                new \h2o\Query\Where\LogicOr(
                        new \h2o\Query\Where\Raw("A = B"),
                        $clause
                    ),
                $and
            );
    }

    public function testOrderAccessors ()
    {
        $select = new \h2o\Query\Select;
        $this->assertSame( array(), $select->getOrder() );

        $fld1 = $this->getMock('h2o\iface\Query\Ordered');
        $this->assertSame( $select, $select->addOrder( $fld1 ) );
        $this->assertSame( array( $fld1 ), $select->getOrder() );

        // Ensure you can't add the same field twice
        $this->assertSame( $select, $select->addOrder( $fld1 ) );
        $this->assertSame( array( $fld1 ), $select->getOrder() );

        $fld2 = $this->getMock('h2o\iface\Query\Ordered');
        $this->assertSame( $select, $select->addOrder( $fld2 ) );
        $this->assertSame( array( $fld1, $fld2 ), $select->getOrder() );

        $this->assertSame( $select, $select->clearOrder() );
        $this->assertSame( array(), $select->getOrder() );
    }

    public function testOrderBy ()
    {
        $select = new \h2o\Query\Select;

        $this->assertSame(
                $select,
                $select->orderBy( "fld1", "tbl.fld2 ASC" )
            );

        $this->assertEquals(
            array(
                new \h2o\Query\Expr\Ordered(
                    new \h2o\Query\Atom\Field("fld1")
                ),
                new \h2o\Query\Expr\Ordered(
                    new \h2o\Query\Atom\Field("fld2", "tbl"),
                    "ASC"
                )
            ),
            $select->getOrder()
        );

        $field = $this->getMock('h2o\iface\Query\Ordered');
        $this->assertSame( $select, $select->orderBy( $field ) );

        $this->assertEquals(
            array(
                new \h2o\Query\Expr\Ordered(
                    new \h2o\Query\Atom\Field("fld1")
                ),
                new \h2o\Query\Expr\Ordered(
                    new \h2o\Query\Atom\Field("fld2", "tbl"),
                    "ASC"
                ),
                $field
            ),
            $select->getOrder()
        );
    }

    public function testGroupBy ()
    {
        $select = new \h2o\Query\Select;

        $this->assertSame(
                $select,
                $select->groupBy( "fld1", "tbl.fld2 ASC" )
            );

        $this->assertEquals(
            array(
                new \h2o\Query\Expr\Ordered(
                    new \h2o\Query\Atom\Field("fld1")
                ),
                new \h2o\Query\Expr\Ordered(
                    new \h2o\Query\Atom\Field("fld2", "tbl"),
                    "ASC"
                )
            ),
            $select->getGroup()
        );

        $field = $this->getMock('h2o\iface\Query\Ordered');
        $this->assertSame( $select, $select->groupBy( $field ) );

        $this->assertEquals(
            array(
                new \h2o\Query\Expr\Ordered(
                    new \h2o\Query\Atom\Field("fld1")
                ),
                new \h2o\Query\Expr\Ordered(
                    new \h2o\Query\Atom\Field("fld2", "tbl"),
                    "ASC"
                ),
                $field
            ),
            $select->getGroup()
        );
    }

    public function testGroupAccessors ()
    {
        $select = new \h2o\Query\Select;
        $this->assertSame( array(), $select->getGroup() );

        $fld1 = $this->getMock('h2o\iface\Query\Ordered');
        $this->assertSame( $select, $select->addGroup( $fld1 ) );
        $this->assertSame( array( $fld1 ), $select->getGroup() );

        // Ensure you can't add the same field twice
        $this->assertSame( $select, $select->addGroup( $fld1 ) );
        $this->assertSame( array( $fld1 ), $select->getGroup() );

        $fld2 = $this->getMock('h2o\iface\Query\Ordered');
        $this->assertSame( $select, $select->addGroup( $fld2 ) );
        $this->assertSame( array( $fld1, $fld2 ), $select->getGroup() );

        $this->assertSame( $select, $select->clearGroup() );
        $this->assertSame( array(), $select->getGroup() );
    }

    public function testHavingAccessors ()
    {
        $obj = new \h2o\Query\Select;
        $this->assertFalse( $obj->havingExists() );
        $this->assertNull( $obj->getHaving() );

        $having = $this->getMock('h2o\iface\Query\Where');

        $this->assertSame( $obj, $obj->setHaving( $having ) );
        $this->assertTrue( $obj->havingExists() );
        $this->assertSame( $having, $obj->getHaving() );

        $this->assertSame( $obj, $obj->clearHaving() );
        $this->assertFalse( $obj->havingExists() );
        $this->assertNull( $obj->getHaving() );
    }

    public function testHaving ()
    {
        $obj = new \h2o\Query\Select;

        $having = $this->getMock('h2o\iface\Query\Where');
        $this->assertSame( $obj, $obj->having($having) );
        $this->assertSame( $having, $obj->getHaving() );

        $this->assertSame( $obj, $obj->having("Field = 'string'") );
        $this->assertEquals(
            new \h2o\Query\Where\Raw("Field = 'string'"),
            $obj->getHaving()
        );
    }

    public function testAndHaving ()
    {
        $obj = new \h2o\Query\Select;

        $this->assertSame( $obj, $obj->andHaving("A = B") );

        $and = $obj->getHaving();
        $this->assertEquals(
                new \h2o\Query\Where\LogicAnd(
                        new \h2o\Query\Where\Raw("A = B")
                    ),
                $and
            );

        $clause = $this->getMock('h2o\iface\Query\Where');
        $this->assertSame( $obj, $obj->andHaving( $clause ) );
        $this->assertSame( $and, $obj->getHaving() );
        $this->assertEquals(
                new \h2o\Query\Where\LogicAnd(
                        new \h2o\Query\Where\Raw("A = B"),
                        $clause
                    ),
                $and
            );
    }

    public function testOrHaving ()
    {
        $obj = new \h2o\Query\Select;

        $this->assertSame( $obj, $obj->orHaving("A = B") );

        $and = $obj->getHaving();
        $this->assertEquals(
                new \h2o\Query\Where\LogicOr(
                        new \h2o\Query\Where\Raw("A = B")
                    ),
                $and
            );

        $clause = $this->getMock('h2o\iface\Query\Where');
        $this->assertSame( $obj, $obj->orHaving( $clause ) );
        $this->assertSame( $and, $obj->getHaving() );
        $this->assertEquals(
                new \h2o\Query\Where\LogicOr(
                        new \h2o\Query\Where\Raw("A = B"),
                        $clause
                    ),
                $and
            );
    }

    public function testOffsetAccessors ()
    {
        $obj = new \h2o\Query\Select;

        $this->assertFalse( $obj->offsetExists() );
        $this->assertNull( $obj->getOffset() );

        $this->assertSame( $obj, $obj->setOffset( 10 ) );
        $this->assertTrue( $obj->offsetExists() );
        $this->assertSame( 10, $obj->getOffset() );

        $this->assertSame( $obj, $obj->clearOffset() );
        $this->assertFalse( $obj->offsetExists() );
        $this->assertNull( $obj->getOffset() );

        $this->assertSame( $obj, $obj->setOffset( -5 ) );
        $this->assertFalse( $obj->offsetExists() );
        $this->assertNull( $obj->getOffset() );
    }

    public function testLimitAccessors ()
    {
        $obj = new \h2o\Query\Select;

        $this->assertFalse( $obj->limitExists() );
        $this->assertNull( $obj->getLimit() );

        $this->assertSame( $obj, $obj->setLimit( 10 ) );
        $this->assertTrue( $obj->limitExists() );
        $this->assertSame( 10, $obj->getLimit() );

        $this->assertSame( $obj, $obj->clearLimit() );
        $this->assertFalse( $obj->limitExists() );
        $this->assertNull( $obj->getLimit() );

        $this->assertSame( $obj, $obj->setLimit( -5 ) );
        $this->assertFalse( $obj->limitExists() );
        $this->assertNull( $obj->getLimit() );
    }

    public function testLimit ()
    {
        $obj = new \h2o\Query\Select;

        $this->assertSame( $obj, $obj->limit(100) );
        $this->assertSame( 100, $obj->getLimit() );
        $this->assertNull( $obj->getOffset() );

        $this->assertSame( $obj, $obj->limit(20, 50) );
        $this->assertSame( 20, $obj->getLimit() );
        $this->assertSame( 50, $obj->getOffset() );
    }

    public function testToSQL_withDistinct ()
    {
        $select = new \h2o\Query\Select;
        $select->setDistinct( TRUE );

        $link = new \h2o\DB\BlackHole\Link;

        $this->assertSame(
        		"SELECT DISTINCT *",
                $select->toSQL( $link )
            );
    }

    public function testToSQL_withFoundRows ()
    {
        $select = new \h2o\Query\Select;
        $select->setFoundRows( TRUE );

        $link = new \h2o\DB\BlackHole\Link;

        $this->assertSame(
        		"SELECT SQL_CALC_FOUND_ROWS *",
                $select->toSQL( $link )
            );
    }

    public function testToSQL_withFieldList ()
    {
        $select = new \h2o\Query\Select;

        $select->addField( new \h2o\Query\Atom\Field("field1") );
        $select->addField( new \h2o\Query\Atom\Field("fld2") );

        $link = new \h2o\DB\BlackHole\Link;
        $this->assertSame(
        		"SELECT `field1`, `fld2`",
                $select->toSQL( $link )
            );
    }

    public function testToSQL_withFrom ()
    {
        $from = $this->getMock( "h2o\iface\Query\From" );
        $from->expects( $this->once() )
            ->method( "toFromSQL" )
            ->will( $this->returnValue("`table`") );

        $select = new \h2o\Query\Select( $from );
        $link = new \h2o\DB\BlackHole\Link;

        $this->assertSame(
        		"SELECT *\n"
                ."FROM `table`",
                $select->toSQL( $link )
            );
    }

    public function testToSQL_withWhere ()
    {
        $where = $this->getMock( "h2o\iface\Query\Where" );
        $where->expects( $this->once() )
            ->method( "toWhereSQL" )
            ->will( $this->returnValue("fld = 'value'") );

        $select = new \h2o\Query\Select;
        $select->setWhere( $where );

        $link = new \h2o\DB\BlackHole\Link;

        $this->assertSame(
        		"SELECT *\n"
                ."WHERE fld = 'value'",
                $select->toSQL( $link )
            );
    }

    public function testToSQL_withOrderBy ()
    {
        $select = new \h2o\Query\Select;

        $select->addOrder( new \h2o\Query\Atom\Field("field1") );
        $select->addOrder( new \h2o\Query\Atom\Field("fld2") );

        $link = new \h2o\DB\BlackHole\Link;
        $this->assertSame(
        		"SELECT *\n"
    			."ORDER BY `field1`, `fld2`",
                $select->toSQL( $link )
            );
    }

    public function testToSQL_withGroupBy ()
    {
        $select = new \h2o\Query\Select;

        $select->addGroup( new \h2o\Query\Atom\Field("field1") );
        $select->addGroup( new \h2o\Query\Atom\Field("fld2") );

        $link = new \h2o\DB\BlackHole\Link;
        $this->assertSame(
        		"SELECT *\n"
    			."GROUP BY `field1`, `fld2`",
                $select->toSQL( $link )
            );
    }

    public function testToSQL_withHaving ()
    {
        $where = $this->getMock( "h2o\iface\Query\Where" );
        $where->expects( $this->once() )
            ->method( "toWhereSQL" )
            ->will( $this->returnValue("fld = 'value'") );

        $select = new \h2o\Query\Select;
        $select->setHaving( $where );

        $link = new \h2o\DB\BlackHole\Link;

        $this->assertSame(
        		"SELECT *\n"
                ."HAVING fld = 'value'",
                $select->toSQL( $link )
            );
    }

    public function testToSQL_limit ()
    {
        $select = new \h2o\Query\Select;
        $select->setLimit( 20 );

        $link = new \h2o\DB\BlackHole\Link;

        $this->assertSame(
        		"SELECT *\n"
                ."LIMIT 0, 20",
                $select->toSQL( $link )
            );
    }

    public function testToSQL_offset ()
    {
        $select = new \h2o\Query\Select;
        $select->setLimit( 20 );
        $select->setOffset( 100 );

        $link = new \h2o\DB\BlackHole\Link;

        $this->assertSame(
        		"SELECT *\n"
                ."LIMIT 100, 20",
                $select->toSQL( $link )
            );
    }

    public function testToSQL_full ()
    {
        $select = new \h2o\Query\Select;
        $select->setDistinct(TRUE)
            ->setFoundRows(TRUE)
            ->addField( new \h2o\Query\Atom\Func("NOW") )
            ->addField(
                new \h2o\Query\Expr\Aliased(
                    new \h2o\Query\Atom\Field("fld2"),
                    "info"
                )
            )
            ->setFrom(
                new \h2o\Query\From\Table("tableName", "db")
            )
            ->setWhere(
                new \h2o\Query\Where\Equals(
            		new \h2o\Query\Atom\Field("fld1"),
                    new \h2o\Query\Atom\Primitive( 5 )
                )
            )
            ->addOrder(
                new \h2o\Query\Expr\Ordered(
            		new \h2o\Query\Atom\Field("fld1"),
            		"DESC"
                )
            )
            ->addGroup(
        		new \h2o\Query\Atom\Field("fld1")
            )
            ->setHaving(
                new \h2o\Query\Where\Equals(
            		new \h2o\Query\Atom\Func("COUNT"),
                    new \h2o\Query\Atom\Primitive( 2 )
                )
            )
            ->setLimit( 20 )
            ->setOffset( 100 );


        $link = new \h2o\DB\BlackHole\Link;

        $this->assertSame(
        		"SELECT DISTINCT SQL_CALC_FOUND_ROWS NOW(), `fld2` AS info\n"
        		."FROM `db`.`tableName`\n"
        		."WHERE `fld1` = 5\n"
        		."ORDER BY `fld1` DESC\n"
        		."GROUP BY `fld1`\n"
        		."HAVING COUNT() = 2\n"
                ."LIMIT 100, 20",
                $select->toSQL( $link )
            );
    }

    public function testFluent ()
    {
        $select = \h2o\Query::select()
            ->distinct()
            ->fields("fld1", "db.fld2 AS info")
            ->from("db.tableName")
            ->where("`fld1` = 5")
            ->orderBy("sortField DESC")
            ->groupBy("id")
            ->having("COUNT(*) = 2")
            ->limit(20, 100);

        $link = new \h2o\DB\BlackHole\Link;

        $this->assertSame(
        		"SELECT DISTINCT `fld1`, `db`.`fld2` AS info\n"
        		."FROM `db`.`tableName`\n"
        		."WHERE `fld1` = 5\n"
        		."ORDER BY `sortField` DESC\n"
        		."GROUP BY `id`\n"
        		."HAVING COUNT(*) = 2\n"
                ."LIMIT 100, 20",
                $select->toSQL( $link )
            );
    }

}

?>