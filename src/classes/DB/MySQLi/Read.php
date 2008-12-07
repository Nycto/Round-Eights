<?php
/**
 * Database Read result
 *
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
 * @package Database
 */

namespace cPHP\DB\MySQLi;

/**
 * MySQLi Database read result
 */
class Read extends \cPHP\DB\Result\Read
{

    /**
     * Internal method that returns the number of rows found
     *
     * @return Integer
     */
    protected function rawCount ()
    {
        return $this->getResult()->num_rows;
    }

    /**
     * Internal method to fetch the next row in a result set
     *
     * @return Array Returns the field values
     */
    protected function rawFetch ()
    {
        return $this->getResult()->fetch_assoc();
    }

    /**
     * Internal method to seek to a specific row in a result resource
     *
     * @param Integer $offset The offset to seek to
     * @return Array Returns the field values
     */
    protected function rawSeek ($offset)
    {
        $this->getResult()->data_seek($offset);
        return $this->rawFetch();
    }

    /**
     * Internal method to get a list of field names returned
     *
     * @return Array
     */
    protected function rawFields ()
    {
        $fields = $this->getResult()->fetch_fields();

        foreach ( $fields AS $key => $field ) {
            $fields[ $key ] = $field->name;
        }

        return $fields;
    }

    /**
     * Internal method to free the result resource
     *
     * @return null
     */
    protected function rawFree ()
    {
        $result = $this->getResult();
        $result->free();
    }

}

?>