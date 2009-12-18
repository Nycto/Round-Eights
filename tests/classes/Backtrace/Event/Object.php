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

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_Backtrace_Event_Object extends PHPUnit_Framework_TestCase
{

    public function testConstruct ()
    {
        $event = $this->getMock(
        	'\r8\Backtrace\Event\Object',
            array('visit', 'getResolvedName'),
            array(
                "cls",
                "meth",
            	'/path/example.php',
                1423,
                array( "arg1", "arg2" )
            )
        );

        $this->assertSame( "cls", $event->getClass() );
        $this->assertSame( "meth", $event->getName() );
        $this->assertSame( "/path/example.php", $event->getFile() );
        $this->assertSame( 1423, $event->getLine() );
        $this->assertSame( array( "arg1", "arg2" ), $event->getArgs() );
    }

    public function testConstruct_Error ()
    {
        try {
            $this->getMock(
            	'\r8\Backtrace\Event\Object',
                array('visit', 'getResolvedName'),
                array(
                    null,
                    "Method",
                	'/path/example.php',
                    50,
                    array( "arg1", "arg2" )
                )
            );

            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }
    }

}

?>