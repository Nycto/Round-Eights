<?php
/**
 * @license Artistic License 2.0
 *
 * This file is part of raindropPHP.
 *
 * raindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * raindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with raindropPHP. If not, see <http://www.raindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package validators
 */

namespace h2o\Validator;

/**
 * Contains the results of a validation
 */
class Result extends \h2o\Validator\ErrorList
{

    /**
     * The value that was validated
     *
     * @var mixed
     */
    protected $value;

    /**
     * Constructor
     *
     * @param mixed $value The value that was validated
     */
    public function __construct ( $value )
    {
        $this->value = $value;
    }

    /**
     * Returns the value that was validated
     *
     * @return mixed
     */
    public function getValue ()
    {
        return $this->value;
    }

    /**
     * Returns whether there aren't any errors in the list
     *
     * @return Boolean
     */
    public function isValid ()
    {
        return !$this->hasErrors();
    }

}

?>