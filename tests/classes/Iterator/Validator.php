<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of RaindropPHP.
 *
 * RaindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * RaindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with RaindropPHP. If not, see <http://www.RaindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <James@RaindropPHP.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_iterator_validator extends PHPUnit_Framework_TestCase
{

    public function testIncludeAll ()
    {
        $validator = $this->getMock('h2o\iface\Validator', array('validate', 'isValid'));
        $validator->expects( $this->exactly(5) )
            ->method('isValid')
            ->will($this->returnValue(TRUE));

        $iterator = new \h2o\Iterator\Validator(
                new \ArrayIterator(range(1,5)),
                $validator
            );

        $this->assertSame(
                array(1,2,3,4,5),
                \iterator_to_array($iterator)
            );
    }

    public function testExcludeAll ()
    {
        $validator = $this->getMock('h2o\iface\Validator', array('validate', 'isValid'));
        $validator->expects( $this->exactly(5) )
            ->method('isValid')
            ->will($this->returnValue(FALSE));

        $iterator = new \h2o\Iterator\Validator(
                new \ArrayIterator(range(1,5)),
                $validator
            );

        $this->assertSame(
                array(),
                \iterator_to_array($iterator)
            );
    }

    public function testExcludeSome ()
    {
        $validator = new \h2o\Validator\Callback(function ($value) {
            return $value % 2 == 0 ? NULL : "Error";
        });

        $iterator = new \h2o\Iterator\Validator(
                new \ArrayIterator(range(1,5)),
                $validator
            );

        $this->assertSame(
                array( 1 => 2, 3 => 4 ),
                \iterator_to_array($iterator)
            );
    }

}

?>