<?php
/**
 * HTML Form Helper
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
 * @package Forms
 */

namespace cPHP;

/**
 * Collects a list of form fields and allows them to be manipulated as a group
 */
class Form implements \Countable
{

    /**
     * Common submit methods
     */
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';

    /**
     * Common form encoding types
     */
    const ENCODING_URLENCODED = 'application/x-www-form-urlencoded';
    const ENCODING_MULTIPART = 'multipart/form-data';

    /**
     * The URL this form will be submitted to
     *
     * The default value for this is the current URL, if there is one
     *
     * @var String
     */
    private $action;

    /**
     * The submit method
     *
     * This defaults to 'POST'
     *
     * @var String
     */
    private $method = self::METHOD_POST;

    /**
     * The form encoding
     *
     * @var Integer
     */
    private $encoding = self::ENCODING_URLENCODED;

    /**
     * The list of form fields
     *
     * @var array
     */
    private $fields = array();

    /**
     * Constructor... Sets the initial state of the instance
     */
    public function __construct ()
    {
        // Set the default action URI to the current page
        $this->action = \cPHP\Env::Request()->url;
    }

    /**
     * Returns the action URL this form will be submitted to
     *
     * @return string;
     */
    public function getAction ()
    {
        return $this->action;
    }

    /**
     * Sets the URI this form submits to
     *
     * @param String $url The action of this form
     * @return Object Returns a self reference
     */
    public function setAction ( $url )
    {
        $url = \cPHP\Filter::URL()->filter( $url );

        if ( !\cPHP\Validator::URL( \cPHP\Validator\URL::ALLOW_RELATIVE )->isValid( $url ) )
            throw new \cPHP\Exception\Argument( 0, "Form Action", "Must be a valid URL" );

        $this->action = $url;

        return $this;
    }

    /**
     * Returns the method that this form will submit using
     *
     * @return String
     */
    public function getMethod ()
    {
        return $this->method;
    }

    /**
     * Sets the method this for should submit using
     *
     * @param String $method
     * @return Object Returns a self reference
     */
    public function setMethod ( $method )
    {
        $method = \cPHP\str\stripW( $method );

        if ( \cPHP\isEmpty($method) )
            throw new \cPHP\Exception\Argument( 0, "Submit Method", "Must not be empty" );

        $this->method = $method;

        return $this;
    }

    /**
     * Returns the encoding that this form will submit using
     *
     * @return String
     */
    public function getEncoding ()
    {
        return $this->encoding;
    }

    /**
     * Sets the encoding this for should use
     *
     * @param String $encoding
     * @return Object Returns a self reference
     */
    public function setEncoding ( $encoding )
    {
        $encoding = trim( \cPHP\strval( $encoding ) );

        if ( \cPHP\isEmpty($encoding) )
            throw new \cPHP\Exception\Argument( 0, "Form Encoding", "Must not be empty" );

        $this->encoding = $encoding;

        return $this;
    }

    /**
     * Returns the list of fields registered in this form
     *
     * @return Object Returns a \cPHP\Ary object
     */
    public function getFields ()
    {
        return $this->fields;
    }

    /**
     * Adds a field to this instance
     *
     * @param Object $field The field being added
     * @return Object Returns a self reference
     */
    public function addField ( \cPHP\iface\Form\Field $field )
    {
        // ensure there aren't any duplicates
        if ( !\cPHP\ary\contains($this->fields, $field, TRUE) )
            $this->fields[] = $field;

        return $this;
    }

    /**
     * Removes all the fields from this instance
     *
     * @return Object Returns a self reference
     */
    public function clearFields ()
    {
        $this->fields = array();
        return $this;
    }

    /**
     * Returns the number of fields in this instance
     *
     * @return Integer
     */
    public function count ()
    {
        return count( $this->fields );
    }

    /**
     * Returns the first field with the given name
     *
     * @param String $name The name of the field to return
     * @return Boolean Returns the requested field, or NULL if it can't be found
     */
    public function find ( $name )
    {
        $name = \cPHP\Filter::Variable()->filter( $name );

        if ( !\cPHP\Validator::Variable()->isValid( $name ) )
            throw new \cPHP\Exception\Argument( 0, "Field Name", "Must be a valid PHP variable name" );

        foreach ( $this->fields AS $field ) {
            if ( $field->getName() == $name )
                return $field;
        }

        return null;
    }

    /**
     * Determines whether a traversable input contains any fields in this form
     *
     * @param mixed $source An array or traversable object
     * @return Boolean
     */
    public function anyIn ( array $source )
    {
        foreach ( $this->fields AS $field ) {

            if ( array_key_exists( $field->getName(), $source ) )
                return TRUE;

        }

        return FALSE;
    }

    /**
     * Takes an associative array of values and fills in the form field values where
     * the key of the input matches the name of the field
     *
     * If the source does not have a value for a specific field, this will set
     * the field value to null.
     *
     * @param array $source
     * @return \cPHP\Form Returns a self reference
     */
    public function fill ( array $source )
    {
        foreach ( $this->fields AS $field ) {

            $name = $field->getName();

            if ( array_key_exists( $name, $source ) )
                $field->setValue( $source[$name] );
            else
                $field->setValue( null );

        }

        return $this;
    }

    /**
     * Validates each field and returns whether the form is valid
     *
     * @return Boolean
     */
    public function isValid ()
    {
        foreach ( $this->fields AS $field ) {
            if ( !$field->isValid() )
                return FALSE;
        }
        return TRUE;
    }

    /**
     * Returns a \cPHP\Tag object that represents this instance
     *
     * @return Object A \cPHP\Tag object
     */
    public function getTag()
    {
        return new \cPHP\Tag(
                'form',
                null,
                array(
                        "method" => $this->getMethod(),
                        "encoding" => $this->getEncoding(),
                        "action" => $this->getAction()
                    )
            );
    }

    /**
     * Converts this field to an HTML string
     *
     * This will only return a string representation of the opening tag
     *
     * @return String
     */
    public function __toString()
    {
        return $this->getTag()->getOpenTag();
    }

    /**
     * Returns all the hidden fields registered in this instance
     *
     * @return Object Returns a \cPHP\Ary object or hidden field objects
     */
    public function getHidden ()
    {
        return array_filter(
                $this->fields,
                function ( $field ) {
                    return ($field instanceof \cPHP\Form\Field\Hidden);
                }
            );
    }

    /**
     * Returns all the hidden fields registered in this instance
     *
     * @return String Returns a string of HTML
     */
    public function getHiddenHTML ()
    {
        return implode(
                "",
                \cPHP\ary\invoke($this->getHidden(), "__toString")
            );
    }

}

?>