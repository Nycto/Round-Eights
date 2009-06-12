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
 * @package Selector
 */

namespace cPHP\Selector\Where;

/**
 * Compares the value in a column to a given value
 */
abstract class Compare implements \cPHP\iface\Selector\Where
{

    /**
     * The column being compared
     *
     * @var \cPHP\iface\Selector\Column
     */
    private $column;

    /**
     * The value to compare the column to
     *
     * @var mixed
     */
    private $value;

    /**
     * Constructor...
     *
     * @param \cPHP\iface\Selector\Column $column The column being compared
     * @param mixed $value The value to compare the column to
     */
    public function __construct ( \cPHP\iface\Selector\Column $column, $value )
    {
        $this->column = $column;
        $this->value = $value;
    }

    /**
     * Returns the Column being compared
     *
     * @return \cPHP\iface\Selector\Column
     */
    public function getColumn ()
    {
        return $this->column;
    }

    /**
     * Returns the Value the column will be compared to
     *
     * @return mixed
     */
    public function getValue ()
    {
        return $this->value;
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