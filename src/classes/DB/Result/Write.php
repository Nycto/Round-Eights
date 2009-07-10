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
 * @author James Frasca <james@Raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Database
 */

namespace h2o\DB\Result;

/**
 * Database Read Query Results
 */
class Write extends \h2o\DB\Result
{

    /**
     * This is the cached value of the affected number of rows
     */
    private $affected;

    /**
     * This is the cached value of the insert ID
     */
    private $insertID;

    /**
     * Constructor...
     *
     * @param Integer|NULL $affected The number of rows affected by this query
     * @param Integer|NULL $insertID The ID of the row inserted by this query
     * @param String $query The query that produced this result
     */
    public function __construct ( $affected, $insertID, $query )
    {
        if ( !\h2o\isVague($insertID) ) {
            $insertID = intval($insertID);
            $this->insertID = $insertID > 0 ? $insertID : NULL;
        }

        $this->affected = max( intval( $affected ), 0 );

        parent::__construct($query);
    }

    /**
     * Returns the number of rows affected by a query
     *
     * @return Integer|False
     */
    public function getAffected ()
    {
        return $this->affected;
    }

    /**
     * Returns the ID of the row inserted by this query
     *
     * @return Integer|False This will return FALSE if no ID is returned
     */
    public function getInsertID ()
    {
        return $this->insertID;
    }

}

?>