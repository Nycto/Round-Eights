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
 * Uses the value of a sub-field from each value as the key
 */
class KeyPluck extends \IteratorIterator
{

    /**
     * The name of the field to pluck from the wrapped iterator
     *
     * @var String
     */
    private $field;

    /**
     * Constructor...
     *
     * @param String $field The name of the field to pluck from the wrapped iterator
     * @param \Traversable $iterator The iterator being wrapped
     */
    public function __construct ( $field, \Traversable $iterator )
    {
        parent::__construct($iterator);
        $this->field = (string) $field;
    }

    /**
     * Returns the current value of the iterator
     *
     * @return mixed
     */
    public function key ()
    {
        $value = $this->current();

        if ( is_array($value) && isset($value[$this->field]) )
            return $value[ $this->field ];
        else if ( is_object($value) && property_exists($value, $this->field) )
            return $value->{ $this->field };
        else
            return NULL;
    }

}

?>