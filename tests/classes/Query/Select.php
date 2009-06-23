
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

    public function testToSQL_withFrom ()
    {
        $from = $this->getMock( "cPHP\iface\Query\From" );
        $from->expects( $this->once() )
            ->method( "toFromSQL" )
            ->will( $this->returnValue("`table`") );

        $select = new \cPHP\Query\Select( $from );

        $this->assertSame(
        		"SELECT *\n"
                ."FROM `table`",
                $select->toSQL()
            );
    }

    public function testToSQL_withFieldList ()
    {
        $this->markTestIncomplete("To be re-written after code refactor");
        $from = $this->getMock( "cPHP\iface\Query\From" );

        $from->expects( $this->once() )
            ->method( "getSQLFields" )
            ->will( $this->returnValue(array(
                    "field1", null, "  ", "fld2"
                )) );

        $from->expects( $this->once() )
            ->method( "getFromSQL" )
            ->will( $this->returnValue("`table`") );

        $select = new \cPHP\Query\Select( $from );

        $this->assertSame(
        		"SELECT field1, fld2\n"
                ."FROM `table`",
                $select->toSQL()
            );
    }

    public function testToSQL_limit ()
    {
        $select = new \cPHP\Query\Select;
        $select->setLimit( 20 );

        $this->assertSame(
        		"SELECT *\n"
                ."LIMIT 0, 20",
                $select->toSQL()
            );
    }

    public function testToSQL_offset ()
    {
        $select = new \cPHP\Query\Select;
        $select->setLimit( 20 );
        $select->setOffset( 100 );

        $this->assertSame(
        		"SELECT *\n"
                ."LIMIT 100, 20",
                $select->toSQL()
            );
    }

}

?>