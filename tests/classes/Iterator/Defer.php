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
class classes_Iterator_Defer extends PHPUnit_Framework_TestCase
{

    /**
     * Returns an test callback object
     *
     * @return \r8\Curry\Unbound
     */
    public function getTestCallback ( $result )
    {
        $curry = $this->getMock('\r8\Curry\Unbound', array('exec', 'rawExec') );

        $curry->expects( $this->once() )
            ->method( "exec" )
            ->will( $this->returnValue( $result ) );

        return $curry;
    }

    public function testGetInnerIterator_Iterator ()
    {
        $inner = $this->getMock('\Iterator');

        $defer = new \r8\Iterator\Defer(
            $this->getTestCallback( $inner )
        );

        $this->assertSame( $inner, $defer->getInnerIterator() );
        $this->assertSame( $inner, $defer->getInnerIterator() );
        $this->assertSame( $inner, $defer->getInnerIterator() );
    }

    public function testGetInnerIterator_Array ()
    {
        $defer = new \r8\Iterator\Defer(
            $this->getTestCallback( array( "one", "two", "three" ) )
        );

        $inner = $defer->getInnerIterator();

        $this->assertThat( $inner, $this->isInstanceOf( 'ArrayIterator' ) );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array( "one", "two", "three" ),
            $inner
        );

        $this->assertSame( $inner, $defer->getInnerIterator() );
        $this->assertSame( $inner, $defer->getInnerIterator() );
        $this->assertSame( $inner, $defer->getInnerIterator() );
    }

    public function testGetInnerIterator_NonIterator ()
    {
        $defer = new \r8\Iterator\Defer(
            $this->getTestCallback( "Non Iterator" )
        );

        $inner = $defer->getInnerIterator();

        $this->assertThat( $inner, $this->isInstanceOf( 'ArrayIterator' ) );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array( "Non Iterator" ),
            $inner
        );

        $this->assertSame( $inner, $defer->getInnerIterator() );
        $this->assertSame( $inner, $defer->getInnerIterator() );
        $this->assertSame( $inner, $defer->getInnerIterator() );
    }

    public function testIteration ()
    {
        $defer = new \r8\Iterator\Defer(
            $this->getTestCallback( array( "one", "two", "three" ) )
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array( "one", "two", "three" ),
            $defer
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array( "one", "two", "three" ),
            $defer
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array( "one", "two", "three" ),
            $defer
        );
    }

    public function testSerialization ()
    {
        $defer = new \r8\Iterator\Defer(
            $this->getTestCallback( array( "one", "two", "three" ) )
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array( "one", "two", "three" ),
            unserialize( serialize( $defer ) )
        );
    }

}

?>