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

namespace r8\Query;

/**
 * Builds a Select SQL query
 */
class Select
{

    /**
     * Whether the DISTINCT flag should be set
     *
     * @var Boolean
     */
    private $distinct = FALSE;

    /**
     * Whether the SQL_CALC_FOUND_ROWS flag should be set
     *
     * @var Boolean
     */
    private $foundRows = FALSE;

    /**
     * The list of fields to select
     *
     * @var array
     */
    private $fields = array();

    /**
     * The table to select from
     *
     * @var \r8\iface\Query\From
     */
    private $from;

    /**
     * The root WHERE clause
     *
     * @var \r8\iface\Query\Where
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
     * @var \r8\iface\Query\Where
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
     * @param \r8\iface\Query\From $from The from clause for the query
     */
    public function __construct (\r8\iface\Query\From $from = null )
    {
        if ( $from )
            $this->setFrom( $from );
    }

    /**
     * Sets whether the DISTINCT flag should be set
     *
     * @param Boolean $distinct Whether the distinct flag should be set
     * @return \r8\Query\Select Returns a self reference
     */
    public function setDistinct ( $distinct )
    {
        $this->distinct = (bool) $distinct;
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
     * Enables the distinct flag
     *
     * @return \r8\Query\Select Returns a self reference
     */
    public function distinct ()
    {
        return $this->setDistinct( TRUE );
    }

    /**
     * Sets whether the SQL_CALC_FOUND_ROWS flag should be set
     *
     * @param Boolean $foundRows Whether the foundRows flag should be set
     * @return \r8\Query\Select Returns a self reference
     */
    public function setFoundRows ( $foundRows )
    {
        $this->foundRows = (bool) $foundRows;
        return $this;
    }

    /**
     * Returns whether the SQL_CALC_FOUND_ROWS flag is set
     *
     * @return Boolean
     */
    public function getFoundRows ()
    {
        return $this->foundRows;
    }

    /**
     * Returns the Fields that will be selected
     *
     * @return array Returns an array of \r8\iface\Query\Selectable objects
     */
    public function getFields ()
    {
        return $this->fields;
    }

    /**
     * Adds a new field to the list of select fields
     *
     * @return \r8\Query\Select Returns a self reference
     */
    public function addField ( \r8\iface\Query\Selectable $field )
    {
        if ( !in_array($field, $this->fields, true) )
            $this->fields[] = $field;

        return $this;
    }

    /**
     * Clears all the select fields
     *
     * @return \r8\Query\Select Returns a self reference
     */
    public function clearFields ()
    {
        $this->fields = array();
        return $this;
    }

    /**
     * Selts the select field list to the given array
     *
     * This will clear out any currently set fields before adding the new ones
     *
     * @param Array $fields An array of \r8\iface\Query\Selectable objects
     * @return \r8\Query\Select Returns a self reference
     */
    public function setFields ( array $fields )
    {
        $this->fields = array();

        foreach ( $fields AS $field ) {
            if ( $field instanceof \r8\iface\Query\Selectable )
                $this->fields[] = $field;
        }

        return $this;
    }

    /**
     * Adds multiple select fields at once using a fluent interface
     *
     * @param mixed... $fields Any fields to add. This can be a string
     *      or a selectable object
     * @return \r8\Query\Select Returns a self reference
     */
    public function fields ()
    {
        foreach ( func_get_args() AS $arg )
        {
            // If they didn't give us an object, create one
            if ( !( $arg instanceof \r8\iface\Query\Selectable ) )
                $arg = \r8\Query\Expr\Aliased::fromString( $arg );

            $this->addField( $arg );
        }

        return $this;
    }

    /**
     * Returns the From clause for the query
     *
     * @return \r8\iface\Query\From Returns NULL if no query has been set
     */
    public function getFrom ()
    {
        return $this->from;
    }

    /**
     * Sets the From clause for the query
     *
     * @param \r8\iface\Query\From $from
     * @return \r8\Query\Select Returns a self reference
     */
    public function setFrom ( \r8\iface\Query\From $from )
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
     * @return \r8\Query\Select Returns a self reference
     */
    public function clearFrom ()
    {
        $this->from = null;
        return $this;
    }

    /**
     * A fluent interface for setting the from value
     *
     * @param mixed $from A string or a selectable expression
     * @return \r8\Query\Select Returns a self reference
     */
    public function from ( $from )
    {
        if ( !($from instanceof \r8\iface\Query\From) )
            $from = \r8\Query\From\Table::fromString( $from );

        return $this->setFrom( $from );
    }

    /**
     * Returns the Where clause for the query
     *
     * @return \r8\iface\Query\Where
     */
    public function getWhere ()
    {
        return $this->where;
    }

    /**
     * Sets the Where clause
     *
     * @param \r8\iface\Query\Where $where
     * @return \r8\Query\Select Returns a self reference
     */
    public function setWhere ( \r8\iface\Query\Where $where )
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
     * @return \r8\Query\Select Returns a self reference
     */
    public function clearWhere ()
    {
        $this->where = null;
        return $this;
    }

    /**
     * Sets the where clause in this instance from a mixed source
     *
     * @param \r8\iface\Query\Where|String $where This will take a string
     *      or a Where object
     * @return \r8\Query\Select Returns a self reference
     */
    public function where ( $where )
    {
        if ( !($where instanceof \r8\iface\Query\Where) )
            $where = new \r8\Query\Where\Raw( $where );

        return $this->setWhere( $where );
    }

    /**
     * Joins the current where clause with a given where clause using
     * an "and" relationship.
     *
     * If the current where clause is already an "and" object, the new
     * clause will simply be appended. Otherwise, a new "and" object will
     * be created and the two will be loaded into it.
     *
     * @param \r8\iface\Query\Where|String $where This will take a string
     *      or a Where object
     * @return \r8\Query\Select Returns a self reference
     */
    public function andWhere ( $where )
    {
        if ( !($where instanceof \r8\iface\Query\Where) )
            $where = new \r8\Query\Where\Raw( $where );

        if ( !($this->where instanceof \r8\Query\Where\LogicAnd) )
            $this->where = new \r8\Query\Where\LogicAnd( $this->where );

        $this->where->addClause( $where );

        return $this;
    }

    /**
     * Joins the current where clause with a given where clause using
     * an "or" relationship.
     *
     * If the current where clause is already an "or" object, the new
     * clause will simply be appended. Otherwise, a new "or" object will
     * be created and the two will be loaded into it.
     *
     * @param \r8\iface\Query\Where|String $where This will take a string
     *      or a Where object
     * @return \r8\Query\Select Returns a self reference
     */
    public function orWhere ( $where )
    {
        if ( !($where instanceof \r8\iface\Query\Where) )
            $where = new \r8\Query\Where\Raw( $where );

        if ( !($this->where instanceof \r8\Query\Where\LogicOr) )
            $this->where = new \r8\Query\Where\LogicOr( $this->where );

        $this->where->addClause( $where );

        return $this;
    }

    /**
     * Returns the Fields that the results will be ordered by
     *
     * @return array Returns an array of \r8\iface\Query\Ordered objects
     */
    public function getOrder ()
    {
        return $this->order;
    }

    /**
     * Adds a new field to the ORDER BY clause
     *
     * @param \r8\iface\Query\Ordered $field
     * @return \r8\Query\Select Returns a self reference
     */
    public function addOrder ( \r8\iface\Query\Ordered $field )
    {
        if ( !in_array($field, $this->order, true) )
            $this->order[] = $field;

        return $this;
    }

    /**
     * Clears all the ORDER BY fields
     *
     * @return \r8\Query\Select Returns a self reference
     */
    public function clearOrder ()
    {
        $this->order = array();
        return $this;
    }

    /**
     * Adds multiple "Order By" fields at once using a fluent interface
     *
     * @param \r8\Query\iface\Ordered $fields... Any fields to add.
     *      This can be a string or an ordered object
     * @return \r8\Query\Select Returns a self reference
     */
    public function orderBy ()
    {
        foreach ( func_get_args() AS $arg )
        {
            // If they didn't give us an object, create one
            if ( !( $arg instanceof \r8\iface\Query\Ordered ) )
                $arg = \r8\Query\Expr\Ordered::fromString( $arg );

            $this->addOrder( $arg );
        }

        return $this;
    }

    /**
     * Returns the Fields that the results will be grouped by
     *
     * @return array Returns an array of \r8\iface\Query\Ordered objects
     */
    public function getGroup ()
    {
        return $this->group;
    }

    /**
     * Adds a new field to the GROUP BY clause
     *
     * @param \r8\iface\Query\Ordered $field
     * @return \r8\Query\Select Returns a self reference
     */
    public function addGroup ( \r8\iface\Query\Ordered $field )
    {
        if ( !in_array($field, $this->group, true) )
            $this->group[] = $field;

        return $this;
    }

    /**
     * Clears all the GROUP BY fields
     *
     * @return \r8\Query\Select Returns a self reference
     */
    public function clearGroup ()
    {
        $this->group = array();
        return $this;
    }

    /**
     * Adds multiple "Group By" fields at once using a fluent interface
     *
     * @param \r8\Query\iface\Ordered $fields... Any fields to add.
     *      This can be a string or an ordered object
     * @return \r8\Query\Select Returns a self reference
     */
    public function groupBy ()
    {
        foreach ( func_get_args() AS $arg )
        {
            // If they didn't give us an object, create one
            if ( !( $arg instanceof \r8\iface\Query\Ordered ) )
                $arg = \r8\Query\Expr\Ordered::fromString( $arg );

            $this->addGroup( $arg );
        }

        return $this;
    }

    /**
     * Returns the Having clause for the query
     *
     * @return \r8\iface\Query\Where
     */
    public function getHaving ()
    {
        return $this->having;
    }

    /**
     * Sets the Having clause for the query
     *
     * @param \r8\iface\Query\Where $having
     * @return \r8\Query\Select Returns a self reference
     */
    public function setHaving ( \r8\iface\Query\Where $having )
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
     * @return \r8\Query\Select Returns a self reference
     */
    public function clearHaving ()
    {
        $this->having = null;
        return $this;
    }

    /**
     * Sets the "Having" clause in this instance from a mixed source
     *
     * @param \r8\iface\Query\Where|String $having This will take a string
     *      or a Where object
     * @return \r8\Query\Select Returns a self reference
     */
    public function having ( $having )
    {
        if ( !($having instanceof \r8\iface\Query\Where) )
            $having = new \r8\Query\Where\Raw( $having );

        return $this->setHaving( $having );
    }

    /**
     * Joins the current "Having" clause with a given "Having" clause using
     * an "and" relationship.
     *
     * If the current "Having" clause is already an "and" object, the new
     * clause will simply be appended. Otherwise, a new "and" object will
     * be created and the two will be loaded into it.
     *
     * @param \r8\iface\Query\Where|String $having This will take a string
     *      or a Where object
     * @return \r8\Query\Select Returns a self reference
     */
    public function andHaving ( $having )
    {
        if ( !($having instanceof \r8\iface\Query\Where) )
            $having = new \r8\Query\Where\Raw( $having );

        if ( !($this->having instanceof \r8\Query\Where\LogicAnd) )
            $this->having = new \r8\Query\Where\LogicAnd( $this->having );

        $this->having->addClause( $having );

        return $this;
    }

    /**
     * Joins the current "Having" clause with a given "Having" clause using
     * an "or" relationship.
     *
     * If the current "Having" clause is already an "or" object, the new
     * clause will simply be appended. Otherwise, a new "or" object will
     * be created and the two will be loaded into it.
     *
     * @param \r8\iface\Query\Where|String $having This will take a string
     *      or a Where object
     * @return \r8\Query\Select Returns a self reference
     */
    public function orHaving ( $having )
    {
        if ( !($having instanceof \r8\iface\Query\Where) )
            $having = new \r8\Query\Where\Raw( $having );

        if ( !($this->having instanceof \r8\Query\Where\LogicOr) )
            $this->having = new \r8\Query\Where\LogicOr( $this->having );

        $this->having->addClause( $having );

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
     * @return \r8\Query\Select Returns a self reference
     */
    public function setLimit ( $limit )
    {
        $this->limit = (int) $limit;

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
     * @return \r8\Query\Select Returns a self reference
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
     * @return \r8\Query\Select Returns a self reference
     */
    public function setOffset ( $offset )
    {
        $this->offset = (int) $offset;

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
     * @return \r8\Query\Select Returns a self reference
     */
    public function clearOffset ()
    {
        $this->offset = null;
        return $this;
    }

    /**
     * Sets the Limit and Offset using a fluent interface
     *
     * @param Integer $limit The result limit
     * @param Integer $offset The result offset
     * @return \r8\Query\Select Returns a self reference
     */
    public function limit ( $limit, $offset = 0 )
    {
        return $this->setLimit( $limit )->setOffset( $offset );
    }

    /**
     * Returns the SQL this object represents
     *
     * @param \r8\iface\DB\Link $link The database link to use for escaping
     * @return String
     */
    public function toSQL ( \r8\iface\DB\Link $link )
    {
        $sql = "SELECT ";

        if ( $this->distinct )
            $sql .= "DISTINCT ";

        if ( $this->foundRows )
            $sql .= "SQL_CALC_FOUND_ROWS ";

        if ( count($this->fields) <= 0 ) {
            $sql .= "*";
        }
        else {
            $sql .= implode(
                ", ",
                \r8\ary\invoke( $this->fields, "toSelectSQL", $link )
            );
        }

        if ( $this->from )
            $sql .= "\nFROM ". $this->from->toFromSQL( $link );

        if ( $this->where )
            $sql .= "\nWHERE ". $this->where->toWhereSQL( $link );

        if ( count($this->order) > 0 ) {
            $sql .= "\nORDER BY ". implode(
                ", ",
                \r8\ary\invoke( $this->order, "toOrderedSQL", $link )
            );
        }

        if ( count($this->group) > 0 ) {
            $sql .= "\nGROUP BY ". implode(
                ", ",
                \r8\ary\invoke( $this->group, "toOrderedSQL", $link )
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