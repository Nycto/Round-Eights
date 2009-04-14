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
 * @package Iterator
 */

namespace cPHP\Iterator;

/**
 * An iterator that passes each value through a filter before returning it
 */
class Filter extends \IteratorIterator
{

    /**
     * The filter to apply to each iterator element
     *
     * @var \cPHP\iface\Filter
     */
    private $filter;

    /**
     * Constructor...
     *
     * @param \Traversable $iterator The iterator being wrapped
     * @param \cPHP\iface\Filter $filter The filter to apply to each iterator element
     */
    public function __construct ( \Traversable $iterator, \cPHP\iface\Filter $filter )
    {
        parent::__construct($iterator);
        $this->filter = $filter;
    }

    /**
     * Returns the current value of the iterator
     *
     * @return mixed
     */
    public function current ()
    {
        return $this->filter->filter( parent::current() );
    }

}

?>