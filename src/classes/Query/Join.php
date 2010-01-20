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

namespace r8\Query;

/**
 * A base class for common join methods
 */
abstract class Join implements \r8\iface\Query\Joinable
{

    /**
     * The table being joined
     *
     * @var \r8\iface\Query\From
     */
    private $table;

    /**
     * The condition on which to join
     *
     * @var \r8\iface\Query\Where
     */
    private $on;

    /**
     * Constructor...
     *
     * @param \r8\iface\Query\From $table The table being joined
     * @param \r8\iface\Query\Where $on The condition on which to join
     */
    public function __construct (
        \r8\iface\Query\From $table,
        \r8\iface\Query\Where $on = NULL
    ) {
        $this->table = $table;
        $this->on = $on;
    }

    /**
     * Returns the Table being joined
     *
     * @return \r8\iface\Query\From
     */
    public function getTable ()
    {
        return $this->table;
    }

    /**
     * Sets the Table to join
     *
     * @param \r8\iface\Query\From $table
     * @return \r8\Query\Join Returns a self reference
     */
    public function setTable ( \r8\iface\Query\From $table )
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Returns the condition on which the tables will be joined
     *
     * @return \r8\iface\Query\Where
     */
    public function getOn ()
    {
        return $this->on;
    }

    /**
     * Sets the condition on which the tables will be joined
     *
     * @param \r8\iface\Query\Where $on
     * @return \r8\Query\Join Returns a self reference
     */
    public function setOn ( \r8\iface\Query\Where $on )
    {
        $this->on = $on;
        return $this;
    }

    /**
     * Returns whether a Join "On" condition has been set
     *
     * @return Boolean
     */
    public function onExists ()
    {
        return isset($this->on);
    }

    /**
     * Clears the On condition from this Join clause
     *
     * @return \r8\Query\Join Returns a self reference
     */
    public function clearOn ()
    {
        $this->on = NULL;
        return $this;
    }

    /**
     * Returns the type of join this instance represents
     *
     * @return String
     */
    abstract public function getJoinType ();

    /**
     * Returns the SQL JOIN clause
     *
     * @param \r8\iface\DB\Link $link The database connection this WHERE clause
     *      is being run against. This is being passed in for escaping purposes
     * @return String
     */
    public function toJoinSQL ( \r8\iface\DB\Link $link )
    {
        $sql = $this->getJoinType()
            ." "
            .$this->table->toFromSQL( $link );

        if ( $this->on )
            $sql .= " ON ". $this->on->toWhereSQL( $link );

        return $sql;
    }

}

?>