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
 * A collection of MetaDB Databases
 */
class Set
{

    /**
     * The dbs registerd in this set
     *
     * @var array An array of \h2o\MetaDB\DB objects
     */
    private $dbs = array();

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

}

?>