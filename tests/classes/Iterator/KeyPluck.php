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
class classes_Iterator_KeyPluck extends PHPUnit_Framework_TestCase
{

    public function testIterate_Empty ()
    {
        $pluck = new \r8\Iterator\KeyPluck(
            "field",
            new ArrayIterator(array())
        );

        PHPUnit_Framework_Constraint_Iterator::assert( array(), $pluck );
    }

    public function testIterate_SubArray ()
    {
        $pluck = new \r8\Iterator\KeyPluck(
            "field",
            new ArrayIterator(array(
                50 => array( "field" => "blah" ),
                100 => array( "field" => "blip" ),
                150 => array( "field" => "bloop" )
            ))
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array(
                "blah" => array( "field" => "blah" ),
                "blip" => array( "field" => "blip" ),
                "bloop" => array( "field" => "bloop" )
            ),
            $pluck
        );
    }

    public function testIterate_SubObject ()
    {
        $obj1 = new stdClass;
        $obj1->data = "blah";

        $obj2 = new stdClass;
        $obj2->data = "blip";

        $pluck = new \r8\Iterator\KeyPluck(
            "data",
            new ArrayIterator(array(
                50 => $obj1,
                100 => $obj2
            ))
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array(
                "blah" => $obj1,
                "blip" => $obj2
            ),
            $pluck
        );
    }

    public function testIterate_Mixed ()
    {
        $obj1 = new stdClass;
        $obj1->wrongName = "blah";

        $obj2 = new stdClass;
        $obj2->data = "blip";

        $pluck = new \r8\Iterator\KeyPluck(
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

        $pluck->rewind();
        $this->assertNull( $pluck->key() );
        $this->assertSame( $obj1, $pluck->current() );

        $pluck->next();
        $this->assertNull( $pluck->key() );
        $this->assertSame( "not pluckable", $pluck->current() );

        $pluck->next();
        $this->assertSame( "blip", $pluck->key() );
        $this->assertSame( $obj2, $pluck->current() );

        $pluck->next();
        $this->assertNull( $pluck->key() );
        $this->assertNull( $pluck->current() );

        $pluck->next();
        $this->assertSame( "bloop", $pluck->key() );
        $this->assertSame( array( "data" => "bloop" ), $pluck->current() );

        $pluck->next();
        $this->assertNull( $pluck->key() );
        $this->assertSame( array( "Wrong field" ), $pluck->current() );
    }

}

?>