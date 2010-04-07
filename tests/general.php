<?php
/**
 * Unit Test File
 *
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
 * @package UnitTests
 */

require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/Extensions/OutputTestCase.php';

define("r8_SUPPRESS_HANDLERS", TRUE);
define("r8_TESTCONFIG", rtrim( __DIR__, "/" ) .'/config.php');

require_once rtrim( __DIR__, "/" ) ."/../src/RoundEights.php";

error_reporting( E_ALL | E_STRICT );

PHPUnit_Util_Filter::addFileToFilter(__FILE__, 'PHPUNIT');

/**
 * Asserts that the array produced by an iterator are exactly equal to a given value
 */
class PHPUnit_Framework_Constraint_Iterator extends PHPUnit_Framework_Constraint
{

    /**
     * The value the iterator should produce
     *
     * @var array
     */
    private $value;

    /**
     * This is a cache to keep from iterating over the same object multiple times
     *
     * @var array
     */
    private $cache = array();

    /**
     * Asserts that the given value produces the expected result when iterated over
     *
     * @return null
     */
    static public function assert ( array $expected, $actual )
    {
        PHPUnit_Framework_TestCase::assertThat(
                $actual,
                new self( $expected )
            );
    }

    /**
     * Converts an interator to an array while providing a maximum result cap
     *
     * @param Integer $max The maximum number of results
     * @param Traversable $iterator The iterator to convert
     * @param Boolean $recurse Whether to recursively reduce inner iterators
     * @return Array
     */
    static public function iteratorToArray ( $max, \Traversable $iterator, $recurse = FALSE )
    {
        $i = 0;

        $result = array();

        foreach ( $iterator AS $key => $value )
        {
            if ( $recurse && $value instanceof \Traversable )
                $value = self::iteratorToArray( $max - 1, $value, TRUE );

            $result[ $key ] = $value;

            $i++;
            if ( $i > $max )
                break;
        }

        return $result;
    }

    /**
     * Constructor...
     *
     * @param Array $value The value the iterator should produce
     */
    public function __construct( array $value )
    {
        $this->value = $value;
    }

    /**
     * Turns an iterator into an array while preventing too much iteration
     *
     * @return Array
     */
    public function toArray ( Traversable $iterator )
    {
        $hash = spl_object_hash( $iterator );

        // First check the cache
        if ( isset($this->cache[$hash]) )
            return $this->cache[$hash];

        $max = count( $this->value );

        // Give them a 50% bonus to make debugging easier
        $max = floor( $max * 1.50 );

        $this->cache[$hash] = self::iteratorToArray( $max, $iterator );

        return $this->cache[$hash];
    }

    /**
     * Evaluates the constraint for parameter $other. Returns TRUE if the
     * constraint is met, FALSE otherwise.
     *
     * @param mixed $other Value or object to evaluate.
     * @return bool
     */
    public function evaluate( $other )
    {
        if ( !($other instanceof Traversable) )
            return FALSE;

        return $this->toArray($other) === $this->value;
    }

    /**
     * @param   mixed   $other The value passed to evaluate() which failed the
     *                         constraint check.
     * @param   string  $description A string with extra description of what was
     *                               going on while the evaluation failed.
     * @param   boolean $not Flag to indicate negation.
     * @return String
     */
    protected function customFailureDescription($other, $description, $not)
    {
        if ( !($other instanceof Traversable) )
            return PHPUnit_Util_Type::toString($other) ." is an instance of Traversable";

        $other = $this->toArray($other);

        $diff = new PHPUnit_Framework_ComparisonFailure_Array(
            $this->value,
            $other
        );

        return "Iteration did not produce the expected result:\n"
            .$diff->toString();
    }

    /**
     * Returns a string representation of the constraint.
     *
     * @return string
     */
    public function toString()
    {
        return "produces". PHPUnit_Util_Type::toString($this->value) ."when iterated over";
    }

}

?>