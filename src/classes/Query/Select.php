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

namespace cPHP\Query;

/**
 * Builds a Select SQL query
 */
class Select
{

    /**
     * The table to select from
     *
     * @var \cPHP\iface\Query\From
     */
    private $from;

    /**
     * The list of fields to select
     *
     * @var array
     */
    private $fields = array();

    /**
     * The maximum number of rows to return
     *
     * @var Integer
     */
    private $limit;

    /**
     * The offset to begin selecting rows from
     *
     * @var Integer
     */
    private $offset;

    /**
     * Constructor...
     *
     * @param \cPHP\iface\Query\From $from The from clause for the query
     */
    public function __construct (\cPHP\iface\Query\From $from = null )
    {
        if ( $from )
            $this->setFrom( $from );
    }

    /**
     * Returns the From clause for the query
     *
     * @return \cPHP\iface\Query\From Returns NULL if no query has been set
     */
    public function getFrom ()
    {
        return $this->from;
    }

    /**
     * Sets the From clause for the query
     *
     * @param \cPHP\iface\Query\From $from
     * @return \cPHP\Query\Select Returns a self reference
     */
    public function setFrom ( \cPHP\iface\Query\From $from )
    {
        $this->from = $from;
        return $this;
    }

    /**
     * Returns whether the From clause has been set
     *
     * @return Boolean
     */
    public function fromExists ()
    {
        return isset( $this->from );
    }

    /**
     * Clears the From clause for the query
     *
     * @return \cPHP\Query\Select Returns a self reference
     */
    public function clearFrom ()
    {
        $this->from = null;
        return $this;
    }

    /**
     * Returns the Fields that will be selected
     *
     * @return array Returns an array of \cPHP\iface\Query\Selectable objects
     */
    public function getFields ()
    {
        return $this->fields;
    }

    /**
     * Adds a new field to the list of select fields
     *
     * @return \cPHP\Query\Select Returns a self reference
     */
    public function addField ( \cPHP\iface\Query\Selectable $field )
    {
        if ( !in_array($field, $this->fields, true) )
            $this->fields[] = $field;

        return $this;
    }

    /**
     * Clears all the select fields
     *
     * @return \cPHP\Query\Select Returns a self reference
     */
    public function clearFields ()
    {
        $this->fields = array();
        return $this;
    }

    /**
     * Returns the Limit
     *
     * @return Integer
     */
    public function getLimit ()
    {
        return $this->limit;
    }

    /**
     * Sets the Limit
     *
     * @param Integer $limit
     * @return \cPHP\Query\Select Returns a self reference
     */
    public function setLimit ( $limit )
    {
        $this->limit = intval($limit);

        if ( $this->limit <= 0 )
            $this->limit = null;

        return $this;
    }

    /**
     * Returns whether the Limit has been set
     *
     * @return Boolean
     */
    public function limitExists ()
    {
        return isset( $this->limit );
    }

    /**
     * Clears the currently set Limit
     *
     * @return \cPHP\Query\Select Returns a self reference
     */
    public function clearLimit ()
    {
        $this->limit = null;
        return $this;
    }

    /**
     * Returns the Offset
     *
     * @return Integer
     */
    public function getOffset ()
    {
        return $this->offset;
    }

    /**
     * Sets the Offset
     *
     * @param Integer $offset
     * @return \cPHP\Query\Select Returns a self reference
     */
    public function setOffset ( $offset )
    {
        $this->offset = intval($offset);

        if ( $this->offset <= 0 )
            $this->offset = null;

        return $this;
    }

    /**
     * Returns whether the Offset has been set
     *
     * @return Boolean
     */
    public function offsetExists ()
    {
        return isset( $this->offset );
    }

    /**
     * Clears the currently set Offset
     *
     * @return \cPHP\Query\Select Returns a self reference
     */
    public function clearOffset ()
    {
        $this->offset = null;
        return $this;
    }

    /**
     * Returns the SQL this object represents
     *
     * @param \cPHP\iface\DB\Link $link The database link to use for escaping
     * @return String
     */
    public function toSQL ( \cPHP\iface\DB\Link $link )
    {
        $sql = "SELECT ";

        if ( count($this->fields) <= 0 ) {
            $sql .= "*";
        }
        else {
            $sql .= implode(
        		", ",
                \cPHP\ary\invoke(
                    $this->fields,
                    "toSelectSQL",
                    $link
                )
            );
        }

        if ( $this->from )
            $sql .= "\nFROM ". $this->from->toFromSQL();

        if ( $this->limitExists() )
        {
            $sql .= "\nLIMIT "
        		.( $this->offsetExists() ? $this->getOffset() : "0" )
            	.", ". $this->getLimit();
        }


        return $sql;
    }

}

?>