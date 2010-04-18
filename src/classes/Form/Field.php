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

namespace r8\Form;

/**
 * The base class for HTML form fields
 */
abstract class Field implements \r8\iface\Form\Field
{

    /**
     * The name of this form field
     *
     * @var String
     */
    private $name;

    /**
     * The label that describes this input field
     *
     * @var String
     */
    private $label;

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
     * @var \r8\iface\Filter
     */
    private $filter;

    /**
     * The filter to apply to the data before outputing it to the client
     *
     * This allows you to do things like obfuscate credit cards, SSNs, or set
     * a value that a field should always be equal to.
     *
     * @var \r8\iface\Filter
     */
    private $outFilter;

    /**
     * The validator for this field
     *
     * @var \r8\iface\Validator
     */
    private $validator;

    /**
     * Constructor...
     *
     * @param String $name The name of this form field
     * @param String|NULL $label The label that describes this input field
     */
    public function __construct( $name, $label = NULL )
    {
        $this->setName( $name );
        $this->setLabel( $label );
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
     * @return \r8\Form\Field Returns a self reference
     */
    public function setName( $name )
    {
        $name = r8(new \r8\Filter\Variable)->filter( $name );

        if ( !r8(new \r8\Validator\Variable)->isValid( $name ) )
            throw new \r8\Exception\Argument( 0, "Field Name", "Must be a valid PHP variable name" );

        $this->name = $name;

        return $this;
    }

    /**
     * Sets the label that describes this form field
     *
     * @param String $label
     * @return \r8\Form\Field Returns a self reference
     */
    public function setLabel ( $label )
    {
        $this->label = \r8\isEmpty( (string) $label ) ? NULL : (string) $label;
        return $this;
    }

    /**
     * Returns the label that describes this input
     *
     * @return String
     */
    public function getLabel ()
    {
        return $this->label;
    }

    /**
     * Returns the filter loaded in to this instance
     *
     * If no filter has been explicitly set, this will create a new chain
     * filter, save it to this instance and return a reference to it
     *
     * @return \r8\iface\Filter
     */
    public function getFilter ()
    {
        if ( !($this->filter instanceof \r8\iface\Filter) )
            $this->filter = new \r8\Filter\Chain;

        return $this->filter;
    }

    /**
     * Sets the filter for this instance
     *
     * @param \r8\iface\Filter An object that implements the \r8\iface\Filter interface
     * @return \r8\Form\Field Returns a self reference
     */
    public function setFilter( \r8\iface\Filter $filter )
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * Returns the filter loaded in to this instance
     *
     * By default, this is set to use a Chain filter
     *
     * @return \r8\iface\Filter
     */
    public function getOutputFilter ()
    {
        if ( !($this->outFilter instanceof \r8\iface\Filter) )
            $this->outFilter = new \r8\Filter\Chain;

        return $this->outFilter;
    }

    /**
     * Sets the output filter
     *
     * @param \r8\iface\Filter
     * @return \r8\Form\Field Returns a self reference
     */
    public function setOutputFilter ( \r8\iface\Filter $filter )
    {
        $this->outFilter = $filter;
        return $this;
    }

    /**
     * Returns the validator loaded in to this instance
     *
     * If no validator has been explicitly set, this will create a new "Any"
     * validator, save it to this instance and return a reference to it
     *
     * @return \r8\iface\Validator
     */
    public function getValidator ()
    {
        if ( !($this->validator instanceof \r8\iface\Validator) )
            $this->validator = new \r8\Validator\Any;

        return $this->validator;
    }

    /**
     * Sets the validator for this instance
     *
     * @param \r8\iface\Validator A validator object
     * @return \r8\Form\Field Returns a self reference
     */
    public function setValidator( \r8\iface\Validator $validator )
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
     * @param \r8\iface\Validator $validator The validator to add to this instance
     * @return \r8\Form\Field Returns a self reference
     */
    public function andValidator ( \r8\iface\Validator $validator )
    {
        if ( $this->validator instanceof \r8\Validator\All ) {
            $this->validator->add( $validator );
        }
        else {
            $this->validator = new \r8\Validator\All(
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
     * objects or arrays using the \r8\reduce function
     *
     * @param mixed $value The value of this field
     * @return \r8\Form\Field Returns a self reference
     */
    public function setValue ( $value )
    {
        $this->value = \r8\reduce( $value );
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
        if ( $this->filter instanceof \r8\iface\Filter )
            return $this->filter->filter( $this->getRawValue() );
        else
            return $this->getRawValue();
    }

    /**
     * Applies the output filter and returns the resultting value
     *
     * @return mixed The filtered value
     */
    public function getForOutput ()
    {
        // Only apply the filter if there is one
        if ( $this->outFilter instanceof \r8\iface\Filter )
            return $this->outFilter->filter( $this->getValue() );
        else
            return $this->getValue();
    }

    /**
     * Applies the validator to the value in this instance and returns an
     * instance of Validator Results.
     *
     * This will apply the validator to the filtered value
     *
     * @return \r8\Validator\Results
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
     * Returns a \r8\HTML\Tag object that represents this instance
     *
     * @return \r8\HTML\Tag
     */
    public function getTag()
    {
        return new \r8\HTML\Tag(
            'input',
            null,
            array(
                "value" => $this->getForOutput(),
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