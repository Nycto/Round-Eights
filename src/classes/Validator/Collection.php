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
 * @package Validators
 */

namespace r8\Validator;

/**
 * An interface for grouping a set of validators in to one object
 */
abstract class Collection extends \r8\Validator
{

    /**
     * The list of validators contained in this instance
     *
     * @var array
     */
    protected $validators = array();

    /**
     * Constructor
     *
     * Allows you to add validators on construction
     *
     * @param \r8\iface\Validator $validators...
     */
    public function __construct ()
    {
        if ( func_num_args() > 0 ) {
            $args = func_get_args();
            $this->addMany( $args );
        }
    }

    /**
     * Adds a validator to this instance
     *
     * @todo This really shouldn't accept a string
     * @param Object The validator to addd to this instance
     * @return Object Returns a self reference
     */
    public function add ( $validator )
    {
        if ( is_object($validator) ) {

            if ( !$validator instanceof \r8\iface\Validator )
                throw new \r8\Exception\Argument( 0, "Validator", "Must be an instance of \r8\iface\Validator" );

        }
        else {
            $validator = \r8\strval( $validator );

            if ( !is_subclass_of($validator, '\r8\iface\Validator') ) {

                $refl = new \ReflectionClass( $validator );
                if ( !$refl->implementsInterface( '\r8\iface\Validator' ) )
                    throw new \r8\Exception\Argument( 0, "Validator", "Must be an instance of \r8\iface\Validator" );

            }

            $validator = new $validator;
        }

        $this->validators[] = $validator;
        return $this;
    }

    /**
     * Returns the list of validators contained in this instance
     *
     * @return Array
     */
    public function getValidators ()
    {
        return $this->validators;
    }

    /**
     * Adds many validators to this instance at once
     *
     * @param mixed $validators... Any arguments passed will be flattened down and filtered
     * @return Object Returns a self reference
     */
    public function addMany ( $validators )
    {
        $validators = func_get_args();
        $validators = \r8\ary\flatten( $validators );
        $validators = array_filter(
                $validators,
                function($validator) {
                    return $validator instanceof \r8\iface\Validator;
                }
            );
        array_walk( $validators, array($this, "add") );

        return $this;
    }

}

?>