<?php
/**
 * Validation class
 *
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
 * @package Validators
 */

namespace cPHP\Validator;

/**
 * Given a \cPHP\Form\Multi object, this determines whether the given value
 * is a valid selection
 */
class MultiField extends \cPHP\Validator
{

    /**
     * The field that is being validated
     */
    protected $field;

    /**
     * Constructor...
     *
     * @param Object $field The \cPHP\Form\Multi field to compare the value to
     */
    public function __construct ( \cPHP\Form\Multi $field )
    {
        $this->field = $field;
    }

    /**
     * Validates a URL
     *
     * This will always fail for anything that isn't a basic value. That is, boolean,
     * null, integers, floats, or strings.
     *
     * @param mixed $value The value to validate
     * @return String Any errors encountered
     */
    protected function process ( $value )
    {
        if ( !\cPHP\isBasic($value) || !array_key_exists( $value, $this->field->getOptions() ) )
            return "Value is not a valid selection";
    }

}

?>