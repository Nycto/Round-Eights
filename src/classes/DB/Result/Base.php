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
 * @copyright Copyright 2009, James Frasca, All Rights Reserved
 * @package Database
 */

namespace r8\DB\Result;

/**
 * The base class for Database Query results
 */
abstract class Base
{

    /**
     * The query associated with these results
     *
     * @var String
     */
    private $query;
    
    /**
     * The query result adapter that provides a standard way to interface with
     * the results
     *
     * @var \r8\iface\DB\Adapter\Result
     */
    private $adapter;

    /**
     * Constructor...
     *
     * @param String $query The query that produced this result
     * @param \r8\iface\DB\Adapter\Result $adapter The query result adapter that
     *      provides a standard way to interface with the results
     */
    public function __construct ( $query, \r8\iface\DB\Adapter\Result $adapter )
    {
        $this->query = (string) $query;
        $this->adapter = $adapter;
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
    
    /**
     * Returns the Adapter that provides an interface for interacting with the adapters
     *
     * @return \r8\iface\DB\Adapter\Result
     */
    public function getAdapter ()
    {
       return $this->adapter;
    }

}

?>