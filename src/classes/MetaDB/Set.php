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
 * A collection of MetaDB Databases
 */
class Set
{

    /**
     * The database connection to run queries against
     *
     * @var \h2o\iface\DB\Link
     */
    private $link;

    /**
     * The dbs registerd in this set
     *
     * @var array An array of \h2o\MetaDB\DB objects
     */
    private $dbs = array();

    /**
     * Constructor...
     *
     * @param \h2o\iface\DB\Link $link The database connection to run
     * 		queries against
     */
    public function __construct ( \h2o\iface\DB\Link $link )
    {
        $this->link = $link;
    }

    /**
     * Returns the DBs registered in this set
     *
     * @return array Returns an array of \h2o\MetaDB\DB objects
     */
    public function getDBs ()
    {
        return $this->dbs;
    }

    /**
     * Adds a new database to this set
     *
     * @param \h2o\MetaDB\DB $db The database to add
     * @return \h2o\MetaDB\Set Returns a self reference
     */
    public function addDB ( \h2o\MetaDB\DB $db )
    {
        // Ensure the database doesn't already exist
        $found = $this->getDB( $db->getName() );

        if ( !is_null( $found ) && $found !== $db ) {
            $err = new \h2o\Exception\Argument(
                    0,
                    "DB",
                    "A database with that name already exists"
                );
            $err->addData("Database Name", $db->getName());
            throw $err;
        }

        $this->dbs[ $db->getName() ] = $db;
        return $this;
    }

    /**
     * Returns a registered database from its name
     *
     * @param String $name The database name to search in
     * @return \h2o\MetaDB\DB Returns the found database, or NULL if the database
     * 		doesn't exist
     */
    public function getDB ( $name )
    {
        $name = trim( trim( \h2o\strval($name) ), "`" );

        if ( !isset( $this->dbs[$name] ) )
            return NULL;

        return $this->dbs[$name];
    }

    /**
     * Class property access to the contained databases
     *
     * @throws \h2o\Exception\Variable If the requested database doesn't exist,
     * 		this exception will be thrown
     * @param String $name The name of the database to pull
     * @return \h2o\MetaDB\DB Returns the requested database
     */
    public function __get ( $name )
    {
        $db = $this->getDB( $name );

        if ( !$db )
            throw new \h2o\Exception\Variable($name, "Database does not exist");

        return $db;
    }

    /**
     * Class property access to the contained databases
     *
     * @param String $name The name of the database to test
     * @return Boolean Returns whether a database has been registered
     */
    public function __isset ( $name )
    {
        return $this->getDB( $name ) ? TRUE : FALSE;
    }

    /**
     * Executes the select query and returns the results
     *
     * @param \h2o\iface\MetaDB\RowBuilder $builder The builder to
     * 		use for constructing rows
     * @param \h2o\Query\Select $query The query to run
     * @return \h2o\MetaDB\Result
     */
    public function select (
        \h2o\iface\MetaDB\RowBuilder $builder,
        \h2o\Query\Select $query
    ) {
        return new \h2o\MetaDB\Result(
            $this->link->query( $query->toSQL( $this->link ) ),
            $builder
        );
    }

}

?>