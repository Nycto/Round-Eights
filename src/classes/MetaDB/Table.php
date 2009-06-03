<?php
/**
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
 * @package MetaDB
 */

namespace cPHP\MetaDB;

/**
 * A database table
 */
class Table
{

    /**
     * The TableSet this table belongs to
     *
     * @var \cPHP\MetaDB\TableSet
     */
    private $tabelset;

    /**
     * The database this table belongs to
     *
     * @var String
     */
    private $dbName;

    /**
     * The name of this table
     *
     * @var String
     */
    private $tableName;

    /**
     * The columns in this table
     *
     * @var array An array of \cPHP\iface\MetaDB\Column objects
     */
    private $columns = array();

    /**
     * The primary key for this table
     *
     * @var \cPHP\iface\MetaDB\Column
     */
    private $primary;

    /**
     * Constructor...
     *
     * @param \cPHP\MetaDB\TableSet $tableset The TableSet this table belongs to
     * @param String $dbName The name of the database this table is in
     * @param String $tableName The name of the table in the database
     */
    public function __construct ( \cPHP\MetaDB\TableSet $tableset, $dbName, $tableName )
    {
        $dbName = trim( trim( \cPHP\strval($dbName) ), "`" );
        $tableName = trim( trim( \cPHP\strval($tableName) ), "`" );

        if ( \cPHP\isEmpty($dbName) )
            throw new \cPHP\Exception\Argument( 0, "DB Name", "Must not be empty" );

        if ( \cPHP\isEmpty($tableName) )
            throw new \cPHP\Exception\Argument( 1, "Table Name", "Must not be empty" );

        $this->dbName = $dbName;
        $this->tableName = $tableName;
        $this->tableset = $tableset;

        // Add this table to the table set
        $tableset->addTable( $this );
    }

    /**
     * Returns the name of the database this table is in
     *
     * @return String
     */
    public function getDBName ()
    {
        return $this->dbName;
    }

    /**
     * Returns the name of the table
     *
     * @return String
     */
    public function getTableName ()
    {
        return $this->tableName;
    }

    /**
     * Returns the columns registered in this table
     *
     * @return array Returns an array of \cPHP\iface\MetaDB\Column objects
     */
    public function getColumns ()
    {
        return $this->columns;
    }

    /**
     * Adds a new column to this table
     *
     * @param \cPHP\iface\MetaDB\Column $column The db column to add
     * @return \cPHP\MetaDB\Table Returns a self reference
     */
    public function addColumn ( \cPHP\iface\MetaDB\Column $column )
    {
        if ( !in_array($column, $this->columns, true) ) {

            if ( !is_null( $this->findColumn( $column->getName() ) ) ) {
                $err = new \cPHP\Exception\Argument(
                        0,
                        "Column",
                        "A column with that name already exists"
                    );
                $err->addData("Column Name", $column->getName());
                throw $err;
            }

            $this->columns[] = $column;
        }

        return $this;
    }

    /**
     * Returns the first column with the given name
     *
     * @param String $name The name of the column to find
     * @return \cPHP\iface\MetaDB\Column Returns NULL if the column couldn't
     * 		be found
     */
    public function findColumn ( $name )
    {
        $name = trim( \cPHP\strval( $name ) );

        foreach ( $this->columns AS $column ) {
            if ( $column->getName() == $name )
                return $column;
        }

        return NULL;
    }

    /**
     * Returns the primary key of this table
     *
     * @return \cPHP\iface\MetaDB\Column Returns NULL if no primary
     * 		key has been set
     */
    public function getPrimary ()
    {
        return $this->primary;
    }

    /**
     * Sets the primary key for this table
     *
     * @param \cPHP\iface\MetaDB\Column $column The new primary key
     * @return \cPHP\MetaDB\Table Returns a self reference
     */
    public function setPrimary ( \cPHP\iface\MetaDB\Column $column )
    {
        $this->primary = $column;

        // If the primary hasn't been registered yet, add it to the columns
        if ( !in_array($column, $this->columns, true) )
            array_unshift( $this->columns, $column );

        return $this;
    }

}

?>