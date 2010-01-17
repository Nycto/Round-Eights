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

namespace r8\Query\Atom;

/**
 * Represents a primitive value in a SQL query
 */
class Primitive extends \r8\Query\Atom
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
     * @param \r8\iface\DB\Link $link The database connection this atom
     *      is being created against. This is being passed in for escaping
     *      purposes
     * @return String
     */
    public function toAtomSQL( \r8\iface\DB\Link $link )
    {
        return $link->quote( \r8\reduce( $this->value ) );
    }

}

?>