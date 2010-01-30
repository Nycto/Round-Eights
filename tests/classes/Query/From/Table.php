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
class classes_query_from_table extends PHPUnit_Framework_TestCase
{

    public function testFromString ()
    {
        $table = \r8\Query\From\Table::fromString( "table" );
        $this->assertSame( "table", $table->getTable() );
        $this->assertNull( $table->getDatabase() );
        $this->assertNull( $table->getAlias() );

        $table = \r8\Query\From\Table::fromString( "db.table" );
        $this->assertSame( "table", $table->getTable() );
        $this->assertSame( "db", $table->getDatabase() );
        $this->assertNull( $table->getAlias() );

        $table = \r8\Query\From\Table::fromString( "`db`.`table`" );
        $this->assertSame( "table", $table->getTable() );
        $this->assertSame( "db", $table->getDatabase() );
        $this->assertNull( $table->getAlias() );

        $table = \r8\Query\From\Table::fromString( "table AS Alias" );
        $this->assertSame( "table", $table->getTable() );
        $this->assertNull( $table->getDatabase() );
        $this->assertSame( "Alias", $table->getAlias() );

        $table = \r8\Query\From\Table::fromString( "db.table AS Alias" );
        $this->assertSame( "table", $table->getTable() );
        $this->assertSame( "db", $table->getDatabase() );
        $this->assertSame( "Alias", $table->getAlias() );

        $table = \r8\Query\From\Table::fromString( "`db`.`table` AS Alias" );
        $this->assertSame( "table", $table->getTable() );
        $this->assertSame( "db", $table->getDatabase() );
        $this->assertSame( "Alias", $table->getAlias() );

        $table = \r8\Query\From\Table::fromString( "`table AS ` AS Alias" );
        $this->assertSame( "tableAS", $table->getTable() );
        $this->assertNull( $table->getDatabase() );
        $this->assertSame( "Alias", $table->getAlias() );
    }

    public function testConstruct ()
    {
        $table = new \r8\Query\From\Table( "table" );
        $this->assertSame( "table", $table->getTable() );
        $this->assertNull( $table->getDatabase() );
        $this->assertNull( $table->getAlias() );

        $table = new \r8\Query\From\Table( "table", "db" );
        $this->assertSame( "table", $table->getTable() );
        $this->assertSame( "db", $table->getDatabase() );
        $this->assertNull( $table->getAlias() );

        $table = new \r8\Query\From\Table( "table", "db", "t" );
        $this->assertSame( "table", $table->getTable() );
        $this->assertSame( "db", $table->getDatabase() );
        $this->assertSame( "t", $table->getAlias() );
    }

    public function testTableAccessor ()
    {
        $table = new \r8\Query\From\Table( "table" );
        $this->assertSame( "table", $table->getTable() );

        $this->assertSame( $table, $table->setTable("!@# table Name") );
        $this->assertSame( "tableName", $table->getTable() );

        try {
            $table->setTable("   ");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }
    }

    public function testDatabaseAccessors ()
    {
        $obj = new \r8\Query\From\Table('table');
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

    public function testAliasAccessors ()
    {
        $obj = new \r8\Query\From\Table('table');
        $this->assertFalse( $obj->aliasExists() );
        $this->assertNull( $obj->getAlias() );

        $this->assertSame( $obj, $obj->setAlias( 'As' ) );
        $this->assertTrue( $obj->aliasExists() );
        $this->assertSame( 'As', $obj->getAlias() );

        $this->assertSame( $obj, $obj->clearAlias() );
        $this->assertFalse( $obj->aliasExists() );
        $this->assertNull( $obj->getAlias() );

        $this->assertSame( $obj, $obj->setAlias( ' !@# ali as' ) );
        $this->assertTrue( $obj->aliasExists() );
        $this->assertSame( 'alias', $obj->getAlias() );

        $this->assertSame( $obj, $obj->setAlias( '   ' ) );
        $this->assertFalse( $obj->aliasExists() );
        $this->assertNull( $obj->getAlias() );
    }

    public function testToFromSQL ()
    {
        $link = new \r8\DB\Link( new \r8\DB\BlackHole\Link );
        $table = new \r8\Query\From\Table( "table" );

        $this->assertSame( "`table`", $table->toFromSQL($link) );

        $table->setAlias("Alias");
        $this->assertSame( "`table` AS `Alias`", $table->toFromSQL($link) );

        $table->setDatabase("DB");
        $this->assertSame( "DB.`table` AS `Alias`", $table->toFromSQL($link) );

        $table->clearAlias();
        $this->assertSame( "DB.`table`", $table->toFromSQL($link) );
    }

}

?>