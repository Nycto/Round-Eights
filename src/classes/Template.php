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

    /**
     * Returns the value of a variable
     *
     * @param String The name of the variable to return
     * @return mixed The value of the given variable
     */
    public function get ( $label )
    {
        $label = self::normalizeLabel($label);

        if ( !array_key_exists( $label, $this->variables ) )
            return NULL;

        return $this->variables[ $label ];
    }

    /**
     * Adds a value only if it hasn't been set
     *
     * @param String $label The name of this value
     * @param mixed $value The value being registered
     * @return Object Returns a self reference
     */
    public function add ( $label, $value )
    {
        $label = self::normalizeLabel($label);

        if ( !array_key_exists( $label, $this->variables ) )
            $this->variables[ $label ] = $value;

        return $this;
    }

    /**
     * Completely clears all the variables
     *
     * @return Object Returns a self reference
     */
    public function clear ()
    {
        $this->variables = array();
        return $this;
    }

    /**
     * Appends a value to an existing label
     *
     * If the label doesn't already exist, it will be added with the given value.
     * If the existing value isn't a string, it will be converted to one
     *
     * @param String $label The name of value
     * @param mixed $value The value being appended
     * @return Object Returns a self reference
     */
    public function append ( $label, $value )
    {
        $label = self::normalizeLabel($label);

        if ( array_key_exists( $label, $this->variables ) ) {
            $this->variables[ $label ] =
                \cPHP\strval( $this->variables[ $label ] )
                . \cPHP\strval( $value );
        }
        else {
            $this->variables[ $label ] = $value;
        }

        return $this;
    }

    /**
     * Imports a list of values
     *
     * If given a Template, the values will be pulled out of it. If given an array
     * or a traversable object, it will pull out all the key/value pairs. If given
     * any other kind of object, the public properties will be imported.
     *
     * @param mixed $values The values to import. This can be another Template,
     *      an array, a traversable object.
     * @return Object Returns a self reference
     */
    public function import ( $values )
    {
        // Allow other templates to be directly imported
        if ( $values instanceof self )
            $values = $values->getValues();

        // pull any properties from non-traversable objects
        else if ( is_object($values) && !($values instanceof \Traversable) )
            $values = get_object_vars( $values );

        else if ( !\cPHP\Ary::is($values) )
            throw new \cPHP\Exception\Argument(0, "Import Values", "Value can not be imported");

        foreach ( $values AS $label => $value ) {
            $this->set( $label, $value );
        }

        return $this;
    }

    /**
     * Renders this template and outputs it to the client
     *
     * @return Object Returns a self reference
     */
    abstract public function display ();

    /**
     * Renders the template and returns it as a string
     *
     * @return String Returns the rendered template as a string
     */
    public function render ()
    {
        ob_start();
        $this->display();
        return ob_get_clean();
    }

    /**
     * Renders the template and returns it as a string
     *
     * @return String
     */
    public function __toString ()
    {
        return $this->render();
    }

    /**
     * Allows you access the value of a variable as a class property
     *
     * @param String $label The value being fetched
     * @return mixed Returns the value of the given variable
     */
    public function __get ( $label )
    {
        return $this->get( $label );
    }

    /**
     * Allows you to set the value of a variable via a class property
     *
     * @param String $label The value being set
     * @param mixed $value The new value
     */
    public function __set ( $label, $value )
    {
        $this->set( $label, $value );
    }

    /**
     * Allows you to check if a value is set via a class property
     *
     * @param String $label The value being tested
     * @return Boolean Returns whether the value is set
     */
    public function __isset ( $label )
    {
        return $this->exists( $label );
    }

    /**
     * Allows you to unset a value via a class property
     *
     * @param String $label The value being unset
     */
    public function __unset ( $label )
    {
        $this->remove( $label );
    }

}

?>