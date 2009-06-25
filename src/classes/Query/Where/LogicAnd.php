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
 * A logical AND clause
 */
class LogicAnd extends \cPHP\Query\Where\Logic
{

    /**
     * Returns the delimiter that will be used to combine the WHERE clauses
     *
     * @return String
     */
    protected function getDelimiter ()
    {
        return "AND";
    }

    /**
     * Returns the precedence level of this clause
     *
     * The number this returns is arbitrary, it's only importance is it's value
     * relative to other where clauses. A higher value means this clause
     * has a more important order of operation.
     *
     * @return Integer
     */
    public function getPrecedence ()
    {
        if ( $this->countClauses() != 1 )
            return 70;

        // If there is only one clause in this instance, then mask the precedence
        // to reduce the number of parenthesis that are added
        return \cPHP\ary\first( $this->getClauses() )->getPrecedence();
    }

}

?>