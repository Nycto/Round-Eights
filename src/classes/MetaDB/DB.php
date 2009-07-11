<?php
/**
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
 * @package MetaDB
 */

namespace h2o\MetaDB;

/**
 * A database
 */
class DB
{

    /**
     * The TableSet this table belongs to
     *
     * @var \h2o\MetaDB\Set
     */
    private $set;

    /**
     * The name of this database
     *
     * @var String
     */
    private $name;

    /**
     * The tables in this database
     *
     * @var array An array of \h2o\iface\MetaDB\Tables objects
     */
    private $tables = array();

    /**
     * Constructor...
     *
     * @param \h2o\MetaDB\Set $set The TableSet this table belongs to
     * @param String $name The name of the database
     */
    public function __construct ( \h2o\MetaDB\Set $set, $name )
    {
        $name = trim( trim( \h2o\strval($name) ), "`" );

        if ( \h2o\isEmpty($name) )
            throw new \h2o\Exception\Argument( 0, "DB Name", "Must not be empty" );

        $this->name = $name;
        $this->set = $set;

        // Add this table to the table set
        $set->addDB( $this );
    }

    /**
     * Returns the name of the database
     *
     * @return String
     */
    public function getName ()
    {
        return $this->name;
    }

    /**
     * Returns the Tables registered in this database
     *
     * @return array Returns an array of \h2o\MetaDB\Table objects
     */
    public function getTables ()
    {
        return $this->tables;
    }

    /**
     * Registers a new table in this database
     *
     * @param \h2o\MetaDB\Table $table The table to register
     * @return \h2o\MetaDB\DB Returns a self reference
     */
    public function addTable ( \h2o\MetaDB\Table $table )
    {
        // Ensure the table doesn't already exist
        $found = $this->getTable( $table->getName() );

        if ( !is_null( $found ) && $found !== $table ) {
            $err = new \h2o\Exception\Argument(
                    0,
                    "Table",
                    "A table with that name already exists"
                );
            $err->addData("Table Name", $table->getName());
            throw $err;
        }

        $this->tables[ $table->getName() ] = $table;
        return $this;
    }

    /**
     * Returns a registered table from its name
     *
     * @param String $name The table name to search in
     * @return \h2o\MetaDB\Table Returns the found table, or NULL if the table
     * 		doesn't exist
     */
    public function getTable ( $name )
    {
        $name = trim( trim( \h2o\strval($name) ), "`" );

        if ( !isset( $this->tables[$name] ) )
            return NULL;

        return $this->tables[$name];
    }

    /**
     * Class property access to the contained tables
     *
     * @throws \h2o\Exception\Variable If the requested table doesn't exist,
     * 		this exception will be thrown
     * @param String $name The name of the table to pull
     * @return \h2o\MetaDB\Table Returns the requested Table
     */
    public function __get ( $name )
    {
        $db = $this->getTable( $name );

        if ( !$db )
            throw new \h2o\Exception\Variable($name, "Table does not exist");

        return $db;
    }

    /**
     * Class property access to the contained tables
     *
     * @param String $name The name of the table to test
     * @return Boolean Returns whether a table has been registered
     */
    public function __isset ( $name )
    {
        return $this->getTable( $name ) ? TRUE : FALSE;
    }

}

?>