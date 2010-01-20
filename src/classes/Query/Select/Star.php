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

namespace r8\Query\Select;

/**
 * Represents a Star in the list of fields to select
 */
class Star implements \r8\iface\Query\Selectable
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
     * Constructor...
     *
     * @param String $table If given, the table this field is in
     * @param String $database If given the name of the database this field is in
     */
    public function __construct ( $table = null, $database = null )
    {
        if ( $table )
            $this->setTable( $table );

        if ( $database )
            $this->setDatabase( $database );
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
     * Sets the Table name
     *
     * @param String $table
     * @return \r8\Query\Atom\Field Returns a self reference
     */
    public function setTable ( $table )
    {
        $table = \r8\str\stripW( $table );
        $this->table = $table ? $table : null;
        return $this;
    }

    /**
     * Returns whether the Table name has been set
     *
     * @return Boolean
     */
    public function tableExists ()
    {
        return isset( $this->table );
    }

    /**
     * Clears the currently set Table name
     *
     * @return \r8\Query\Atom\Field Returns a self reference
     */
    public function clearTable ()
    {
        $this->table = null;
        return $this;
    }

    /**
     * Returns the Database name
     *
     * @return String
     */
    public function getDatabase ()
    {
        return $this->database;
    }

    /**
     * Sets the Database name
     *
     * @param String $database
     * @return \r8\Query\Atom\Field Returns a self reference
     */
    public function setDatabase ( $database )
    {
        $database = \r8\str\stripW( $database );
        $this->database = $database ? $database : null;
        return $this;
    }

    /**
     * Returns whether the Database name has been set
     *
     * @return Boolean
     */
    public function databaseExists ()
    {
        return isset( $this->database );
    }

    /**
     * Clears the currently set Database name
     *
     * @return \r8\Query\Atom\Field Returns a self reference
     */
    public function clearDatabase ()
    {
        $this->database = null;
        return $this;
    }

    /**
     * Returns the SQL string for this expression
     *
     * @param \r8\iface\DB\Link $link The database connection this WHERE clause
     *      is being run against. This is being passed in for escaping purposes
     * @return String
     */
    public function toSelectSQL( \r8\iface\DB\Link $link )
    {
        return
            ( $this->table && $this->database ? "`". $this->database ."`." : "" )
            .( $this->table ? "`". $this->table ."`." : "" )
            ."*";
    }

}

?>