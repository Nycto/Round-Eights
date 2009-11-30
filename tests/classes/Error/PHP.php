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
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_Error_PHP extends PHPUnit_Framework_TestCase
{

    public function testAccessors ()
    {
        $backtrace = new \r8\Backtrace;
        $error = new \r8\Error\PHP(
            "example.php", 123, E_STRICT,
            "Error Message", $backtrace
        );

        $this->assertSame( 123, $error->getLine() );
        $this->assertSame( "example.php", $error->getFile() );
        $this->assertSame( "Error Message", $error->getMessage() );
        $this->assertSame( E_STRICT, $error->getCode() );
        $this->assertSame( $backtrace, $error->getBacktrace() );
    }

    public function testIsFatal_False ()
    {
        $backtrace = new \r8\Backtrace;
        $error = new \r8\Error\PHP(
            "example.php", 123, E_STRICT,
            "Error Message", $backtrace
        );

        $this->assertFalse( $error->isFatal() );
    }

    public function testIsFatal_True ()
    {
        $backtrace = new \r8\Backtrace;
        $error = new \r8\Error\PHP(
            "example.php", 123, E_ERROR,
            "Error Message", $backtrace
        );

        $this->assertTrue( $error->isFatal() );
    }

    public function testGetTypes_Known ()
    {
        $backtrace = new \r8\Backtrace;
        $error = new \r8\Error\PHP(
            "example.php", 123, E_STRICT,
            "Error Message", $backtrace
        );

        $this->assertSame( "Strict", $error->getType() );
    }

    public function testGetTypes_Unknown ()
    {
        $backtrace = new \r8\Backtrace;
        $error = new \r8\Error\PHP(
            "example.php", 123, 7,
            "Error Message", $backtrace
        );

        $this->assertSame( "Unknown Error", $error->getType() );
    }

}

?>