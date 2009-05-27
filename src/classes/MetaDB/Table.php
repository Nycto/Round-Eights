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
     * The database this table belongs to
     *
     * @var String
     */
    private $db;

    /**
     * The name of this table
     *
     * @var String
     */
    private $table;

    /**
     * The columns in this table
     *
     * @var array An array of \cPHP\iface\MetaDB\Column objects
     */
    private $columns = array();

    /**
     * Constructor...
     *
     * @param String $db The name of the database this table is in
     * @param String $table The name of the table in the database
     */
    public function __construct ( $db, $table )
    {
        $db = trim( trim( \cPHP\strval($db) ), "`" );
        $table = trim( trim( \cPHP\strval($table) ), "`" );

        if ( \cPHP\isEmpty($db) )
            throw new \cPHP\Exception\Argument( 0, "DB Name", "Must not be empty" );

        if ( \cPHP\isEmpty($table) )
            throw new \cPHP\Exception\Argument( 1, "Table Name", "Must not be empty" );

        $this->db = $db;
        $this->table = $table;
    }

    /**
     * Returns the name of the database this table is in
     *
     * @return String
     */
    public function getDB ()
    {
        return $this->db;
    }

    /**
     * Returns the name of the table
     *
     * @return String
     */
    public function getTable ()
    {
        return $this->table;
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
        if ( !in_array($column, $this->columns, true) )
            $this->columns[] = $column;

        return $this;
    }

}

?>