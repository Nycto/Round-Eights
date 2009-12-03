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
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Forms
 */

namespace r8\Form;

/**
 * The core class for HTML form fields that have multiple, distinct, options
 */
abstract class Multi extends \r8\Form\Field
{

    /**
     * The list of options
     *
     * This is an associative array where they key is the option value and
     * the element value is the option label
     *
     * @var Array
     */
    private $options = array();

    /**
     * Constructor...
     *
     * Loads in the default validator
     *
     * @param String The name of this form field
     */
    public function __construct( $name )
    {
        parent::__construct($name);

        $this->setValidator(
                new \r8\Validator\MultiField( $this )
            );
    }

    /**
     * Returns the list of registered options
     *
     * @return Array
     */
    public function getOptions ()
    {
        return $this->options;
    }

    /**
     * Adds a new option on to this list
     *
     * @param mixed $value The raw value of this option. This will be reduced
     *      down to a basic value
     * @param String $label The visible label for this value
     * @return \r8\Form\Multi Returns a self reference
     */
    public function addOption ( $value, $label )
    {
        $value = \r8\indexVal($value);
        $label = \r8\strval( $label );

        $this->options[ $value ] = $label;

        return $this;
    }

    /**
     * Returns whether an option exists based on its value
     *
     * @param mixed $value The option value to test
     * @return Boolean
     */
    public function hasOption ( $value )
    {
        $value = \r8\indexVal($value);

        return array_key_exists( $value, $this->options );
    }

    /**
     * Removes an option from the list based on it's value
     *
     * @param mixed $value The option value to remove
     * @return \r8\Form\Multi Returns a self reference
     */
    public function removeOption ( $value )
    {
        $value = \r8\indexVal($value);

        if ( $this->hasOption( $value ) )
            unset($this->options[ $value ]);

        return $this;
    }

    /**
     * Returns the label for an option based on it's value
     *
     * @param mixed $value The option value to look up
     * @return String Returns the label for the given option
     */
    public function getOptionLabel ( $value )
    {
        $value = \r8\indexVal($value);

        if ( !$this->hasOption( $value ) )
            throw new \r8\Exception\Index($value, "Option Value", "Option does not exist in field");

        return $this->options[ $value ];
    }

    /**
     * Removes all the registered options from this instance
     *
     * @return \r8\Form\Multi Returns a self reference
     */
    public function clearOptions ()
    {
        $this->options = array();
        return $this;
    }

    /**
     * Imports a set of options from an array or traversable object
     *
     * @param array $source
     * @return \r8\Form\Multi Returns a self reference
     */
    public function importOptions ( array $source )
    {
        $source = \r8\ary\flatten( $source );

        foreach ( $source AS $key => $value ) {
            $this->addOption($key, $value);
        }

        return $this;
    }

}

?>