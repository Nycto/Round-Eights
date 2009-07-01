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
 * @package Query
 */

namespace cPHP\Query\Where;

/**
 * Presents a raw string as a Where clause
 */
class Raw implements \cPHP\iface\Query\Where
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
        $this->value = trim( \cPHP\strval( $value ) );
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
     * @param \cPHP\iface\DB\Link $link The database connection this WHERE clause
     * 		is being run against. This is being passed in for escaping purposes
     * @return String
     */
    public function toWhereSQL( \cPHP\iface\DB\Link $link )
    {
        return $this->value;
    }

}

?>