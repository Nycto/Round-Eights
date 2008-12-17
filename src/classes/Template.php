<?php
/**
 * Core Template Class
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
 * @package FileFinder
 */

namespace cPHP;

/**
 * The base Template class
 */
abstract class Template
{

    /**
     * The list of variables
     */
    protected $variables = Array();

    /**
     * Normalizes a variable name and checks whether it is properly formatted
     *
     * @throws cPHP\Exception\Argument Thrown if the label is invalid
     * @param String $label The variable name being filtered
     * @return String
     */
    static public function normalizeLabel ( $label )
    {
        $label = \cPHP\Filter::Variable()->filter( $label );

        if ( !\cPHP\Validator::Variable()->isValid( $label ) )
            throw new \cPHP\Exception\Argument(0, "Label", "Must be a valid PHP variable name");

        return $label;
    }

    /**
     * Returns the list of variables registered in this instance
     *
     * @return Object Returs a cPHP\Ary instance
     */
    public function getValues ()
    {
        return new \cPHP\Ary( $this->variables );
    }

    /**
     * Set a variable value
     *
     * @param String $label The name of this value
     * @param mixed $value The value being registered
     * @return Object Returns a self reference
     */
    public function set ( $label, $value )
    {
        $this->variables[ self::normalizeLabel($label) ] = $value;
        return $this;
    }

    /**
     * Removes a variable
     *
     * @param String $label The name of the value being removed
     * @return Object Returns a self reference
     */
    public function remove ( $label )
    {
        unset( $this->variables[ self::normalizeLabel($label) ] );
        return $this;
    }

    /**
     * Returns whether a variable has been set
     *
     * @param String $label The name of the value being tested
     * @return Boolean
     */
    public function exists ( $label )
    {
        return array_key_exists(
                self::normalizeLabel($label),
                $this->variables
            );
    }

}

?>