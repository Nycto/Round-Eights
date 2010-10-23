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
 * @package Query
 */

namespace r8\Query\From;

/**
 * Allows a Raw string to be used in a From clause
 */
class Raw implements \r8\iface\Query\From
{

    /**
     * The raw SQL to pass through
     *
     * @var String
     */
    private $sql;

    /**
     * The alias for this table
     *
     * @var String
     */
    private $alias;

    /**
     * Constructor...
     *
     * @param String $sql The raw SQL to pass through
     * @param String $alias The alias to apply to this table
     */
    public function __construct ( $sql, $alias = null )
    {
        $this->sql = (string) $sql;
        $this->setAlias( $alias );
    }

    /**
     * Returns the Alias
     *
     * @return String
     */
    public function getAlias ()
    {
        return $this->alias;
    }

    /**
     * Sets the Alias
     *
     * @param String $alias The alias of this field
     * @return \r8\Query\From\Table Returns a self reference
     */
    public function setAlias ( $alias )
    {
        $alias = \r8\str\stripW( $alias );
        $this->alias = $alias ? $alias : null;
        return $this;
    }

    /**
     * Returns whether the Alias has been set
     *
     * @return Boolean
     */
    public function aliasExists ()
    {
        return isset( $this->alias );
    }

    /**
     * Clears the currently set Alias
     *
     * @return \r8\Query\From\Table Returns a self reference
     */
    public function clearAlias ()
    {
        $this->alias = null;
        return $this;
    }

    /**
     * Returns the SQL FROM clause
     *
     * @param \r8\iface\DB\Link $link The database connection this WHERE clause
     *      is being run against. This is being passed in for escaping purposes
     * @return String
     */
    public function toFromSQL ( \r8\iface\DB\Link $link )
    {
        return
            $this->sql
            .( $this->alias ? " AS `". $this->alias ."`" : "" );
    }

}

