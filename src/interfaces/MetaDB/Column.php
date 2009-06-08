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
 * @package MetaDB
 */

namespace cPHP\iface\MetaDB;

/**
 * The basic implementation of a database column
 */
interface Column
{

    /**
     * Returns the SQL needed to insert a value into this field
     *
     * @param mixed $value The value being inserted into this field
     * @return String
     */
    public function getInsertSQL ( $value );

    /**
     * Returns the SQL needed to update a value in this field
     *
     * @param mixed $value The new value for the field
     * @return String
     */
    public function getUpdateSQL ( $value );

    /**
     * Returns the SQL needed to select a value from this field
     *
     * @return String
     */
    public function getSelectSQL ();

    /**
     * Quotes a value for use in a SQL query
     *
     * @param mixed $value The value to quote
     * @return String
     */
    public function quote ( $value );

    /**
     * Returns the name of this column
     *
     * @return String
     */
    public function getName ();

}

?>