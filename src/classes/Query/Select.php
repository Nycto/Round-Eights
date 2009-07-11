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
 * @package Query
 */

namespace h2o\Query;

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
     * @var \h2o\iface\Query\From
     */
    private $from;

    /**
     * The root WHERE clause
     *
     * @var \h2o\iface\Query\Where
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
     * @var \h2o\iface\Query\Where
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
     * @param \h2o\iface\Query\From $from The from clause for the query
     */
    public function __construct (\h2o\iface\Query\From $from = null )
    {
        if ( $from )
            $this->setFrom( $from );
    }

    /**
     * Sets whether the DISTINCT flag should be set
     *
     * @param Boolean $distinct Whether the distinct flag should be set
     * @return \h2o\Query\Select Returns a self reference
     */
    public function setDistinct ( $distinct )
    {
        $this->distinct = \h2o\boolVal( $distinct );
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
     * @return \h2o\Query\Select Returns a self reference
     */
    public function distinct ()
    {
        return $this->setDistinct( TRUE );
    }

    /**
     * Sets whether the SQL_CALC_FOUND_ROWS flag should be set
     *
     * @param Boolean $foundRows Whether the foundRows flag should be set
     * @return \h2o\Query\Select Returns a self reference
     */
    public function setFoundRows ( $foundRows )
    {
        $this->foundRows = \h2o\boolVal( $foundRows );
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
     * @return array Returns an array of \h2o\iface\Query\Selectable objects
     */
    public function getFields ()
    {
        return $this->fields;
    }

    /**
     * Adds a new field to the list of select fields
     *
     * @return \h2o\Query\Select Returns a self reference
     */
    public function addField ( \h2o\iface\Query\Selectable $field )
    {
        if ( !in_array($field, $this->fields, true) )
            $this->fields[] = $field;

        return $this;
    }

    /**
     * Clears all the select fields
     *
     * @return \h2o\Query\Select Returns a self reference
     */
    public function clearFields ()
    {
        $this->fields = array();
        return $this;
    }

    /**
     * Adds multiple select fields at once using a fluent interface
     *
     * @param mixed... $fields Any fields to add. This can be a string
     * 		or a selectable object
     * @return \h2o\Query\Select Returns a self reference
     */
    public function fields ()
    {
        foreach ( func_get_args() AS $arg )
        {
            // If they didn't give us an object, create one
            if ( !( $arg instanceof \h2o\iface\Query\Selectable ) )
                $arg = \h2o\Query\Expr\Aliased::fromString( $arg );

            $this->addField( $arg );
        }

        return $this;
    }

    /**
     * Returns the From clause for the query
     *
     * @return \h2o\iface\Query\From Returns NULL if no query has been set
     */
    public function getFrom ()
    {
        return $this->from;
    }

    /**
     * Sets the From clause for the query
     *
     * @param \h2o\iface\Query\From $from
     * @return \h2o\Query\Select Returns a self reference
     */
    public function setFrom ( \h2o\iface\Query\From $from )
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
     * @return \h2o\Query\Select Returns a self reference
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
     * @return \h2o\Query\Select Returns a self reference
     */
    public function from ( $from )
    {
        if ( !($from instanceof \h2o\iface\Query\From) )
            $from = \h2o\Query\From\Table::fromString( $from );

        return $this->setFrom( $from );
    }

    /**
     * Returns the Where clause for the query
     *
     * @return \h2o\iface\Query\Where
     */
    public function getWhere ()
    {
        return $this->where;
    }

    /**
     * Sets the Where clause
     *
     * @param \h2o\iface\Query\Where $where
     * @return \h2o\Query\Select Returns a self reference
     */
    public function setWhere ( \h2o\iface\Query\Where $where )
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
     * @return \h2o\Query\Select Returns a self reference
     */
    public function clearWhere ()
    {
        $this->where = null;
        return $this;
    }

    /**
     * Sets the where clause in this instance from a mixed source
     *
     * @param \h2o\iface\Query\Where|String $where This will take a string
     * 		or a Where object
     * @return \h2o\Query\Select Returns a self reference
     */
    public function where ( $where )
    {
        if ( !($where instanceof \h2o\iface\Query\Where) )
            $where = new \h2o\Query\Where\Raw( $where );

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
     * @param \h2o\iface\Query\Where|String $where This will take a string
     * 		or a Where object
     * @return \h2o\Query\Select Returns a self reference
     */
    public function andWhere ( $where )
    {
        if ( !($where instanceof \h2o\iface\Query\Where) )
            $where = new \h2o\Query\Where\Raw( $where );

        if ( !($this->where instanceof \h2o\Query\Where\LogicAnd) )
            $this->where = new \h2o\Query\Where\LogicAnd( $this->where );

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
     * @param \h2o\iface\Query\Where|String $where This will take a string
     * 		or a Where object
     * @return \h2o\Query\Select Returns a self reference
     */
    public function orWhere ( $where )
    {
        if ( !($where instanceof \h2o\iface\Query\Where) )
            $where = new \h2o\Query\Where\Raw( $where );

        if ( !($this->where instanceof \h2o\Query\Where\LogicOr) )
            $this->where = new \h2o\Query\Where\LogicOr( $this->where );

        $this->where->addClause( $where );

        return $this;
    }

    /**
     * Returns the Fields that the results will be ordered by
     *
     * @return array Returns an array of \h2o\iface\Query\Ordered objects
     */
    public function getOrder ()
    {
        return $this->order;
    }

    /**
     * Adds a new field to the ORDER BY clause
     *
     * @param \h2o\iface\Query\Ordered $field
     * @return \h2o\Query\Select Returns a self reference
     */
    public function addOrder ( \h2o\iface\Query\Ordered $field )
    {
        if ( !in_array($field, $this->order, true) )
            $this->order[] = $field;

        return $this;
    }

    /**
     * Clears all the ORDER BY fields
     *
     * @return \h2o\Query\Select Returns a self reference
     */
    public function clearOrder ()
    {
        $this->order = array();
        return $this;
    }

    /**
     * Adds multiple "Order By" fields at once using a fluent interface
     *
     * @param \h2o\Query\iface\Ordered $fields... Any fields to add.
     * 		This can be a string or an ordered object
     * @return \h2o\Query\Select Returns a self reference
     */
    public function orderBy ()
    {
        foreach ( func_get_args() AS $arg )
        {
            // If they didn't give us an object, create one
            if ( !( $arg instanceof \h2o\iface\Query\Ordered ) )
                $arg = \h2o\Query\Expr\Ordered::fromString( $arg );

            $this->addOrder( $arg );
        }

        return $this;
    }

    /**
     * Returns the Fields that the results will be grouped by
     *
     * @return array Returns an array of \h2o\iface\Query\Ordered objects
     */
    public function getGroup ()
    {
        return $this->group;
    }

    /**
     * Adds a new field to the GROUP BY clause
     *
     * @param \h2o\iface\Query\Ordered $field
     * @return \h2o\Query\Select Returns a self reference
     */
    public function addGroup ( \h2o\iface\Query\Ordered $field )
    {
        if ( !in_array($field, $this->group, true) )
            $this->group[] = $field;

        return $this;
    }

    /**
     * Clears all the GROUP BY fields
     *
     * @return \h2o\Query\Select Returns a self reference
     */
    public function clearGroup ()
    {
        $this->group = array();
        return $this;
    }

    /**
     * Adds multiple "Group By" fields at once using a fluent interface
     *
     * @param \h2o\Query\iface\Ordered $fields... Any fields to add.
     * 		This can be a string or an ordered object
     * @return \h2o\Query\Select Returns a self reference
     */
    public function groupBy ()
    {
        foreach ( func_get_args() AS $arg )
        {
            // If they didn't give us an object, create one
            if ( !( $arg instanceof \h2o\iface\Query\Ordered ) )
                $arg = \h2o\Query\Expr\Ordered::fromString( $arg );

            $this->addGroup( $arg );
        }

        return $this;
    }

    /**
     * Returns the Having clause for the query
     *
     * @return \h2o\iface\Query\Where
     */
    public function getHaving ()
    {
        return $this->having;
    }

    /**
     * Sets the Having clause for the query
     *
     * @param \h2o\iface\Query\Where $having
     * @return \h2o\Query\Select Returns a self reference
     */
    public function setHaving ( \h2o\iface\Query\Where $having )
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
     * @return \h2o\Query\Select Returns a self reference
     */
    public function clearHaving ()
    {
        $this->having = null;
        return $this;
    }

    /**
     * Sets the "Having" clause in this instance from a mixed source
     *
     * @param \h2o\iface\Query\Where|String $having This will take a string
     * 		or a Where object
     * @return \h2o\Query\Select Returns a self reference
     */
    public function having ( $having )
    {
        if ( !($having instanceof \h2o\iface\Query\Where) )
            $having = new \h2o\Query\Where\Raw( $having );

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
     * @param \h2o\iface\Query\Where|String $having This will take a string
     * 		or a Where object
     * @return \h2o\Query\Select Returns a self reference
     */
    public function andHaving ( $having )
    {
        if ( !($having instanceof \h2o\iface\Query\Where) )
            $having = new \h2o\Query\Where\Raw( $having );

        if ( !($this->having instanceof \h2o\Query\Where\LogicAnd) )
            $this->having = new \h2o\Query\Where\LogicAnd( $this->having );

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
     * @param \h2o\iface\Query\Where|String $having This will take a string
     * 		or a Where object
     * @return \h2o\Query\Select Returns a self reference
     */
    public function orHaving ( $having )
    {
        if ( !($having instanceof \h2o\iface\Query\Where) )
            $having = new \h2o\Query\Where\Raw( $having );

        if ( !($this->having instanceof \h2o\Query\Where\LogicOr) )
            $this->having = new \h2o\Query\Where\LogicOr( $this->having );

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
     * @return \h2o\Query\Select Returns a self reference
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
     * @return \h2o\Query\Select Returns a self reference
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
     * @return \h2o\Query\Select Returns a self reference
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
     * @return \h2o\Query\Select Returns a self reference
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
     * @return \h2o\Query\Select Returns a self reference
     */
    public function limit ( $limit, $offset = 0 )
    {
        return $this->setLimit( $limit )->setOffset( $offset );
    }

    /**
     * Returns the SQL this object represents
     *
     * @param \h2o\iface\DB\Link $link The database link to use for escaping
     * @return String
     */
    public function toSQL ( \h2o\iface\DB\Link $link )
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
                \h2o\ary\invoke( $this->fields, "toSelectSQL", $link )
            );
        }

        if ( $this->from )
            $sql .= "\nFROM ". $this->from->toFromSQL( $link );

        if ( $this->where )
            $sql .= "\nWHERE ". $this->where->toWhereSQL( $link );

        if ( count($this->order) > 0 ) {
            $sql .= "\nORDER BY ". implode(
        		", ",
                \h2o\ary\invoke( $this->order, "toOrderedSQL", $link )
            );
        }

        if ( count($this->group) > 0 ) {
            $sql .= "\nGROUP BY ". implode(
        		", ",
                \h2o\ary\invoke( $this->group, "toOrderedSQL", $link )
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