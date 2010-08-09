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

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * unit tests
 */
class classes_Exception extends PHPUnit_Framework_TestCase
{

    public function testConstruct ()
    {
        $err = new \r8\Exception("This is a message", 543);
        $this->assertEquals( 543, $err->getCode() );
        $this->assertEquals( "This is a message", $err->getMessage() );
    }

    public function testGetTraceByOffset ()
    {
        $err = new \r8\Exception();

        $trace = $err->getTraceByOffset(0);

        $this->assertThat( $trace, $this->isInstanceOf( '\r8\Backtrace\Event' ) );
        $this->assertEquals( __FUNCTION__, $trace->getName() );
    }

    public function testData ()
    {
        $err = new \r8\Exception;

        $this->assertSame( $err, $err->addData("Data Label", 20) );
        $this->assertSame( array("Data Label" => 20), $err->getData() );
        $this->assertEquals( 20, $err->getDataValue("Data Label") );
    }

}

?>