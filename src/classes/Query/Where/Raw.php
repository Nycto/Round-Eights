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
 * @package Query
 */

namespace h2o\Query\Where;

/**
 * Presents a raw string as a Where clause
 */
class Raw implements \h2o\iface\Query\Where
{

    /**
     * The value being presented as a WHERE clause
     *
     * @var String
     */
    private $value;

    /**
     * Constructor...
     *
     * @param String $value The value being presented as a WHERE clause
     */
    public function __construct ( $value )
    {
        $this->value = trim( \h2o\strval( $value ) );
    }

    /**
     * Returns the value being presented as a WHERE clause
     *
     * @return String
     */
    public function getValue ()
    {
        return $this->value;
    }

    /**
     * Returns the precedence level of this clause
     *
     * Because the contents of this are in a denormalized format, the
     * precedence for them is very low
     *
     * @return Integer
     */
    public function getPrecedence ()
    {
        return 0;
    }

    /**
     * Returns the SQL Where expression represented by this object
     *
     * @param \h2o\iface\DB\Link $link The database connection this WHERE clause
     * 		is being run against. This is being passed in for escaping purposes
     * @return String
     */
    public function toWhereSQL( \h2o\iface\DB\Link $link )
    {
        return $this->value;
    }

}

?>