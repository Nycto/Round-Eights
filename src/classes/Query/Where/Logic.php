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

namespace cPHP\Query\Where;

/**
 * Combines multiple where expressions with a logical clause
 */
abstract class Logic implements \cPHP\iface\Query\Where
{

    /**
     * The list of WHERE clauses to combine
     *
     * @var array An array of \cPHP\iface\Query\Where objects
     */
    private $clauses = array();

    /**
     * Returns the list of WHERE clauses
     *
     * @return array An array of \cPHP\iface\Query\Where objects
     */
    public function getClauses ()
    {
        return $this->clauses;
    }

    /**
     * Adds a new WHERE clause to the list
     * @param \cPHP\iface\Query\Where $where The WHERE clause to add
     * @return \cPHP\Query\Where\Logic Returns a self reference
     */
    public function addClause ( \cPHP\iface\Query\Where $where )
    {
        if ( !in_array( $where, $this->clauses, true) )
            $this->clauses[] = $where;

        return $this;
    }

    /**
     * Clears all the Where clauses out of the list
     *
     * @return \cPHP\Query\Where\Logic Returns a self reference
     */
    public function clearClauses ()
    {
        $this->clauses = array();
        return $this;
    }

}

?>