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

namespace h2o\MetaDB\Row;

/**
 * The base class for a generic database Row
 */
class Generic implements \h2o\iface\MetaDB\Row
{

    /**
     * The list of values in this Row
     *
     * @var Array
     */
    private $values = array();

    /**
     * The list of fields represented by this row
     *
     * @var Array
     */
    private $columns = array();

    /**
     * Constructor...
     *
     * @param Array $values The list of values in this Row
     * @param Array $columns The list of fields represented by this row
     */
    public function __construct ( array $values, array $columns )
    {
        foreach ( $columns AS $column ) {
            if ( $column instanceof \h2o\iface\MetaDB\Column ) {
                $name = $column->getName();
                $this->columns[ $name ] = $column;

                if ( array_key_exists( $name, $values ) )
                    $this->values[ $name ] = $column->filterSelected( $values[ $name ] );
            }
        }
    }

    /**
     * Returns the list of values as an indexed array
     *
     * @return Array
     */
    public function getValues ()
    {
        return $this->values;
    }

    /**
     * Returns the Columns represented by this row
     *
     * @return array
     */
    public function getColumns ()
    {
        return $this->columns;
    }

    /**
     * Provides access to the data in this row as a class property
     *
     * @param String $column The column value to access
     * @return mixed
     */
    public function __get ( $column )
    {
        if ( array_key_exists($column, $this->values) )
            return $this->values[ $column ];
        else
            throw new \h2o\Exception\Variable($column, "Undefined column");
    }

    /**
     * Returns whether a specific column has been set via class a property
     *
     * @param String $column The column value to access
     * @return Boolean
     */
    public function __isset ( $column )
    {
        return isset( $this->values[$column] );
    }

}

?>