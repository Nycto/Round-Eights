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
 * @package MetaDB
 */

namespace h2o\iface\Query;

/**
 * A SQL Where Expression
 */
interface Where
{

    /**
     * Returns the SQL Where expression represented by this object
     *
     * @param \h2o\iface\DB\Link $link The database connection this WHERE clause
     * 		is being run against. This is being passed in for escaping purposes
     * @return String
     */
    public function toWhereSQL( \h2o\iface\DB\Link $link );

    /**
     * Returns the precedence level of this clause
     *
     * The number this returns is arbitrary, it's only importance is it's value
     * relative to other where clauses. A higher value means this clause
     * has a more important order of operation.
     *
     * @return Integer
     */
    public function getPrecedence ();

}

?>