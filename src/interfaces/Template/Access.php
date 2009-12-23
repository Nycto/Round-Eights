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
 * @package Template
 */

namespace r8\iface\Template;

/**
 * Interface for templates that provide an interface for injecting data into
 * the template
 */
interface Access extends \r8\iface\Template
{

    /**
     * Set a variable value
     *
     * @param String $label The name of this value
     * @param mixed $value The value being registered
     * @return \r8\Template\Access Returns a self reference
     */
    public function set ( $label, $value );

    /**
     * Removes a variable
     *
     * @param String $label The name of the value being removed
     * @return \r8\Template\Access Returns a self reference
     */
    public function remove ( $label );

    /**
     * Returns whether a variable has been set
     *
     * @param String $label The name of the value being tested
     * @return Boolean
     */
    public function exists ( $label );

    /**
     * Returns the value of a variable
     *
     * @param String The name of the variable to return
     * @return mixed The value of the given variable
     */
    public function get ( $label );

}

?>