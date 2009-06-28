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

namespace cPHP\Query\Atom;

/**
 * Represents a field name in a SQL query
 */
class Field extends \cPHP\Query\Atom
{

    /**
     * The field name
     *
     * @var String
     */
    private $field;

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
     * Instantiates a new instance of this object from a string
     *
     * @param String $string The string to parse into an object
     * @return \cPHP\Query\Atom\Field
     */
    static public function fromString ( $string )
    {
        $field = \cPHP\strval( $field );

        // If it doesn't contain a 'dot', then things are simple
        if ( !\cPHP\str\contains( ".", $field ) )
            $this->field = "`". trim( $field, "`" ) ."`";

        // If it has a dot, but no back ticks, we can do some easy escaping
        else if ( !\cPHP\str\contains( "`", $field ) )
            $this->field = "`". str_replace(".", "`.`", $field) ."`";

        // Otherwise, lets just assume they know what they're doing
        else
            $this->field = $field;
    }

    /**
     * Constructor...
     *
     * @param String $field The field name
     * @param String $table If given, the table this field is in
     * @param String $database If given the name of the database this field is in
     */
    public function __construct ( $field, $table = null, $database = null )
    {
        $this->setField( $field );

        if ( $table )
            $this->setTable( $table );

        if ( $database )
            $this->setDatabase( $database );
    }

    /**
     * Returns the field name
     *
     * @return String
     */
    public function getField ()
    {
        return $this->field;
    }

    /**
     * Sets the field name
     *
     * @param String $field
     * @return \cPHP\Query\Atom\Field Returns a self referencve
     */
    public function setField ( $field )
    {
        $field = \cPHP\str\stripW( $field );

        if ( empty($field) )
            throw new \cPHP\Exception\Argument( 0, "Field Name", "Must not be empty" );

        $this->field = $field;

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
     * Sets the Table name
     *
     * @param String $table
     * @return \cPHP\Query\Atom\Field Returns a self reference
     */
    public function setTable ( $table )
    {
        $table = \cPHP\str\stripW( $table );
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
     * @return \cPHP\Query\Atom\Field Returns a self reference
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
     * @return \cPHP\Query\Atom\Field Returns a self reference
     */
    public function setDatabase ( $database )
    {
        $database = \cPHP\str\stripW( $database );
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
     * @return \cPHP\Query\Atom\Field Returns a self reference
     */
    public function clearDatabase ()
    {
        $this->database = null;
        return $this;
    }

    /**
     * Returns the SQL this atom represents
     *
     * @param \cPHP\iface\DB\Link $link The database connection this atom
     * 		is being created against. This is being passed in for escaping
     * 		purposes
     * @return String
     */
    public function toAtomSQL( \cPHP\iface\DB\Link $link )
    {
        return
            ( $this->table && $this->database ? "`". $this->database ."`." : "" )
            .( $this->table ? "`". $this->table ."`." : "" )
            ."`". $this->field ."`";
    }

}

?>