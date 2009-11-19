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

require_once rtrim( __DIR__, "/" ) ."/../general.php";

class stub_Enum extends \r8\Enum
{
    const ONE = 1;
    const TWO = 2;
    const THREE = 3;
}

class stub_Enum_nonUnique extends \r8\Enum
{
    const ONE = "ONE";
    const UNE = "ONE";
}

class stub_Enum_Conflict extends \r8\Enum
{
    const ONE = "UNE";
    const UNE = "ONE";
}

/**
 * unit tests
 */
class classes_Enum extends PHPUnit_Framework_TestCase
{

    public function testGetValues_NonInstantiable ()
    {
        try {
            \r8\Enum::getValues();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Interaction $err ) {
            $this->assertSame( "Enum class is not instantiable", $err->getMessage() );
        }
    }

    public function testGetValues_NonUnique ()
    {
        try {
            \stub_Enum_nonUnique::getValues();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Interaction $err ) {
            $this->assertSame( "Enum values must be unique", $err->getMessage() );
        }
    }

    public function testGetValues_Conflict ()
    {
        try {
            \stub_Enum_Conflict::getValues();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Interaction $err ) {
            $this->assertSame( "Enum contains a conflicting label and value", $err->getMessage() );
        }
    }

    public function testGetValues ()
    {
        $this->assertSame(
            array( "ONE" => 1, "TWO" => 2, "THREE" => 3 ),
            \stub_Enum::getValues()
        );

        $this->assertSame(
            array( "ONE" => 1, "TWO" => 2, "THREE" => 3 ),
            \stub_Enum::getValues()
        );
    }

    public function testConstruct_Error ()
    {
        try {
            new \stub_Enum("Invalid value");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "Invalid Enum input value", $err->getMessage() );
        }
    }

    public function testConstruct_FromLabel ()
    {
        $enum = new stub_Enum( "ONE" );
        $this->assertSame( "ONE", $enum->getLabel() );
        $this->assertSame( 1, $enum->getValue() );
        $this->assertSame( "1", "$enum" );
    }

    public function testConstruct_FromValue ()
    {
        $enum = new stub_Enum( 3 );
        $this->assertSame( "THREE", $enum->getLabel() );
        $this->assertSame( 3, $enum->getValue() );
        $this->assertSame( "3", "$enum" );
    }

}

?>