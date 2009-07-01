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
 * @package Query
 */

namespace h2o\Query\Where;

/**
 * Combines multiple where expressions with a logical clause
 */
abstract class Logic implements \h2o\iface\Query\Where
{

    /**
     * The list of WHERE clauses to combine
     *
     * @var array An array of \h2o\iface\Query\Where objects
     */
    private $clauses = array();

    /**
     * Constructor...
     *
     * @param $clauses... \h2o\iface\Query\Where Any initial where clauses
     * 		to set in this instance
     */
    public function __construct ()
    {
        if ( func_num_args() > 0 ) {
            foreach ( func_get_args() AS $arg ) {
                if ( $arg instanceof \h2o\iface\Query\Where )
                    $this->addClause( $arg );
            }
        }
    }

    /**
     * Returns the list of WHERE clauses
     *
     * @return array An array of \h2o\iface\Query\Where objects
     */
    public function getClauses ()
    {
        return $this->clauses;
    }

    /**
     * Adds a new WHERE clause to the list
     * @param \h2o\iface\Query\Where $where The WHERE clause to add
     * @return \h2o\Query\Where\Logic Returns a self reference
     */
    public function addClause ( \h2o\iface\Query\Where $where )
    {
        if ( !in_array( $where, $this->clauses, true) )
            $this->clauses[] = $where;

        return $this;
    }

    /**
     * Clears all the Where clauses out of the list
     *
     * @return \h2o\Query\Where\Logic Returns a self reference
     */
    public function clearClauses ()
    {
        $this->clauses = array();
        return $this;
    }

    /**
     * Returns the number of clauses in this instance
     *
     * @return Integer
     */
    public function countClauses ()
    {
        return count( $this->clauses );
    }

    /**
     * Returns the delimiter that will be used to combine the WHERE clauses
     *
     * @return String
     */
    abstract protected function getDelimiter ();

    /**
     * Returns the SQL Where expression represented by this object
     *
     * @param \h2o\iface\DB\Link $link The database connection this WHERE clause
     * 		is being run against. This is being passed in for escaping purposes
     * @return String
     */
    public function toWhereSQL( \h2o\iface\DB\Link $link )
    {
        $prec = $this->getPrecedence();
        $result = array();

        foreach ( $this->clauses AS $clause )
        {
            $sql = trim( $clause->toWhereSQL( $link ) );

            if ( \h2o\IsEmpty($sql) )
                continue;

            // Wrap the clause in parenthesis if it has lower precedence
            // For a list of operation precedence, look here:
            // http://dev.mysql.com/doc/refman/5.4/en/operator-precedence.html
            if ( $clause->getPrecedence() < $prec )
                $sql = "(". $sql .")";

            $result[] = $sql;
        }

        // Combine all the sub-clauses with the delimiter
        return implode(
        		" ". trim( $this->getDelimiter() ) ." ",
                $result
            );
    }

}

?>