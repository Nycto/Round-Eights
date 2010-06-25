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
class classes_Iterator_OnComplete extends PHPUnit_Framework_TestCase
{

    public function testIterate_Once_WithData ()
    {
        $data = array( 50 => "blah", 100 => "blip", 150 => "bloop" );

        $callback = $this->getMock('\r8\Curry\Unbound', array('rawExec', 'exec'));
        $callback->expects( $this->once() )->method( "exec" );

        $iterator = new \r8\Iterator\OnComplete(
            new ArrayIterator($data),
            $callback
        );

        \r8\Test\Constraint\Iterator::assert( $data, $iterator );
        \r8\Test\Constraint\Iterator::assert( $data, $iterator );
        \r8\Test\Constraint\Iterator::assert( $data, $iterator );
    }

    public function testIterate_Once_Empty ()
    {
        $callback = $this->getMock('\r8\Curry\Unbound', array('rawExec', 'exec'));
        $callback->expects( $this->once() )->method( "exec" );

        $iterator = new \r8\Iterator\OnComplete(
            new EmptyIterator,
            $callback
        );

        \r8\Test\Constraint\Iterator::assert( array(), $iterator );
        \r8\Test\Constraint\Iterator::assert( array(), $iterator );
        \r8\Test\Constraint\Iterator::assert( array(), $iterator );
    }

    public function testIterate_Multi_WithData ()
    {
        $data = array( 50 => "blah", 100 => "blip", 150 => "bloop" );

        $callback = $this->getMock('\r8\Curry\Unbound', array('rawExec', 'exec'));
        $callback->expects( $this->exactly(3) )->method( "exec" );

        $iterator = new \r8\Iterator\OnComplete(
            new ArrayIterator($data),
            $callback,
            FALSE
        );

        \r8\Test\Constraint\Iterator::assert( $data, $iterator );
        \r8\Test\Constraint\Iterator::assert( $data, $iterator );
        \r8\Test\Constraint\Iterator::assert( $data, $iterator );
    }

    public function testIterate_Multi_Empty ()
    {
        $callback = $this->getMock('\r8\Curry\Unbound', array('rawExec', 'exec'));
        $callback->expects( $this->exactly(3) )->method( "exec" );

        $iterator = new \r8\Iterator\OnComplete(
            new EmptyIterator,
            $callback,
            FALSE
        );

        \r8\Test\Constraint\Iterator::assert( array(), $iterator );
        \r8\Test\Constraint\Iterator::assert( array(), $iterator );
        \r8\Test\Constraint\Iterator::assert( array(), $iterator );
    }

}

?>