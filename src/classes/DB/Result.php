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
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Database
 */

namespace r8\DB;

/**
 * Database Query Result
 */
abstract class Result
{

    /**
     * The query associated with these results
     */
    private $query;

    /**
     * Constructor...
     *
     * @param String $query The query that produced this result
     */
    public function __construct ( $query )
    {
        $this->query = \r8\strval($query);
    }

    /**
     * Returns the query associated with this result
     *
     * @return String
     */
    public function getQuery ()
    {
        return $this->query;
    }

}

?>