<?php
/**

 * @license Artistic License 2.0
 *
 * This file is part of RaindropPHP.
 *
 * RaindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * RaindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with RaindropPHP. If not, see <http://www.RaindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <James@RaindropPHP.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Validators
 */

namespace h2o\Validator;

/**
 * Given a \h2o\Form\Multi object, this determines whether the given value
 * is a valid selection
 */
class MultiField extends \h2o\Validator
{

    /**
     * The field that is being validated
     *
     * @var \h2o\Form\Multi
     */
    protected $field;

    /**
     * Constructor...
     *
     * @param \h2o\Form\Multi $field The field to compare the value against
     */
    public function __construct ( \h2o\Form\Multi $field )
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
     * @return String|NULL Any errors encountered
     */
    protected function process ( $value )
    {
        if ( !\h2o\isBasic($value) || !array_key_exists( $value, $this->field->getOptions() ) )
            return "Value is not a valid selection";
    }

}

?>