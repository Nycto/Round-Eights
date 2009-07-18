<?php
/**
 * Database Query Result
 *
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
 * @package Database
 */

namespace h2o\iface\DB\Result;

/**
 * Database Read Query Results
 */
interface Read extends \Countable, \SeekableIterator
{

    /**
     * Returns whether this instance currently holds a valid resource
     *
     * @return Boolean
     */
    public function hasResult ();

    /**
     * Returns a list of field names returned by the query
     *
     * @return Array
     */
    public function getFields ();

    /**
     * Returns whether a field exists in the results
     *
     * @param String $field The case-sensitive field name
     * @return Boolean
     */
    public function isField ( $field );

    /**
     * Returns the number of fields in the result set
     *
     * @return Integer
     */
    public function fieldCount ();

    /**
     * Frees the resource in this instance
     *
     * @return Object Returns a self reference
     */
    public function free ();

}

?>