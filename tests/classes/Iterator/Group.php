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

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_Iterator_Group extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a test iterator filled with data already
     *
     * @return \r8\Iterator\Group
     */
    public function getTestIterator ( $field )
    {
        return new \r8\Iterator\Group(
            $field,
            new ArrayIterator( array(
                array( "num" => 5, "color" => "blue", "shape" => "square" ),
                array( "num" => 5, "color" => "red" ),
                array( "num" => 6, "color" => "red", "shape" => "square" ),
                array( "num" => 6, "color" => "red", "shape" => "triangle" ),
                array( "num" => 6, "color" => "blue" ),
            ) )
        );
    }

    /**
     * Creates a new stdClass and sets properties based on the values in an array
     *
     * @return stdClass
     */
    public function getTestObj ( array $data )
    {
        $obj = new stdClass;

        foreach ( $data AS $key => $value )
        {
            $obj->{$key} = $value;
        }

        return $obj;
    }

    /**
     * Creates a new ArrayAccess object and sets it up to return a set of values
     *
     * @return stdClass
     */
    public function getArrayAccess ( array $data )
    {
        $obj = $this->getMock('ArrayAccess');

        $obj->expects( $this->any() )
            ->method( "offsetExists" )
            ->will( $this->returnCallback( function ($offset) use ($data) {
                return isset($data[$offset]);
            } ) );

        $obj->expects( $this->any() )
            ->method( "offsetGet" )
            ->will( $this->returnCallback( function ($offset) use ($data) {
                return $data[$offset];
            } ) );

        return $obj;
    }

    public function testIterate ()
    {
        $result = PHPUnit_Framework_Constraint_Iterator::iteratorToArray(
            10,
            $this->getTestIterator( "num" )
        );

        $this->assertSame( 2, count($result) );

        $this->assertArrayHasKey( 5, $result );
        $this->assertArrayHasKey( 6, $result );

        $this->assertThat( $result[5], $this->isInstanceOf( 'Traversable' ) );
        $this->assertThat( $result[6], $this->isInstanceOf( 'Traversable' ) );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array(
                array( "num" => 5, "color" => "blue", "shape" => "square" ),
                array( "num" => 5, "color" => "red" ),
            ),
            $result[5]
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array(
                array( "num" => 6, "color" => "red", "shape" => "square" ),
                array( "num" => 6, "color" => "red", "shape" => "triangle" ),
                array( "num" => 6, "color" => "blue" )
            ),
            $result[6]
        );
    }

    public function testIterate_unsorted ()
    {
        $this->assertEquals(
            array(
                "blue" => array(
                    array( "num" => 5, "color" => "blue", "shape" => "square" ),
                ),
                "red" => array(
                    array( "num" => 5, "color" => "red" ),
                    array( "num" => 6, "color" => "red", "shape" => "square" ),
                    array( "num" => 6, "color" => "red", "shape" => "triangle" ),
                ),
                "blue" => array(
                    array( "num" => 6, "color" => "blue" )
                )
            ),
            PHPUnit_Framework_Constraint_Iterator::iteratorToArray(
                10, $this->getTestIterator( "color" ), TRUE
            )
        );
    }

    public function testIterate_gaps ()
    {
        $this->assertEquals(
            array(
                "square" => array(
                    array( "num" => 5, "color" => "blue", "shape" => "square" ),
                    array( "num" => 6, "color" => "red", "shape" => "square" ),
                ),
                "triangle" => array(
                    array( "num" => 6, "color" => "red", "shape" => "triangle" ),
                )
            ),
            PHPUnit_Framework_Constraint_Iterator::iteratorToArray(
                10, $this->getTestIterator( "shape" ), TRUE
            )
        );
    }

    public function testIterate_objects ()
    {
        $data = array(
            $this->getTestObj( array( "num" => 5, "color" => "blue" ) ),
            $this->getTestObj( array( "num" => 5, "color" => "red" ) ),
            $this->getTestObj( array( "color" => "red" ) ),
            $this->getTestObj( array( "num" => 6, "color" => "red" ) ),
            $this->getTestObj( array( "num" => NULL, "color" => "blue" ) )
        );

        $iter = new \r8\Iterator\Group( "num", new ArrayIterator( $data ) );

        $this->assertEquals(
            array(
                5 => array( $data[0], $data[1], ),
                6 => array( $data[3], ),
            ),
            PHPUnit_Framework_Constraint_Iterator::iteratorToArray(
                10, $iter, TRUE
            )
        );
    }

    public function testIterate_ArrayAccess ()
    {
        $data = array(
            $this->getArrayAccess( array( "num" => 5, "color" => "blue" ) ),
            $this->getArrayAccess( array( "num" => 5, "color" => "red" ) ),
            $this->getArrayAccess( array( "color" => "red" ) ),
            $this->getArrayAccess( array( "num" => 6, "color" => "red" ) ),
            $this->getArrayAccess( array( "num" => NULL, "color" => "blue" ) )
        );

        $iter = new \r8\Iterator\Group( "num", new ArrayIterator( $data ) );

        $this->assertEquals(
            array(
                5 => array( $data[0], $data[1], ),
                6 => array( $data[3], ),
            ),
            PHPUnit_Framework_Constraint_Iterator::iteratorToArray(
                10, $iter, TRUE
            )
        );
    }

    public function testIterate_nonField ()
    {
        // Notice that "fruit" is not a field in the iterator that getTestIterator returns
        PHPUnit_Framework_Constraint_Iterator::assert(
            array(),
            $this->getTestIterator( "fruit" )
        );
    }

    public function testIterate_OneDimension ()
    {
        PHPUnit_Framework_Constraint_Iterator::assert(
            array(),
            new \r8\Iterator\Group(
                "field",
                new ArrayIterator( range(1, 10) )
            )
        );
    }

    public function testIterate_empty ()
    {
        PHPUnit_Framework_Constraint_Iterator::assert(
            array(),
            new \r8\Iterator\Group(
                "field",
                new EmptyIterator
            )
        );
    }

    public function testIterate_BadKeys ()
    {
        PHPUnit_Framework_Constraint_Iterator::assert(
            array(),
            new \r8\Iterator\Group(
                "field",
                new \ArrayIterator( array(
                    array( "field" => new stdClass ),
                    array( "field" => array() ),
                    array( "field" => NULL ),
                ) )
            )
        );
    }

}

?>