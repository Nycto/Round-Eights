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
 * @package MetaDB
 */

namespace r8\MetaDB;

/**
 * The base class for a standard column
 */
abstract class Column implements \r8\iface\MetaDB\Column
{

    /**
     * The table this column belongs to
     *
     * @var \r8\MetaDB\Table
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
     * @param \r8\MetaDB\Table $table The table this column belongs to
     * @param String $name The name of this column
     */
    public function __construct ( \r8\MetaDB\Table $table, $name )
    {
        $name = trim( trim( \r8\strval($name) ), "`" );

        if ( \r8\isEmpty($name) )
            throw new \r8\Exception\Argument( 0, "Column Name", "Must not be empty" );

        $this->table = $table;
        $this->name = $name;

        $this->table->addColumn( $this );
    }

    /**
     * Returns the Table this column belongs to
     *
     * @return \r8\MetaDB\Table
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

    /**
     * Returns the SQL string for this expression
     *
     * @param \r8\iface\DB\Link $link The database connection this WHERE clause
     * 		is being run against. This is being passed in for escaping purposes
     * @return String
     */
    public function toSelectSQL( \r8\iface\DB\Link $link )
    {
        return $this->table->toFromSQL( $link ) .".". $this->getName();
    }

}

?>