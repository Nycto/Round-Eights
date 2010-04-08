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
class classes_Iterator_Pluck extends PHPUnit_Framework_TestCase
{

    public function testIterate_Empty ()
    {
        $pluck = new \r8\Iterator\Pluck(
            "field",
            new ArrayIterator(array())
        );

        \r8\Test\Constraint\Iterator::assert( array(), $pluck );
    }

    public function testIterate_SubArray ()
    {
        $pluck = new \r8\Iterator\Pluck(
            "field",
            new ArrayIterator(array(
                50 => array( "field" => "blah" ),
                100 => array( "field" => "blip" ),
                150 => array( "field" => "bloop" )
            ))
        );

        \r8\Test\Constraint\Iterator::assert(
            array( 50 => "blah", 100 => "blip", 150 => "bloop" ),
            $pluck
        );
    }

    public function testIterate_SubObject ()
    {
        $obj1 = new stdClass;
        $obj1->data = "blah";

        $obj2 = new stdClass;
        $obj2->data = "blip";

        $pluck = new \r8\Iterator\Pluck(
            "data",
            new ArrayIterator(array(
                50 => $obj1,
                100 => $obj2
            ))
        );

        \r8\Test\Constraint\Iterator::assert(
            array( 50 => "blah", 100 => "blip" ),
            $pluck
        );
    }

    public function testIterate_Mixed ()
    {
        $obj1 = new stdClass;
        $obj1->wrongName = "blah";

        $obj2 = new stdClass;
        $obj2->data = "blip";

        $pluck = new \r8\Iterator\Pluck(
            "data",
            new ArrayIterator(array(
                50 => $obj1,
                75 => "not pluckable",
                100 => $obj2,
                125 => NULL,
                150 => array( "data" => "bloop" ),
                180 => array( "Wrong field" )
            ))
        );

        \r8\Test\Constraint\Iterator::assert(
            array(
                50 => NULL,
                75 => NULL,
                100 => "blip",
                125 => NULL,
                150 => "bloop",
                180 => NULL
            ),
            $pluck
        );
    }

}

?>