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
 * @package Iterator
 */

namespace r8\Iterator;

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
     * @var \r8\iface\Validator
     */
    private $validator;

    /**
     * Constructor...
     *
     * @param \Iterator $iterator The iterator being wrapped
     * @param \r8\iface\Validator $validator The validator to use when deciding whether an
     *    element should be included in the iteration
     */
    public function __construct ( \Iterator $iterator, \r8\iface\Validator $validator )
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