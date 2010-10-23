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
class classes_query_atom_field extends PHPUnit_Framework_TestCase
{

    public function testFromString ()
    {
        $fld = \r8\Query\Atom\Field::fromString("field");
        $this->assertSame( "field", $fld->getField() );
        $this->assertNull( $fld->getTable() );
        $this->assertNull( $fld->getDatabase() );

        $fld = \r8\Query\Atom\Field::fromString("tbl.field");
        $this->assertSame( "field", $fld->getField() );
        $this->assertSame( "tbl", $fld->getTable() );
        $this->assertNull( $fld->getDatabase() );

        $fld = \r8\Query\Atom\Field::fromString("db.tbl.field");
        $this->assertSame( "field", $fld->getField() );
        $this->assertSame( "tbl", $fld->getTable() );
        $this->assertSame( "db", $fld->getDatabase() );

        try {
            \r8\Query\Atom\Field::fromString("  ");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }
    }

    public function testConstruct ()
    {
        $fld = new \r8\Query\Atom\Field("field");
        $this->assertSame( "field", $fld->getField() );
        $this->assertNull( $fld->getTable() );
        $this->assertNull( $fld->getDatabase() );

        $fld = new \r8\Query\Atom\Field("field", "tbl");
        $this->assertSame( "field", $fld->getField() );
        $this->assertSame( "tbl", $fld->getTable() );
        $this->assertNull( $fld->getDatabase() );

        $fld = new \r8\Query\Atom\Field("field", "tbl", "db");
        $this->assertSame( "field", $fld->getField() );
        $this->assertSame( "tbl", $fld->getTable() );
        $this->assertSame( "db", $fld->getDatabase() );
    }

    public function testFieldAccessor ()
    {
        $field = new \r8\Query\Atom\Field( "field" );
        $this->assertSame( "field", $field->getField() );

        $this->assertSame( $field, $field->setField("!@# field Name") );
        $this->assertSame( "fieldName", $field->getField() );

        try {
            $field->setField("   ");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }
    }

    public function testTableAccessors ()
    {
        $obj = new \r8\Query\Atom\Field('field');
        $this->assertFalse( $obj->tableExists() );
        $this->assertNull( $obj->getTable() );

        $this->assertSame( $obj, $obj->setTable( "tbl" ) );
        $this->assertTrue( $obj->tableExists() );
        $this->assertSame( "tbl", $obj->getTable() );

        $this->assertSame( $obj, $obj->clearTable() );
        $this->assertFalse( $obj->tableExists() );
        $this->assertNull( $obj->getTable() );

        $this->assertSame( $obj, $obj->setTable( '   tbl!@#name  ' ) );
        $this->assertTrue( $obj->tableExists() );
        $this->assertSame( 'tblname', $obj->getTable() );

        $this->assertSame( $obj, $obj->setTable( '    ' ) );
        $this->assertFalse( $obj->tableExists() );
        $this->assertNull( $obj->getTable() );
    }

    public function testDatabaseAccessors ()
    {
        $obj = new \r8\Query\Atom\Field('field', 'table');
        $this->assertFalse( $obj->databaseExists() );
        $this->assertNull( $obj->getDatabase() );

        $this->assertSame( $obj, $obj->setDatabase( "dbase" ) );
        $this->assertTrue( $obj->databaseExists() );
        $this->assertSame( "dbase", $obj->getDatabase() );

        $this->assertSame( $obj, $obj->clearDatabase() );
        $this->assertFalse( $obj->databaseExists() );
        $this->assertNull( $obj->getDatabase() );

        $this->assertSame( $obj, $obj->setDatabase( '   db!@#name  ' ) );
        $this->assertTrue( $obj->databaseExists() );
        $this->assertSame( 'dbname', $obj->getDatabase() );

        $this->assertSame( $obj, $obj->setDatabase( '    ' ) );
        $this->assertFalse( $obj->databaseExists() );
        $this->assertNull( $obj->getDatabase() );
    }

    public function testToAtomSQL ()
    {
        $link = new \r8\DB\Link( new \r8\DB\BlackHole\Link );

        $fld = new \r8\Query\Atom\Field("field");
        $this->assertSame( "`field`", $fld->toAtomSQL( $link ) );

        $fld = new \r8\Query\Atom\Field("field", "tbl");
        $this->assertSame( "tbl.`field`", $fld->toAtomSQL( $link ) );

        $fld = new \r8\Query\Atom\Field("field", "tbl", "db");
        $this->assertSame( "db.tbl.`field`", $fld->toAtomSQL( $link ) );

        $fld = new \r8\Query\Atom\Field("field", null, "db");
        $this->assertSame( "`field`", $fld->toAtomSQL( $link ) );
    }

}

