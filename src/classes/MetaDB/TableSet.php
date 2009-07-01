<?php
/**
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
 * @package MetaDB
 */

namespace h2o\MetaDB;

/**
 * A collection of tables
 */
class TableSet
{

    /**
     * The tables registerd in this set
     *
     * @var array An array of \h2o\MetaDB\Table objects
     */
    private $tables = array();

    /**
     * Returns the tables registered in this set
     *
     * @return array Returns an array of \h2o\MetaDB\Table objects
     */
    public function getTables ()
    {
        return $this->tables;
    }

    /**
     * Adds a new table to this set
     *
     * @param \h2o\MetaDB\Table $table The db table to add
     * @return \h2o\MetaDB\TableSet Returns a self reference
     */
    public function addTable ( \h2o\MetaDB\Table $table )
    {
        // Ensure the table doesn't already exist in this set
        $found = $this->findTable( $table->getDBName(), $table->getTableName() );

        if ( !is_null( $found ) && $found !== $table ) {
            $err = new \h2o\Exception\Argument(
                    0,
                    "Table",
                    "A table with that name already exists"
                );
            $err->addData("Database Name", $table->getDBName());
            $err->addData("Table Name", $table->getTableName());
            throw $err;
        }

        $this->tables[ $table->getDBName() ][ $table->getTableName() ] = $table;
        return $this;
    }

    /**
     * Finds a table from its database and table name
     *
     * @param String $dbName The database name to search in
     * @param String $tableName The table name to look for
     * @return \h2o\MetaDB\Table Returns the found table, or NULL if the table
     * 		doesn't exist
     */
    public function findTable ( $dbName, $tableName )
    {
        $dbName = trim( trim( \h2o\strval($dbName) ), "`" );
        $tableName = trim( trim( \h2o\strval($tableName) ), "`" );

        if ( !isset( $this->tables[$dbName] ) )
            return NULL;

        if ( !isset( $this->tables[$dbName][$tableName] ) )
            return NULL;

        return $this->tables[$dbName][$tableName];
    }

}

?>