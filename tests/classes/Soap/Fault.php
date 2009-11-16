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
class classes_Soap_Fault extends PHPUnit_Framework_TestCase
{

    public function testTranslatePrimeCode ()
    {
        $this->assertSame(
        	"VersionMismatch",
            \r8\Soap\Fault::translatePrimeCode("VERSIONMISMATCH")
        );

        $this->assertSame(
        	"MustUnderstand",
            \r8\Soap\Fault::translatePrimeCode("MustUnderstand")
        );

        $this->assertSame(
        	"DataEncodingUnknown",
            \r8\Soap\Fault::translatePrimeCode("dataencodingunknown")
        );

        $this->assertSame(
        	"Sender",
            \r8\Soap\Fault::translatePrimeCode("SeNdEr")
        );

        $this->assertSame(
        	"Receiver",
            \r8\Soap\Fault::translatePrimeCode("   Receiver   ")
        );

        $this->assertNull(
            \r8\Soap\Fault::translatePrimeCode("Other")
        );
    }

    public function testConstruct_bare ()
    {
        $fault = new \r8\Soap\Fault("Fault!");

        $this->assertSame( "Fault!", $fault->getMessage() );
        $this->assertSame( 0, $fault->getCode() );
        $this->assertSame( "Sender", $fault->getPrimeCode() );
        $this->assertSame( array(), $fault->getSubCodes() );
    }

    public function testConstruct_full ()
    {
        $fault = new \r8\Soap\Fault(
    		"Fault!",
    		"Receiver",
            array( "Sub Code!", "#two!", "   ", 3)
        );

        $this->assertSame( "Fault!", $fault->getMessage() );
        $this->assertSame( 0, $fault->getCode() );
        $this->assertSame( "Receiver", $fault->getPrimeCode() );
        $this->assertSame( array("SubCode", "two", "3"), $fault->getSubCodes() );
    }

    public function testSetRole ()
    {
        $fault = new \r8\Soap\Fault("Error");
        $this->assertNull( $fault->getRole() );

        $this->assertSame( $fault, $fault->setRole("test:uri") );
        $this->assertSame( "test:uri", $fault->getRole() );
    }

    public function testSetDetails ()
    {
        $fault = new \r8\Soap\Fault("Error");
        $this->assertSame( array(), $fault->getDetails() );

        $this->assertSame(
            $fault,
            $fault->setDetails(array("one" => "once", "two" => "twice"))
        );

        $this->assertSame(
            array("one" => "once", "two" => "twice"),
            $fault->getDetails()
        );
    }

}

?>