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
 * Compares two atoms
 */
abstract class Compare implements \cPHP\iface\Query\Where
{

    /**
     * The value on the left of the operator
     *
     * @var \cPHP\iface\Query\Atom
     */
    private $left;

    /**
     * The value to right of the operator
     *
     * @var \cPHP\iface\Query\Atom
     */
    private $right;

    /**
     * Constructor...
     *
     * @param \cPHP\iface\Query\Atom $left The value on the left of the operator
     * @param \cPHP\iface\Query\Atom $right The value on the right of the operator
     */
    public function __construct ( \cPHP\iface\Query\Atom $left, \cPHP\iface\Query\Atom $right )
    {
        $this->left = $left;
        $this->right = $right;
    }

    /**
     * Returns the value to the left of the operator
     *
     * @return \cPHP\iface\Query\Atom
     */
    public function getLeft ()
    {
        return $this->left;
    }

    /**
     * Returns the value to the right of the operator
     *
     * @return \cPHP\iface\Query\Atom
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