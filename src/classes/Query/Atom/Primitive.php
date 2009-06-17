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

namespace cPHP\Query\Atom;

/**
 * Represents a primitive value in a SQL query
 */
class Primitive implements \cPHP\iface\Query\Atom
{

    /**
     * The primitive value
     *
     * @var mixed
     */
    private $value;

    /**
     * Constructor...
     *
     * @param mixed $value The primitive value in this instance
     */
    public function __construct ( $value )
    {
        $this->value = $value;
    }

    /**
     * Returns the value in this instance
     *
     * @return mixed
     */
    public function getValue ()
    {
        return $this->value;
    }

    /**
     * Returns the SQL this atom represents
     *
     * @param \cPHP\iface\DB\Link $link The database connection this atom
     * 		is being created against. This is being passed in for escaping
     * 		purposes
     * @return String
     */
    public function toAtomSQL( \cPHP\iface\DB\Link $link )
    {
        return $link->quote( \cPHP\reduce( $this->value ) );
    }

}

?>