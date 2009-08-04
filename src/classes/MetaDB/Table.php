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
 * @author James Frasca <James@RaindropPHP.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package MetaDB
 */

namespace h2o\MetaDB;

/**
 * A database table
 */
class Table implements \h2o\iface\Query\From
{

    /**
     * The DB this table belongs to
     *
     * @var \h2o\MetaDB\DB
     */
    private $db;

    /**
     * The name of this table
     *
     * @var String
     */
    private $name;

    /**
     * The row builder to use for queries against this table
     *
     * @var \h2o\iface\MetaDB\RowBuilder
     */
    private $builder;

    /**
     * The columns in this table
     *
     * @var array An array of \h2o\iface\MetaDB\Column objects
     */
    private $columns = array();

    /**
     * The primary key for this table
     *
     * @var \h2o\iface\MetaDB\Column
     */
    private $primary;

    /**
     * Constructor...
     *
     * @param \h2o\MetaDB\DB $db The Database this table belongs to
     * @param String $name The name of the table in the database
     */
    public function __construct ( \h2o\MetaDB\DB $db, $name )
    {
        $name = trim( trim( \h2o\strval($name) ), "`" );

        if ( \h2o\isEmpty($name) )
            throw new \h2o\Exception\Argument( 1, "Table Name", "Must not be empty" );

        $this->name = $name;
        $this->db = $db;

        // Instantiate a default row builder
        $this->builder = new \h2o\MetaDB\RowBuilder\Generic( $this );

        // Add this table to the db
        $db->addTable( $this );
    }

    /**
     * Returns the name of the table
     *
     * @return String
     */
    public function getName ()
    {
        return $this->name;
    }

    /**
     * Returns the RowBuilder for this table
     *
     * @return \h2o\iface\MetaDB\RowBuilder
     */
    public function getRowBuilder ()
    {
        return $this->builder;
    }

    /**
     * Sets the RowBuilder for this table
     *
     * @param \h2o\iface\MetaDB\RowBuilder $builder
     * @return return_info Returns a self reference
     */
    public function setRowBuilder ( \h2o\iface\MetaDB\RowBuilder $builder )
    {
        $this->builder = $builder;
        return $this;
    }

    /**
     * Returns the columns registered in this table
     *
     * @return array Returns an array of \h2o\iface\MetaDB\Column objects
     */
    public function getColumns ()
    {
        return $this->columns;
    }

    /**
     * Adds a new column to this table
     *
     * @param \h2o\iface\MetaDB\Column $column The db column to add
     * @return \h2o\MetaDB\Table Returns a self reference
     */
    public function addColumn ( \h2o\iface\MetaDB\Column $column )
    {
        // Ensure the column doesn't already exist
        $found = $this->getColumn( $column->getName() );

        if ( !is_null( $found ) && $found !== $column ) {
            $err = new \h2o\Exception\Argument(
                    0,
                    "Column",
                    "A column with that name already exists"
                );
            $err->addData("Column Name", $column->getName());
            throw $err;
        }

        $this->columns[ $column->getName() ] = $column;
        return $this;
    }

    /**
     * Returns the first column with the given name
     *
     * @param String $name The name of the column to find
     * @return \h2o\iface\MetaDB\Column Returns NULL if the column couldn't
     * 		be found
     */
    public function getColumn ( $name )
    {
        $name = trim( \h2o\strval( $name ) );

        foreach ( $this->columns AS $column ) {
            if ( $column->getName() == $name )
                return $column;
        }

        return NULL;
    }

    /**
     * Returns the primary key of this table
     *
     * @return \h2o\iface\MetaDB\Column Returns NULL if no primary
     * 		key has been set
     */
    public function getPrimary ()
    {
        return $this->primary;
    }

    /**
     * Sets the primary key for this table
     *
     * @param \h2o\iface\MetaDB\Column $column The new primary key
     * @return \h2o\MetaDB\Table Returns a self reference
     */
    public function setPrimary ( \h2o\iface\MetaDB\Column $column )
    {
        $this->primary = $column;

        // If the primary hasn't been registered yet, add it to the columns
        if ( !in_array($column, $this->columns, true) ) {
            $this->columns = array( $column->getName() => $column )
                + $this->columns;
        }

        return $this;
    }

    /**
     * Class property access to the contained columns
     *
     * @throws \h2o\Exception\Variable If the requested column doesn't exist,
     * 		this exception will be thrown
     * @param String $name The name of the column to pull
     * @return \h2o\MetaDB\Column Returns the requested Column
     */
    public function __get ( $name )
    {
        $db = $this->getColumn( $name );

        if ( !$db )
            throw new \h2o\Exception\Variable($name, "Column does not exist");

        return $db;
    }

    /**
     * Class property access to the contained columns
     *
     * @param String $name The name of the column to test
     * @return Boolean Returns whether a column has been registered
     */
    public function __isset ( $name )
    {
        return $this->getColumn( $name ) ? TRUE : FALSE;
    }

    /**
     * Returns the SQL FROM clause
     *
     * @param \h2o\iface\DB\Link $link The database connection this WHERE clause
     * 		is being run against. This is being passed in for escaping purposes
     * @return String
     */
    public function toFromSQL ( \h2o\iface\DB\Link $link )
    {
        return $this->db->getName() .".". $this->getName();
    }

    /**
     * Executes the select query and returns the results
     *
     * @param \h2o\Query\Select $query The query to run
     * @return \h2o\MetaDB\Result
     */
    public function select ( \h2o\Query\Select $query )
    {
        return $this->db->select( $this->builder, $query );
    }

    /**
     * Executes a select against this table using the given WHERE clause
     *
     * @param \h2o\iface\Query\Where $where The WHERE clause to run
     * 		the query with
     * @return \h2o\MetaDB\Result
     */
    public function where ( \h2o\iface\Query\Where $where )
    {
        $query = new \h2o\Query\Select( $this );
        $query->setFields( $this->columns );
        $query->setWhere( $where );

        return $this->select( $query );
    }

}

?>