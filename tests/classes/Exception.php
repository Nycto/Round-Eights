<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of commonPHP.
 *
 * commonPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * commonPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with commonPHP. If not, see <http://www.commonphp.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@commonphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * unit tests
 */
class classes_exception extends PHPUnit_Framework_TestCase
{

    public function testMessage ()
    {
        $err = new \cPHP\Exception();
        $this->assertFalse( $err->issetMessage() );

        $err = new \cPHP\Exception("This is a message");
        $this->assertTrue( $err->issetMessage() );
        $this->assertEquals( "This is a message", $err->getMessage() );
    }

    public function testCode ()
    {
        $err = new \cPHP\Exception();
        $this->assertFalse( $err->issetCode() );

        $err = new \cPHP\Exception("This is a message", 543);
        $this->assertTrue( $err->issetCode() );
        $this->assertEquals( 543, $err->getCode() );
    }

    public function testGetTraceByOffset ()
    {
        $err = new \cPHP\Exception();

        $this->assertThat( $err->getTraceByOffset(0), $this->isInstanceOf("cPHP\Ary") );
        $this->assertEquals(
                __FUNCTION__,
                $err->getTraceByOffset(0)->offsetGet("function")
            );
    }

    public function testGetTraceCount ()
    {
        $err = new \cPHP\Exception();

        $this->assertThat( $err->getTraceCount(0), $this->isType("int") );
        $this->assertThat( $err->getTraceCount(0), $this->greaterThan(0) );
    }

    public function testFault ()
    {
        $err = new \cPHP\Exception();

        // test whether setFault and issetFault work
        $this->assertFalse( $err->issetFault() );
        $this->assertSame( $err, $err->setFault(0) );
        $this->assertTrue( $err->issetFault() );

        $this->assertEquals( 0, $err->getFaultOffset() );

        // Now reset the fault and test shiftFault without any arguments
        $this->assertSame( $err, $err->shiftFault() );
        $this->assertEquals( 1, $err->getFaultOffset() );

        // Make sure getFault returns an array
        $this->assertThat( $err->getFault(), $this->isInstanceOf("cPHP\Ary") );

        // test unsetFault
        $this->assertSame( $err, $err->unsetFault() );
        $this->assertFalse( $err->issetFault() );


        // Test shift Fault when no fault is currently set
        $err->shiftFault();
        $this->assertEquals(0, $err->getFaultOffset());

    }

    public function testData ()
    {
        $err = new \cPHP\Exception;

        $this->assertSame( $err, $err->addData("Data Label", 20) );
        $this->assertThat( $err->getData(), $this->isInstanceOf("cPHP\Ary") );
        $this->assertEquals( array("Data Label" => 20), $err->getData()->get() );
        $this->assertEquals( 20, $err->getDataValue("Data Label") );

    }

    public function testThrowing ()
    {
        $this->setExpectedException('\cPHP\Exception');
        throw new \cPHP\Exception;
    }

}

?>