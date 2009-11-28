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
class classes_Error_Exception extends PHPUnit_Framework_TestCase
{

    public function testAccessors ()
    {
        $exception = new Exception( "The Message", 5050 );
        $error = new \r8\Error\Exception( $exception );

        $this->assertType( 'integer', $error->getLine() );
        $this->assertSame( __FILE__, $error->getFile() );
        $this->assertSame( "The Message", $error->getMessage() );
        $this->assertSame( 5050, $error->getCode() );
        $this->assertTrue( $error->isFatal() );
    }

    public function testGetBacktrace ()
    {
        $exception = new Exception( "The Message", 5050 );
        $error = new \r8\Error\Exception( $exception );

        $trace = $error->getBacktrace();
        $this->assertThat( $trace, $this->isInstanceOf('\r8\Backtrace') );
        $this->assertGreaterThan( 1, $trace->count() );

        $this->assertSame( $trace, $error->getBacktrace() );
        $this->assertSame( $trace, $error->getBacktrace() );
        $this->assertSame( $trace, $error->getBacktrace() );
    }

}

?>