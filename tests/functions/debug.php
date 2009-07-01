<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of raindropPHP.
 *
 * raindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * raindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with raindropPHP. If not, see <http://www.raindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * unit tests
 */
class functions_debug extends PHPUnit_Framework_TestCase
{

    public function testGetDump ()
    {
        $this->assertEquals( "bool(TRUE)", \h2o\getDump( TRUE ) );
        $this->assertEquals( "bool(FALSE)", \h2o\getDump( FALSE ) );

        $this->assertEquals( "null()", \h2o\getDump( null ) );

        $this->assertEquals( "int(1)", \h2o\getDump( 1 ) );

        $this->assertEquals( "float(10.5)", \h2o\getDump( 10.5 ) );

        $this->assertEquals( "string('some string')", \h2o\getDump( "some string" ) );
        $this->assertEquals(
                "string('some string that is goi'...'after fifty characters')",
                \h2o\getDump( "some string that is going to be trimmed after fifty characters" )
            );
        $this->assertEquals( "string('some\\nstring\\twith\\rbreaks')", \h2o\getDump( "some\nstring\twith\rbreaks" ) );

        $this->assertEquals( "array(0)", \h2o\getDump( array() ) );
        $this->assertEquals( "array(1)(int(0) => int(5))", \h2o\getDump( array( 5 ) ) );
        $this->assertEquals(
                "array(2)(int(0) => string('string'), int(20) => float(1.5))",
                \h2o\getDump( array( "string", 20 => 1.5 ) )
            );
        $this->assertEquals(
                "array(5)(int(0) => int(1), int(1) => int(2),...)",
                \h2o\getDump( array( 1, 2, 3, 4, 20 ) )
            );
        $this->assertEquals(
                "array(1)(int(0) => array(2))",
                \h2o\getDump( array( array( 5, 6 ) ) )
            );

        $this->assertEquals( "object(Exception)", \h2o\getDump( new Exception ) );

        $this->assertEquals( "resource(stream)", \h2o\getDump( fopen( __FILE__, "r" ) ) );
    }

}

?>