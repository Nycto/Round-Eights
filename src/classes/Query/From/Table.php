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
 * @package Query
 */

namespace cPHP\Query\From;

/**
 * Represents a single table to select from
 */
class Table implements \cPHP\iface\Query\From
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
     * Constructor...
     */
    public function __construct ( $table, $database = null, $alias = null )
    {
        $this->setTable( $table );
    }

    /**
     * Sets the table name
     *
     * @param String $table
     * @return \cPHP\Query\From\Table Returns a self referencve
     */
    public function setTable ( $table )
    {
        $table = \cPHP\str\stripW( $table );

        if ( empty($table) )
            throw new \cPHP\Exception\Argument( 0, "Table Name", "Must not be empty" );

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
     * Returns the SQL FROM clause
     *
     * @return String
     */
    public function toFromSQL ()
    {
        return
            ( $this->database ? $this->database ."." : "" )
            . $this->table
            .( $this->alias ? " AS ". $this->alias : "" );
    }

}

?>