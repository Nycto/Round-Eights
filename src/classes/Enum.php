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
 * @package Enum
 */

namespace r8;

/**
 * A helper class for creating enumerable objects
 */
abstract class Enum
{

    /**
     * This is a cache of classes and their valid values
     *
     * This array exists to reduce the amount of reflection that needs be done
     *
     * @var Array
     */
    static private $cache = array();

    /**
     * The label of this enum instance
     *
     * @var String
     */
    private $label;

    /**
     * The value of this enum instance
     *
     * @var Mixed
     */
    private $value;

    /**
     * An internal method for deriving the valid values of an enumerable
     *
     * @return Array
     */
    final static public function getValues ()
    {
        $class = get_called_class();

        if ( isset(self::$cache[$class]) )
            return self::$cache[$class];

        $refl = new \ReflectionClass( $class );

        if ( !$refl->isInstantiable() )
        {
            $err = new \r8\Exception\Interaction("Enum class is not instantiable");
            $err->addData("Class", $class);
            throw $err;
        }

        $consts = $refl->getConstants();

        if ( $consts != array_unique($consts) )
        {
            $err = new \r8\Exception\Interaction("Enum values must be unique");
            $err->addData("Class", $class);
            throw $err;
        }

        $reserved = array();
        foreach ( $consts AS $label => $value )
        {
            $label = strtolower( $label );
            $value = strtolower( $value );

            if ( in_array($label, $reserved) || in_array($value, $reserved) )
            {
                $err = new \r8\Exception\Interaction("Enum contains a conflicting label and value");
                $err->addData("Class", $class);
                $err->addData("Label", $label);
                $err->addData("Value", $value);
                throw $err;
            }

            $reserved[] = $label;
            $reserved[] = $value;
        }

        self::$cache[$class] = $consts;

        return self::$cache[$class];
    }

    /**
     * Allows enumerable instantiation via static method calls
     *
     * @param String $method The Enumerable to create
     * @param Array $args Any arguments passed to this instance
     * @return \r8\Enum Returns an Enumerable object
     */
    static public function __callStatic ( $method, array $args )
    {
        return new static( $method );
    }

    /**
     * Constructor...
     *
     * @param Mixed $input The Label or Value to derive this enumerable from
     */
    public function __construct ( $input )
    {
        list( $this->label, $this->value ) = $this->find( $input );
    }

    /**
     * Using a label or a value as it's input, this will select the label/value
     * pair that matches it
     *
     * @return Array
     */
    private function find ( $input )
    {
        foreach ( static::getValues() AS $label => $value )
        {
            if ( strcasecmp($label, $input) == 0 || strcasecmp($value, $input) == 0 )
                return array( $label, $value );
        }

        throw new \r8\Exception\Argument( 0, "input", "Invalid Enum input value" );
    }

    /**
     * Returns the label of this enumerable instance
     *
     * @return String
     */
    public function getLabel ()
    {
        return $this->label;
    }

    /**
     * Returns the Value of this enumerable instance
     *
     * @return mixed
     */
    public function getValue ()
    {
        return $this->value;
    }

    /**
     * Returns the value of this enumerable as a string
     *
     * @return String
     */
    public function __toString ()
    {
        return (string) $this->value;
    }

}

?>