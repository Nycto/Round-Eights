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
 * @author James Frasca <james@Raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package MetaDB
 */

namespace h2o\MetaDB;

/**
 * The base class for a standard column
 */
abstract class Column implements \h2o\iface\MetaDB\Column
{

    /**
     * The table this column belongs to
     *
     * @var \h2o\MetaDB\Table
     */
    private $table;

    /**
     * The name of this column
     *
     * @var String
     */
    private $name;

    /**
     * Constructor...
     *
     * @param \h2o\MetaDB\Table $table The table this column belongs to
     * @param String $name The name of this column
     */
    public function __construct ( \h2o\MetaDB\Table $table, $name )
    {
        $name = trim( trim( \h2o\strval($name) ), "`" );

        if ( \h2o\isEmpty($name) )
            throw new \h2o\Exception\Argument( 0, "Column Name", "Must not be empty" );

        $this->table = $table;
        $this->name = $name;

        $this->table->addColumn( $this );
    }

    /**
     * Returns the Table this column belongs to
     *
     * @return \h2o\MetaDB\Table
     */
    public function getTable ()
    {
        return $this->table;
    }

    /**
     * Returns the Name of this column
     *
     * @return String
     */
    public function getName ()
    {
        return $this->name;
    }

}

?>