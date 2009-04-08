<?php
/**
 * Unit Test File
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
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * numeric function unit tests
 */
class functions_array extends PHPUnit_Framework_TestCase
{

    public function testFlatten ()
    {
        $this->assertSame(
                array(1,2,3),
                \cPHP\ary\flatten( array(array(1,2,3)) )
            );

        $this->assertSame(
                array(1,2,3,4,5,6),
                \cPHP\ary\flatten( array(array(1,2,3),array(4,5,6)) )
            );

        $this->assertSame(
                array(1,2,3,4,5,6,7,8),
                \cPHP\ary\flatten( array(array(1,2,3),array(4,5,array(6,7,8))) )
            );

        $this->assertSame(
                array(array(1,2,3),array(4,5,6,7,8)),
                \cPHP\ary\flatten(
                        array(array(1,2,3),array(4,5,array(6,7,8))),
                        2
                    )
            );

        $this->assertSame(
                array(array(1,2,3),array(4,5,array(6,7,8))),
                \cPHP\ary\flatten(
                        array(array(1,2,3),array(4,5,array(6,7,8))),
                        3
                    )
            );

        $this->assertSame(
                array(array(1,2,3),array(4,5,array(6,7,8))),
                \cPHP\ary\flatten(
                        array(array(1,2,3),array(4,5,array(6,7,8))),
                        4
                    )
            );
    }

    public function testBranch_basic ()
    {
        $ary = array();

        $this->assertNull(
                \cPHP\ary\branch($ary, "new", array("one", "two", "three"))
            );
        $this->assertSame(
                array('one' => array('two' => array('three' => 'new'))),
                $ary
            );

        $this->assertNull(
                \cPHP\ary\branch($ary, "other", array( array("one"), array(array("two"), "five")))
            );
        $this->assertSame(
                array('one' => array(
                        'two' => array('three' => 'new', 'five' => 'other')
                    )),
                $ary
            );

        $this->assertNull(
                \cPHP\ary\branch($ary, "val", array('one', 'two'))
            );
        $this->assertSame(
                array( 'one' => array('two' => 'val') ),
                $ary
            );

        $this->assertNull(
                \cPHP\ary\branch($ary, "value", array('first'))
            );
        $this->assertSame(
                array( 'one' => array('two' => 'val'), 'first' => 'value' ),
                $ary
            );

        $this->assertNull(
                \cPHP\ary\branch($ary, "value", array(array('first', '2nd')))
            );
        $this->assertSame(
                array(
                        'one' => array('two' => 'val'),
                        'first' => array( '2nd' => 'value' )
                    ),
                $ary
            );

        $this->assertNull(
                \cPHP\ary\branch($ary, "over", array(array('first', '2nd', '3rd')))
            );
        $this->assertSame(
                array(
                        'one' => array('two' => 'val'),
                        'first' => array( '2nd' => array( '3rd' => 'over' ) )
                    ),
                $ary
            );
    }

    public function testBranch_pushLastKey ()
    {
        $ary = array();

        $this->assertNull( \cPHP\ary\branch($ary, "new", array(null)) );
        $this->assertSame(
                array('new'),
                $ary
            );

        $this->assertNull( \cPHP\ary\branch($ary, "another", array(null)) );
        $this->assertSame(
                array('new', 'another'),
                $ary
            );


        $this->assertNull( \cPHP\ary\branch($ary, "leaf", array('push', null)) );
        $this->assertSame(
                array('new', 'another', 'push' => array('leaf')),
                $ary
            );


        $this->assertNull( \cPHP\ary\branch($ary, "leaf2", array('push', null)) );
        $this->assertSame(
                array('new', 'another', 'push' => array('leaf', 'leaf2')),
                $ary
            );


        $this->assertNull( \cPHP\ary\branch($ary, "leaf3", array('push', null)) );
        $this->assertSame(
                array('new', 'another', 'push' => array('leaf', 'leaf2', 'leaf3')),
                $ary
            );
    }

    public function testBranch_pushMidKey ()
    {
        $ary = array();

        $this->assertNull( \cPHP\ary\branch($ary, "new", array("one", null, "two")) );
        $this->assertSame(
                array('one' => array(array('two' => 'new'))),
                $ary
            );

        $this->assertNull( \cPHP\ary\branch($ary, "val", array("one", null, "two")) );
        $this->assertSame(
                array('one' => array(
                        array('two' => 'new'),
                        array('two' => 'val')
                    )),
                $ary
            );

        $this->assertNull( \cPHP\ary\branch($ary, 3, array("one", null, "three")) );
        $this->assertSame(
                array('one' => array(
                        array('two' => 'new'),
                        array('two' => 'val'),
                        array('three' => 3)
                    )),
                $ary
            );
    }

    public function testTranslateKeys ()
    {
        $ary = array( 'one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5 );

        $this->assertEquals(
                array( 'eno' => 1, 'two' => 2, 'eerht' => 3, 'ruof' => 4, 'five' => 5 ),
                \cPHP\ary\translateKeys(
                        $ary,
                        array('one' => 'eno', 'three' => 'eerht', 'four' => 'ruof')
                    )
            );

        $this->assertEquals(
                array( 'one' => 1, 'two' => 2, 'six' => 3, 'four' => 4, 'five' => 5 ),
                \cPHP\ary\translateKeys(
                        $ary,
                        array('one' => 'five', 'three' => 'six')
                    )
            );
    }

}

?>