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
 * @package MetaDB
 */

namespace cPHP\MetaDB;

/**
 * The base class for a standard column
 */
abstract class Column implements \cPHP\iface\MetaDB\Column
{

    /**
     * The table this column belongs to
     *
     * @var \cPHP\MetaDB\Table
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
     * @param \cPHP\MetaDB\Table $table The table this column belongs to
     * @param String $name The name of this column
     */
    public function __construct ( \cPHP\MetaDB\Table $table, $name )
    {
        $name = trim( trim( \cPHP\strval($name) ), "`" );

        if ( \cPHP\isEmpty($name) )
            throw new \cPHP\Exception\Argument( 0, "Column Name", "Must not be empty" );

        $this->table = $table;
        $this->name = $name;

        $this->table->addColumn( $this );
    }

    /**
     * Returns the Table this column belongs to
     *
     * @return \cPHP\MetaDB\Table
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
     * Quotes a value for use in a SQL query
     *
     * @param mixed $value The value to quote
     * @return String
     */
    public function quote ( $value )
    {
        return $this->table->quote( $value );
    }

}

?>