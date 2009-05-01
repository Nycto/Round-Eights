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
 * An iterator that uses a validator to determine whether an element should
 * be included in the list
 */
class Validator extends \FilterIterator
{

    /**
     * The validator to use when deciding whether an element should be included
     * in the iteration
     *
     * @var \cPHP\iface\Validator
     */
    private $validator;

    /**
     * Constructor...
     *
     * @param \Iterator $iterator The iterator being wrapped
     * @param \cPHP\iface\Validator $validator The validator to use when deciding whether an
     *    element should be included in the iteration
     */
    public function __construct ( \Iterator $iterator, \cPHP\iface\Validator $validator )
    {
        parent::__construct($iterator);
        $this->validator = $validator;
    }

    /**
     * Returns whether the current element should be included in the iteration
     *
     * @return Boolean
     */
    public function accept ()
    {
        return $this->validator->isValid( $this->current() );
    }

}

?>