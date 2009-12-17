<?php
/**
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
 * @package MetaDB
 */

namespace r8\MetaDB;

/**
 * A database
 */
class DB
{

    /**
     * The TableSet this table belongs to
     *
     * @var \r8\MetaDB\Set
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
     * @var array An array of \r8\iface\MetaDB\Tables objects
     */
    private $tables = array();

    /**
     * Constructor...
     *
     * @param \r8\MetaDB\Set $set The TableSet this table belongs to
     * @param String $name The name of the database
     */
    public function __construct ( \r8\MetaDB\Set $set, $name )
    {
        $name = trim( trim( \r8\strval($name) ), "`" );

        if ( \r8\isEmpty($name) )
            throw new \r8\Exception\Argument( 0, "DB Name", "Must not be empty" );

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
     * @return array Returns an array of \r8\MetaDB\Table objects
     */
    public function getTables ()
    {
        return $this->tables;
    }

    /**
     * Registers a new table in this database
     *
     * @param \r8\MetaDB\Table $table The table to register
     * @return \r8\MetaDB\DB Returns a self reference
     */
    public function addTable ( \r8\MetaDB\Table $table )
    {
        // Ensure the table doesn't already exist
        $found = $this->getTable( $table->getName() );

        if ( !is_null( $found ) && $found !== $table ) {
            $err = new \r8\Exception\Argument(
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
     * @return \r8\MetaDB\Table Returns the found table, or NULL if the table
     * 		doesn't exist
     */
    public function getTable ( $name )
    {
        $name = trim( trim( \r8\strval($name) ), "`" );

        if ( !isset( $this->tables[$name] ) )
            return NULL;

        return $this->tables[$name];
    }

    /**
     * Class property access to the contained tables
     *
     * @throws \r8\Exception\Variable If the requested table doesn't exist,
     * 		this exception will be thrown
     * @param String $name The name of the table to pull
     * @return \r8\MetaDB\Table Returns the requested Table
     */
    public function __get ( $name )
    {
        $db = $this->getTable( $name );

        if ( !$db )
            throw new \r8\Exception\Variable($name, "Table does not exist");

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

    /**
     * Executes the select query and returns the results
     *
     * @param \r8\iface\MetaDB\RowBuilder $builder The builder to
     * 		use for constructing rows
     * @param \r8\Query\Select $query The query to run
     * @return \r8\MetaDB\Result
     */
    public function select (
        \r8\iface\MetaDB\RowBuilder $builder,
        \r8\Query\Select $query
    ) {
        return $this->set->select( $builder, $query );
    }

}

?>