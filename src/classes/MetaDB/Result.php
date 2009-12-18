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
 * @package MetaDB
 */

namespace r8\MetaDB;

/**
 * The result object for a MetaDB query
 */
class Result extends \r8\DB\Result\Read\Decorator
{

    /**
     * The builder to use for constructing each row
     *
     * @var \r8\iface\MetaDB\RowBuilder
     */
    private $builder;

    /**
     * Constructor...
     *
     * @param \r8\iface\DB\Result\Read $decorated The Read result being decorated
     * @param \r8\iface\MetaDB\RowBuilder $builder The builder to use for
     * 		constructing each row
     */
    public function __construct (
        \r8\iface\DB\Result\Read $decorated,
        \r8\iface\MetaDB\RowBuilder $builder
    ) {
        parent::__construct( $decorated );
        $this->builder = $builder;
    }

    /**
     * Returns the RowBuilder that will be used to construct each row
     *
     * @return \hwo\iface\MetaDB\RowBuilder
     */
    public function getRowBuilder ()
    {
        return $this->builder;
    }

    /**
     * Returns the value of the current row
     *
     * Iterator interface function
     *
     * @return mixed Returns the current Row, or FALSE if the iteration has
     *      reached the end of the row list
     */
    public function current ()
    {
        $current = $this->getDecorated()->current();

        if ( !is_array($current) )
            return FALSE;

        return $this->builder->fromArray( $current );
    }

}

?>