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
class classes_exception_argument extends PHPUnit_Framework_TestCase
{

    // Returns an thrown exception
    public function getThrown ()
    {
        try {
            $throw = function ( $arg1, $arg2 ) {
                throw new \r8\Exception\Argument(0, "test", "From our sponsors", 505, 0);
            };
            $throw("arg value", "other arg");
        }
        catch ( \r8\Exception\Argument $err ) {
            return $err;
        }
    }

    public function testConstruct ()
    {
        $err = $this->getThrown();

        $this->assertEquals( 0, $err->getArgOffset() );
        $this->assertEquals( "From our sponsors", $err->getMessage() );
        $this->assertEquals( 505, $err->getCode() );

        $this->assertSame( array("Arg Label" => "test"), $err->getData() );

        $this->assertEquals( 0, $err->getFaultOffset() );

    }

    public function testArg ()
    {
        $err = $this->getThrown();

        $this->assertTrue( $err->issetArg() );
        $this->assertEquals( 0, $err->getArgOffset() );
        $this->assertEquals( "arg value", $err->getArgData() );

        $this->assertSame( $err, $err->setArg(1) );
        $this->assertEquals( "other arg", $err->getArgData() );

        $this->assertSame( $err, $err->unsetArg() );
    }

    public function testUnsetArg ()
    {
        $err = $this->getThrown();

        $this->assertTrue( $err->issetArg() );

        $this->assertSame( $err, $err->unsetArg() );

        $this->assertFalse( $err->issetArg() );
        $this->assertFalse( $err->getArgOffset() );
        $this->assertNull( $err->getArgData() );
    }

    public function testFaultChange ()
    {
        $err = $this->getThrown("garbage arg", "arg v2", "yet another");

        $this->assertEquals( "arg value", $err->getArgData() );

        $err->shiftFault();

        $this->assertTrue( $err->issetArg() );
        $this->assertEquals( "garbage arg", $err->getArgData() );
    }

    public function testNoFault ()
    {
        $err = $this->getThrown();

        $this->assertTrue( $err->issetFault() );
        $this->assertTrue( $err->issetArg() );

        $this->assertSame( $err, $err->unsetFault() );

        $this->assertFalse( $err->issetFault() );
        $this->assertFalse( $err->issetArg() );


        $this->assertSame( $err, $err->setArg(1) );

        $this->assertTrue( $err->issetFault() );
        $this->assertTrue( $err->issetArg() );

        $this->assertEquals( 0, $err->getFaultOffset() );
        $this->assertEquals( 1, $err->getArgOffset() );
    }

    public function testNoArgs ()
    {
        $err = $this->getThrown();

        // Shift the fault to a function call that doesn't have any arguments
        $err->shiftFault();

        $this->assertFalse( $err->issetArg() );
        $this->assertFalse( $err->getArgOffset() );
        $this->assertNull( $err->getArgData() );
    }

    public function testDetailsString ()
    {
        $err = $this->getThrown();

        $this->assertContains("Arg Offset: 0", $err->getDetailsString());
        $this->assertContains("Arg Value: string('arg value')", $err->getDetailsString());
        $this->assertContains("Arg Label: test", $err->getDetailsString());
    }

    public function testDetailsHTML ()
    {
        $err = $this->getThrown();

        $this->assertContains("<dt>Arg Offset</dt><dd>0</dd>", $err->getDetailsHTML());
        $this->assertContains("<dt>Arg Value</dt><dd>string('arg value')</dd>", $err->getDetailsHTML());
        $this->assertContains("<dt>Arg Label</dt><dd>test</dd>", $err->getDetailsHTML());
    }

}

?>