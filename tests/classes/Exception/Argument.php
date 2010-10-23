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
class classes_Exception_Argument extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a test exception
     *
     * @return \r8\Exception\Argument
     */
    public function getTestException ()
    {
        $throw = function ( $arg1, $arg2 ) {
            return new \r8\Exception\Argument(0, "test", "blah", 505);
        };
        return $throw("arg value", "other arg");
    }

    public function testConstruct ()
    {
        $err = $this->getTestException();

        $this->assertEquals( 0, $err->getArgOffset() );
        $this->assertEquals( "blah", $err->getMessage() );
        $this->assertEquals( 505, $err->getCode() );

        $this->assertSame(
            array(
                'Arg Offset' => 0,
                'Arg Value' => "string('arg value')",
                'Arg Label' => 'test',
            ),
            $err->getData()
        );
    }

    public function testArg ()
    {
        $err = $this->getTestException();

        $this->assertEquals( 0, $err->getArgOffset() );
        $this->assertEquals( "arg value", $err->getArgData() );
    }

    public function testNoArgs ()
    {
        $throw = function () {
            return new \r8\Exception\Argument(0, "test", "blah", 505);
        };
        $this->assertNull( $throw()->getArgOffset() );
    }

}

