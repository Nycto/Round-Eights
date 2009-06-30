
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

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_query_select extends PHPUnit_Framework_TestCase
{

    public function testDistinctAccessors ()
    {
        $select = new \cPHP\Query\Select;
        $this->assertFalse( $select->isDistinct() );

        $this->assertSame( $select, $select->setDistinct(TRUE) );
        $this->assertTrue( $select->isDistinct() );

        $this->assertSame( $select, $select->setDistinct(FALSE) );
        $this->assertFalse( $select->isDistinct() );
    }

    public function testDistinct ()
    {
        $select = new \cPHP\Query\Select;

        $this->assertSame( $select, $select->distinct() );
        $this->assertTrue( $select->isDistinct() );
    }

    public function testFoundRows ()
    {
        $select = new \cPHP\Query\Select;
        $this->assertFalse( $select->getFoundRows() );

        $this->assertSame( $select, $select->setFoundRows(TRUE) );
        $this->assertTrue( $select->getFoundRows() );

        $this->assertSame( $select, $select->setFoundRows(FALSE) );
        $this->assertFalse( $select->getFoundRows() );

    }

    public function testFieldAccessors ()
    {
        $select = new \cPHP\Query\Select;
        $this->assertSame( array(), $select->getFields() );

        $fld1 = $this->getMock('cPHP\iface\Query\Selectable');
        $this->assertSame( $select, $select->addField( $fld1 ) );
        $this->assertSame( array( $fld1 ), $select->getFields() );

        // Ensure you can't add the same field twice
        $this->assertSame( $select, $select->addField( $fld1 ) );
        $this->assertSame( array( $fld1 ), $select->getFields() );

        $fld2 = $this->getMock('cPHP\iface\Query\Selectable');
        $this->assertSame( $select, $select->addField( $fld2 ) );
        $this->assertSame( array( $fld1, $fld2 ), $select->getFields() );

        $this->assertSame( $select, $select->clearFields() );
        $this->assertSame( array(), $select->getFields() );
    }

    public function testFields ()
    {
        $select = new \cPHP\Query\Select;

        $this->assertSame(
                $select,
                $select->fields( "fld1", "tbl.fld2" )
            );

        $this->assertEquals(
                array(
                        new \cPHP\Query\Atom\Field("fld1"),
                        new \cPHP\Query\Atom\Field("fld2", "tbl")
                    ),
                $select->getFields()
            );

        $this->assertSame(
                $select,
                $select->fields( new \cPHP\Query\Atom\Field("fld3") )
            );

        $this->assertEquals(
                array(
                        new \cPHP\Query\Atom\Field("fld1"),
                        new \cPHP\Query\Atom\Field("fld2", "tbl"),
                        new \cPHP\Query\Atom\Field("fld3")
                    ),
                $select->getFields()
            );
    }

    public function testFromAccessors ()
    {
        $obj = new \cPHP\Query\Select;
        $this->assertFalse( $obj->fromExists() );
        $this->assertNull( $obj->getFrom() );

        $from = $this->getMock('cPHP\iface\Query\From');

        $this->assertSame( $obj, $obj->setFrom( $from ) );
        $this->assertTrue( $obj->fromExists() );
        $this->assertSame( $from, $obj->getFrom() );

        $this->assertSame( $obj, $obj->clearFrom() );
        $this->assertFalse( $obj->fromExists() );
        $this->assertNull( $obj->getFrom() );
    }

    public function testFrom ()
    {
        $obj = new \cPHP\Query\Select;

        $this->assertSame( $obj, $obj->from("db.table") );
        $this->assertEquals(
                new \cPHP\Query\From\Table("table", "db"),
                $obj->getFrom()
            );

        $table = new \cPHP\Query\From\Table("table", "db");
        $this->assertSame( $obj, $obj->from( $table ) );
        $this->assertSame( $table, $obj->getFrom() );
    }

    public function testWhereAccessors ()
    {
        $obj = new \cPHP\Query\Select;
        $this->assertFalse( $obj->whereExists() );
        $this->assertNull( $obj->getWhere() );

        $where = $this->getMock('cPHP\iface\Query\Where');

        $this->assertSame( $obj, $obj->setWhere( $where ) );
        $this->assertTrue( $obj->whereExists() );
        $this->assertSame( $where, $obj->getWhere() );

        $this->assertSame( $obj, $obj->clearWhere() );
        $this->assertFalse( $obj->whereExists() );
        $this->assertNull( $obj->getWhere() );
    }

    public function testOrderAccessors ()
    {
        $select = new \cPHP\Query\Select;
        $this->assertSame( array(), $select->getOrder() );

        $fld1 = $this->getMock('cPHP\iface\Query\Ordered');
        $this->assertSame( $select, $select->addOrder( $fld1 ) );
        $this->assertSame( array( $fld1 ), $select->getOrder() );

        // Ensure you can't add the same field twice
        $this->assertSame( $select, $select->addOrder( $fld1 ) );
        $this->assertSame( array( $fld1 ), $select->getOrder() );

        $fld2 = $this->getMock('cPHP\iface\Query\Ordered');
        $this->assertSame( $select, $select->addOrder( $fld2 ) );
        $this->assertSame( array( $fld1, $fld2 ), $select->getOrder() );

        $this->assertSame( $select, $select->clearOrder() );
        $this->assertSame( array(), $select->getOrder() );
    }

    public function testGroupAccessors ()
    {
        $select = new \cPHP\Query\Select;
        $this->assertSame( array(), $select->getGroup() );

        $fld1 = $this->getMock('cPHP\iface\Query\Ordered');
        $this->assertSame( $select, $select->addGroup( $fld1 ) );
        $this->assertSame( array( $fld1 ), $select->getGroup() );

        // Ensure you can't add the same field twice
        $this->assertSame( $select, $select->addGroup( $fld1 ) );
        $this->assertSame( array( $fld1 ), $select->getGroup() );

        $fld2 = $this->getMock('cPHP\iface\Query\Ordered');
        $this->assertSame( $select, $select->addGroup( $fld2 ) );
        $this->assertSame( array( $fld1, $fld2 ), $select->getGroup() );

        $this->assertSame( $select, $select->clearGroup() );
        $this->assertSame( array(), $select->getGroup() );
    }

    public function testHavingAccessors ()
    {
        $obj = new \cPHP\Query\Select;
        $this->assertFalse( $obj->havingExists() );
        $this->assertNull( $obj->getHaving() );

        $having = $this->getMock('cPHP\iface\Query\Where');

        $this->assertSame( $obj, $obj->setHaving( $having ) );
        $this->assertTrue( $obj->havingExists() );
        $this->assertSame( $having, $obj->getHaving() );

        $this->assertSame( $obj, $obj->clearHaving() );
        $this->assertFalse( $obj->havingExists() );
        $this->assertNull( $obj->getHaving() );
    }

    public function testOffsetAccessors ()
    {
        $obj = new \cPHP\Query\Select;

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
        $obj = new \cPHP\Query\Select;

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

    public function testToSQL_withDistinct ()
    {
        $select = new \cPHP\Query\Select;
        $select->setDistinct( TRUE );

        $link = new \cPHP\DB\BlackHole\Link;

        $this->assertSame(
        		"SELECT DISTINCT *",
                $select->toSQL( $link )
            );
    }

    public function testToSQL_withFoundRows ()
    {
        $select = new \cPHP\Query\Select;
        $select->setFoundRows( TRUE );

        $link = new \cPHP\DB\BlackHole\Link;

        $this->assertSame(
        		"SELECT SQL_CALC_FOUND_ROWS *",
                $select->toSQL( $link )
            );
    }

    public function testToSQL_withFieldList ()
    {
        $select = new \cPHP\Query\Select;

        $select->addField( new \cPHP\Query\Atom\Field("field1") );
        $select->addField( new \cPHP\Query\Atom\Field("fld2") );

        $link = new \cPHP\DB\BlackHole\Link;
        $this->assertSame(
        		"SELECT `field1`, `fld2`",
                $select->toSQL( $link )
            );
    }

    public function testToSQL_withFrom ()
    {
        $from = $this->getMock( "cPHP\iface\Query\From" );
        $from->expects( $this->once() )
            ->method( "toFromSQL" )
            ->will( $this->returnValue("`table`") );

        $select = new \cPHP\Query\Select( $from );
        $link = new \cPHP\DB\BlackHole\Link;

        $this->assertSame(
        		"SELECT *\n"
                ."FROM `table`",
                $select->toSQL( $link )
            );
    }

    public function testToSQL_withWhere ()
    {
        $where = $this->getMock( "cPHP\iface\Query\Where" );
        $where->expects( $this->once() )
            ->method( "toWhereSQL" )
            ->will( $this->returnValue("fld = 'value'") );

        $select = new \cPHP\Query\Select;
        $select->setWhere( $where );

        $link = new \cPHP\DB\BlackHole\Link;

        $this->assertSame(
        		"SELECT *\n"
                ."WHERE fld = 'value'",
                $select->toSQL( $link )
            );
    }

    public function testToSQL_withOrderBy ()
    {
        $select = new \cPHP\Query\Select;

        $select->addOrder( new \cPHP\Query\Atom\Field("field1") );
        $select->addOrder( new \cPHP\Query\Atom\Field("fld2") );

        $link = new \cPHP\DB\BlackHole\Link;
        $this->assertSame(
        		"SELECT *\n"
    			."ORDER BY `field1`, `fld2`",
                $select->toSQL( $link )
            );
    }

    public function testToSQL_withGroupBy ()
    {
        $select = new \cPHP\Query\Select;

        $select->addGroup( new \cPHP\Query\Atom\Field("field1") );
        $select->addGroup( new \cPHP\Query\Atom\Field("fld2") );

        $link = new \cPHP\DB\BlackHole\Link;
        $this->assertSame(
        		"SELECT *\n"
    			."GROUP BY `field1`, `fld2`",
                $select->toSQL( $link )
            );
    }

    public function testToSQL_withHaving ()
    {
        $where = $this->getMock( "cPHP\iface\Query\Where" );
        $where->expects( $this->once() )
            ->method( "toWhereSQL" )
            ->will( $this->returnValue("fld = 'value'") );

        $select = new \cPHP\Query\Select;
        $select->setHaving( $where );

        $link = new \cPHP\DB\BlackHole\Link;

        $this->assertSame(
        		"SELECT *\n"
                ."HAVING fld = 'value'",
                $select->toSQL( $link )
            );
    }

    public function testToSQL_limit ()
    {
        $select = new \cPHP\Query\Select;
        $select->setLimit( 20 );

        $link = new \cPHP\DB\BlackHole\Link;

        $this->assertSame(
        		"SELECT *\n"
                ."LIMIT 0, 20",
                $select->toSQL( $link )
            );
    }

    public function testToSQL_offset ()
    {
        $select = new \cPHP\Query\Select;
        $select->setLimit( 20 );
        $select->setOffset( 100 );

        $link = new \cPHP\DB\BlackHole\Link;

        $this->assertSame(
        		"SELECT *\n"
                ."LIMIT 100, 20",
                $select->toSQL( $link )
            );
    }

    public function testToSQL_full ()
    {
        $select = new \cPHP\Query\Select;
        $select->setDistinct(TRUE)
            ->setFoundRows(TRUE)
            ->addField( new \cPHP\Query\Atom\Func("NOW") )
            ->addField(
                new \cPHP\Query\Expr\Aliased(
                    new \cPHP\Query\Atom\Field("fld2"),
                    "info"
                )
            )
            ->setFrom(
                new \cPHP\Query\From\Table("tableName", "db")
            )
            ->setWhere(
                new \cPHP\Query\Where\Equals(
            		new \cPHP\Query\Atom\Field("fld1"),
                    new \cPHP\Query\Atom\Primitive( 5 )
                )
            )
            ->addOrder(
                new \cPHP\Query\Expr\Ordered(
            		new \cPHP\Query\Atom\Field("fld1"),
            		"DESC"
                )
            )
            ->addGroup(
        		new \cPHP\Query\Atom\Field("fld1")
            )
            ->setHaving(
                new \cPHP\Query\Where\Equals(
            		new \cPHP\Query\Atom\Func("COUNT"),
                    new \cPHP\Query\Atom\Primitive( 2 )
                )
            )
            ->setLimit( 20 )
            ->setOffset( 100 );


        $link = new \cPHP\DB\BlackHole\Link;

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

}

?>