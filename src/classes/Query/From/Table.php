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
 * @package Query
 */

namespace r8\Query\From;

/**
 * Represents a single table to select from
 */
class Table implements \r8\iface\Query\From
{

    /**
     * The table name
     *
     * @var String
     */
    private $table;

    /**
     * The database name
     *
     * @var String
     */
    private $database;

    /**
     * The alias for this table
     *
     * @var String
     */
    private $alias;

    /**
     * Instantiates a new instance of this object from a string
     *
     * @param String $string The string to parse into an object
     * @return \r8\Query\From\Table
     */
    static public function fromString ( $string )
    {
        list( $string, $alias ) = \r8\Query::parseSQLAlias( $string );

        // Split the name into the table and database
        $parsed = \r8\Query::parseSQLName( $string );

        // Instantiate with the table name
        $field = new self( array_pop($parsed) );

        // Set the database if we found one
        if ( count($parsed) > 0 )
            $field->setDatabase( array_pop($parsed) );

        // Now load in the alias
        if ( $alias )
            $field->setAlias( $alias );

        return $field;
    }

    /**
     * Constructor...
     *
     * @param String $table The name of this table
     * @param String $database The database name
     * @param String $alias The alias to apply to this table
     */
    public function __construct ( $table, $database = null, $alias = null )
    {
        $this->setTable( $table );
        $this->setDatabase( $database );
        $this->setAlias( $alias );
    }

    /**
     * Sets the table name
     *
     * @param String $table The name of the table
     * @return \r8\Query\From\Table Returns a self referencve
     */
    public function setTable ( $table )
    {
        $table = \r8\str\stripW( $table );

        if ( empty($table) )
            throw new \r8\Exception\Argument( 0, "Table Name", "Must not be empty" );

        $this->table = $table;

        return $this;
    }

    /**
     * Returns the Table name
     *
     * @return String
     */
    public function getTable ()
    {
        return $this->table;
    }

    /**
     * Returns the Database
     *
     * @return String
     */
    public function getDatabase ()
    {
        return $this->database;
    }

    /**
     * Sets the Database
     *
     * @param String $database The name of the database
     * @return \r8\Query\From\Table Returns a self reference
     */
    public function setDatabase ( $database )
    {
        $database = \r8\str\stripW( $database );
        $this->database = $database ? $database : null;
        return $this;
    }

    /**
     * Returns whether the Database has been set
     *
     * @return Boolean
     */
    public function databaseExists ()
    {
        return isset( $this->database );
    }

    /**
     * Clears the currently set Database
     *
     * @return \r8\Query\From\Table Returns a self reference
     */
    public function clearDatabase ()
    {
        $this->database = null;
        return $this;
    }

    /**
     * Returns the Alias
     *
     * @return String
     */
    public function getAlias ()
    {
        return $this->alias;
    }

    /**
     * Sets the Alias
     *
     * @param String $alias The alias of this field
     * @return \r8\Query\From\Table Returns a self reference
     */
    public function setAlias ( $alias )
    {
        $alias = \r8\str\stripW( $alias );
        $this->alias = $alias ? $alias : null;
        return $this;
    }

    /**
     * Returns whether the Alias has been set
     *
     * @return Boolean
     */
    public function aliasExists ()
    {
        return isset( $this->alias );
    }

    /**
     * Clears the currently set Alias
     *
     * @return \r8\Query\From\Table Returns a self reference
     */
    public function clearAlias ()
    {
        $this->alias = null;
        return $this;
    }

    /**
     * Returns the SQL FROM clause
     *
     * @param \r8\iface\DB\Link $link The database connection this WHERE clause
     *      is being run against. This is being passed in for escaping purposes
     * @return String
     */
    public function toFromSQL ( \r8\iface\DB\Link $link )
    {
        return
            ( $this->database ? "`". $this->database ."`." : "" )
            . "`". $this->table ."`"
            .( $this->alias ? " AS `". $this->alias ."`" : "" );
    }

}

?>