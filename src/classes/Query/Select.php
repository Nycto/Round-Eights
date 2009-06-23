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
     * Whether the query should be run as distinct
     *
     * @var Boolean
     */
    private $distinct = FALSE;

    /**
     * The list of fields to select
     *
     * @var array
     */
    private $fields = array();

    /**
     * The table to select from
     *
     * @var \cPHP\iface\Query\From
     */
    private $from;

    /**
     * The root WHERE clause
     *
     * @var \cPHP\iface\Query\Where
     */
    private $where;

    /**
     * The list of fields for the ORDER BY clause
     *
     * @var array
     */
    private $order = array();

    /**
     * The list of fields for the GROUP BY clause
     *
     * @var array
     */
    private $group = array();

    /**
     * The root HAVING clause
     *
     * @var \cPHP\iface\Query\Where
     */
    private $having;

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
     * Sets whether the DISTINCT flag should be set
     *
     * @param Boolean $distinct Whether the distinct flag should be set
     * @return \cPHP\Query\Select Returns a self reference
     */
    public function setDistinct ( $distinct )
    {
        $this->distinct = \cPHP\boolVal( $distinct );
        return $this;
    }

    /**
     * Returns whether the Distinct flag is set
     *
     * @return Boolean
     */
    public function isDistinct ()
    {
        return $this->distinct;
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
     * Returns the Where clause for the query
     *
     * @return \cPHP\iface\Query\Where
     */
    public function getWhere ()
    {
        return $this->where;
    }

    /**
     * Sets the Where clause
     *
     * @param \cPHP\iface\Query\Where $where
     * @return \cPHP\Query\Select Returns a self reference
     */
    public function setWhere ( \cPHP\iface\Query\Where $where )
    {
        $this->where = $where;
        return $this;
    }

    /**
     * Returns whether the Where clause has been set
     *
     * @return Boolean
     */
    public function whereExists ()
    {
        return isset( $this->where );
    }

    /**
     * Clears the currently set Where clause
     *
     * @return \cPHP\Query\Select Returns a self reference
     */
    public function clearWhere ()
    {
        $this->where = null;
        return $this;
    }

    /**
     * Returns the Fields that the results will be ordered by
     *
     * @return array Returns an array of \cPHP\iface\Query\Ordered objects
     */
    public function getOrder ()
    {
        return $this->order;
    }

    /**
     * Adds a new field to the ORDER BY clause
     *
     * @param \cPHP\iface\Query\Ordered $field
     * @return \cPHP\Query\Select Returns a self reference
     */
    public function addOrder ( \cPHP\iface\Query\Ordered $field )
    {
        if ( !in_array($field, $this->order, true) )
            $this->order[] = $field;

        return $this;
    }

    /**
     * Clears all the ORDER BY fields
     *
     * @return \cPHP\Query\Select Returns a self reference
     */
    public function clearOrder ()
    {
        $this->order = array();
        return $this;
    }

    /**
     * Returns the Fields that the results will be grouped by
     *
     * @return array Returns an array of \cPHP\iface\Query\Ordered objects
     */
    public function getGroup ()
    {
        return $this->group;
    }

    /**
     * Adds a new field to the GROUP BY clause
     *
     * @param \cPHP\iface\Query\Ordered $field
     * @return \cPHP\Query\Select Returns a self reference
     */
    public function addGroup ( \cPHP\iface\Query\Ordered $field )
    {
        if ( !in_array($field, $this->group, true) )
            $this->group[] = $field;

        return $this;
    }

    /**
     * Clears all the GROUP BY fields
     *
     * @return \cPHP\Query\Select Returns a self reference
     */
    public function clearGroup ()
    {
        $this->group = array();
        return $this;
    }

    /**
     * Returns the Having clause for the query
     *
     * @return \cPHP\iface\Query\Where
     */
    public function getHaving ()
    {
        return $this->having;
    }

    /**
     * Sets the Having clause for the query
     *
     * @param \cPHP\iface\Query\Where $having
     * @return \cPHP\Query\Select Returns a self reference
     */
    public function setHaving ( \cPHP\iface\Query\Where $having )
    {
        $this->having = $having;
        return $this;
    }

    /**
     * Returns whether the Having clause has been set
     *
     * @return Boolean
     */
    public function havingExists ()
    {
        return isset( $this->having );
    }

    /**
     * Clears the currently set Having clause
     *
     * @return \cPHP\Query\Select Returns a self reference
     */
    public function clearHaving ()
    {
        $this->having = null;
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

        if ( $this->distinct )
            $sql .= "DISTINCT ";

        if ( count($this->fields) <= 0 ) {
            $sql .= "*";
        }
        else {
            $sql .= implode(
        		", ",
                \cPHP\ary\invoke( $this->fields, "toSelectSQL", $link )
            );
        }

        if ( $this->from )
            $sql .= "\nFROM ". $this->from->toFromSQL();

        if ( $this->where )
            $sql .= "\nWHERE ". $this->where->toWhereSQL( $link );

        if ( count($this->order) > 0 ) {
            $sql .= "\nORDER BY ". implode(
        		", ",
                \cPHP\ary\invoke( $this->order, "toOrderedSQL", $link )
            );
        }

        if ( count($this->group) > 0 ) {
            $sql .= "\nGROUP BY ". implode(
        		", ",
                \cPHP\ary\invoke( $this->group, "toOrderedSQL", $link )
            );
        }

        if ( $this->having )
            $sql .= "\nHAVING ". $this->having->toWhereSQL( $link );

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