<?php
/**
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
 * @package Forms
 */

namespace cPHP\Form;

/**
 * The base class for HTML form fields
 */
abstract class Field implements \cPHP\iface\Form\Field
{

    /**
     * The name of this form field
     *
     * @var String
     */
    private $name;

    /**
     * The current, raw value of this field
     *
     * The value stored here is unfiltered and unvalidated
     *
     * @var mixed
     */
    protected $value;

    /**
     * The filter to apply to any data that is fed in to this field
     *
     * @var \cPHP\iface\Filter
     */
    private $filter;

    /**
     * The validator for this field
     *
     * @var \cPHP\iface\Validator
     */
    private $validator;

    /**
     * Constructor...
     *
     * @param String $name The name of this form field
     */
    public function __construct( $name )
    {
        $this->setName( $name );
    }

    /**
     * Returns the name of this field
     *
     * @return String
     */
    public function getName ()
    {
        return $this->name;
    }

    /**
     * Sets the name of this field
     *
     * @param String $name The field name
     * @return \cPHP\Form\Field Returns a self reference
     */
    public function setName( $name )
    {
        $name = \cPHP\Filter::Variable()->filter( $name );

        if ( !\cPHP\Validator::Variable()->isValid( $name ) )
            throw new \cPHP\Exception\Argument( 0, "Field Name", "Must be a valid PHP variable name" );

        $this->name = $name;

        return $this;
    }

    /**
     * Returns the filter loaded in to this instance
     *
     * If no filter has been explicitly set, this will create a new chain
     * filter, save it to this instance and return a reference to it
     *
     * @return \cPHP\iface\Filter
     */
    public function getFilter ()
    {
        if ( !($this->filter instanceof \cPHP\iface\Filter) )
            $this->filter = new \cPHP\Filter\Chain;

        return $this->filter;
    }

    /**
     * Sets the filter for this instance
     *
     * @param \cPHP\iface\Filter An object that implements the \cPHP\iface\Filter interface
     * @return \cPHP\Form\Field Returns a self reference
     */
    public function setFilter( \cPHP\iface\Filter $filter )
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * Returns the validator loaded in to this instance
     *
     * If no validator has been explicitly set, this will create a new "Any"
     * validator, save it to this instance and return a reference to it
     *
     * @return \cPHP\iface\Validator
     */
    public function getValidator ()
    {
        if ( !($this->validator instanceof \cPHP\iface\Validator) )
            $this->validator = new \cPHP\Validator\Any;

        return $this->validator;
    }

    /**
     * Sets the validator for this instance
     *
     * @param \cPHP\iface\Validator A validator object
     * @return \cPHP\Form\Field Returns a self reference
     */
    public function setValidator( \cPHP\iface\Validator $validator )
    {
        $this->validator = $validator;
        return $this;
    }

    /**
     * Adds another validator to this instance
     *
     * This checks to see if the current validator is an "Collection\All" instance.
     * If it is, then it adds the given validator on to the list. If it isn't,
     * then it wraps the current validator and the validator in the instance in
     * an All validator and sets it to the validator for this instance.
     *
     * @param \cPHP\iface\Validator $validator The validator to add to this instance
     * @return \cPHP\Form\Field Returns a self reference
     */
    public function andValidator ( \cPHP\iface\Validator $validator )
    {
        if ( $this->validator instanceof \cPHP\Validator\All ) {
            $this->validator->add( $validator );
        }
        else {
            $this->validator = new \cPHP\Validator\All(
                    $this->validator,
                    $validator
                );
        }

        return $this;
    }

    /**
     * Returns the unfiltered, unvalidated value that is contained in this instance
     *
     * @return mixed The raw value of this field
     */
    public function getRawValue ()
    {
        return $this->value;
    }

    /**
     * Sets the value for this field
     *
     * This does not apply the filter when saving, however it will convert any
     * objects or arrays using the \cPHP\reduce function
     *
     * @param mixed $value The value of this field
     * @return \cPHP\Form\Field Returns a self reference
     */
    public function setValue ( $value )
    {
        $this->value = \cPHP\reduce( $value );
        return $this;
    }

    /**
     * Applies the filter and returns the resultting value
     *
     * @return mixed The filtered value
     */
    public function getValue ()
    {
        // Only apply the filter if there is one
        if ( $this->filter instanceof \cPHP\iface\Filter )
            return $this->filter->filter( $this->getRawValue() );
        else
            return $this->getRawValue();
    }

    /**
     * Applies the validator to the value in this instance and returns an
     * instance of Validator Results.
     *
     * This will apply the validator to the filtered value
     *
     * @result \cPHP\Validator\Results
     */
    public function validate ()
    {
        return $this->getValidator()->validate( $this->getValue() );
    }

    /**
     * Runs the validation and returns whether the value passes or not
     *
     * @return Boolean
     */
    public function isValid ()
    {
        return $this->validate()->isValid();
    }

    /**
     * Returns a \cPHP\Tag object that represents this instance
     *
     * @return \cPHP\Tag
     */
    public function getTag()
    {
        return new \cPHP\Tag(
                'input',
                null,
                array(
                        "value" => $this->getValue(),
                        "name" => $this->getName()
                    )
            );
    }

    /**
     * Converts this field to an HTML string
     *
     * @return String
     */
    public function __toString()
    {
        return $this->getTag()->__toString();
    }

}

?>