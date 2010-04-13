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
 * @package Forms
 */

namespace r8\iface\Form;

/**
 * Form field definition
 *
 * Implementing this interface defines an object as a form field, and it should
 * be usable by any other objects that interact with fields
 */
interface Field
{

    /**
     * Returns the name of this field
     *
     * @return String
     */
    public function getName ();

    /**
     * Returns the value of this field
     *
     * @return mixed The value of this field
     */
    public function getValue ();

    /**
     * Sets the value for this field
     *
     * @param mixed $value The value of this field
     * @return Object Returns a self reference
     */
    public function setValue ( $value );

    /**
     * Applies the validator to the value in this instance and returns an
     * instance of Validator Results.
     *
     * @return object An instance of validator results
     */
    public function validate ();

    /**
     * Runs the validation and returns whether the value passes or not
     *
     * @return Boolean
     */
    public function isValid ();

    /**
     * Provides an interface for visiting this field
     *
     * @param \r8\iface\Form\Visitor $visitor The visitor object to call
     * @return NULL
     */
    public function visit ( \r8\iface\Form\Visitor $visitor );

}

?>