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

namespace r8\Query\Where;

/**
 * Compares two atoms
 */
abstract class Compare implements \r8\iface\Query\Where
{

    /**
     * The value on the left of the operator
     *
     * @var \r8\iface\Query\Atom
     */
    private $left;

    /**
     * The value to right of the operator
     *
     * @var \r8\iface\Query\Atom
     */
    private $right;

    /**
     * Constructor...
     *
     * @param \r8\iface\Query\Atom $left The value on the left of the operator
     * @param \r8\iface\Query\Atom $right The value on the right of the operator
     */
    public function __construct ( \r8\iface\Query\Atom $left, \r8\iface\Query\Atom $right )
    {
        $this->left = $left;
        $this->right = $right;
    }

    /**
     * Returns the value to the left of the operator
     *
     * @return \r8\iface\Query\Atom
     */
    public function getLeft ()
    {
        return $this->left;
    }

    /**
     * Returns the value to the right of the operator
     *
     * @return \r8\iface\Query\Atom
     */
    public function getRight ()
    {
        return $this->right;
    }

    /**
     * Returns the precedence level of this clause
     *
     * @return Integer
     */
    public function getPrecedence ()
    {
        return 100;
    }

}

?>