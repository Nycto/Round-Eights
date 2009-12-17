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
class classes_iterator_filter extends PHPUnit_Framework_TestCase
{

    public function testFilter ()
    {
        $filter = $this->getMock('r8\iface\Filter', array('filter'));

        $filter->expects( $this->at(0) )
            ->method('filter')
            ->with($this->equalTo(1))
            ->will($this->returnValue("one"));

        $filter->expects( $this->at(1) )
            ->method('filter')
            ->with($this->equalTo(2))
            ->will($this->returnValue("two"));

        $filter->expects( $this->at(2) )
            ->method('filter')
            ->with($this->equalTo(3))
            ->will($this->returnValue("three"));

        $iterator = new \r8\Iterator\Filter(
                new \ArrayIterator(range(1,3)),
                $filter
            );

        $this->assertSame(
                array( "one", "two", "three" ),
                \iterator_to_array($iterator)
            );
    }

}

?>