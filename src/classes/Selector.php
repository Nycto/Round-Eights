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
 * @package Selector
 */

namespace cPHP;

/**
 * Builds a Select SQL query
 */
class Selector
{

    /**
     * The table to select from
     *
     * @var \cPHP\iface\Selector\From
     */
    private $from;

    /**
     * The maximum number of rows to return
     *
     * @var Integer
     */
    private $limit;

    /**
     * The offset to begin selecting rows from
     *
     * @var Integer
     */
    private $offset;

    /**
     * Constructor...
     *
     * @param \cPHP\iface\Selector\From $from The table to select from
     */
    public function __construct ( \cPHP\iface\Selector\From $from )
    {
        $this->from = $from;
    }

    /**
     * Returns the Limit
     *
     * @return Integer
     */
    public function getLimit ()
    {
        return $this->limit;
    }

    /**
     * Sets the Limit
     *
     * @param Integer $limit
     * @return \cPHP\Selector Returns a self reference
     */
    public function setLimit ( $limit )
    {
        $this->limit = intval($limit);

        if ( $this->limit <= 0 )
            $this->limit = null;

        return $this;
    }

    /**
     * Returns whether the Limit has been set
     *
     * @return Boolean
     */
    public function limitExists ()
    {
        return isset( $this->limit );
    }

    /**
     * Clears the currently set Limit
     *
     * @return \cPHP\Selector Returns a self reference
     */
    public function clearLimit ()
    {
        $this->limit = null;
        return $this;
    }

    /**
     * Returns the Offset
     *
     * @return Integer
     */
    public function getOffset ()
    {
        return $this->offset;
    }

    /**
     * Sets the Offset
     *
     * @param Integer $offset
     * @return \cPHP\Selector Returns a self reference
     */
    public function setOffset ( $offset )
    {
        $this->offset = intval($offset);

        if ( $this->offset <= 0 )
            $this->offset = null;

        return $this;
    }

    /**
     * Returns whether the Offset has been set
     *
     * @return Boolean
     */
    public function offsetExists ()
    {
        return isset( $this->offset );
    }

    /**
     * Clears the currently set Offset
     *
     * @return \cPHP\Selector Returns a self reference
     */
    public function clearOffset ()
    {
        $this->offset = null;
        return $this;
    }

    /**
     * Returns the SQL this object represents
     *
     * @return String
     */
    public function toSQL ()
    {
        $fields = \cPHP\arrayVal( $this->from->getSQLFields() );
        $fields = \cPHP\ary\compact( $fields );
        $fields = count( $fields ) == 0 ? "*" : implode(", ", $fields);

        $sql = "SELECT $fields\n"
            ."FROM ". $this->from->getFromSQL();

        if ( $this->limitExists() )
        {
            $sql .= "\nLIMIT "
        		.( $this->offsetExists() ? $this->getOffset() : "0" )
            	.", ". $this->getLimit();
        }


        return $sql;
    }

}

?>