<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of RaindropPHP.
 *
 * RaindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * RaindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with RaindropPHP. If not, see <http://www.RaindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <James@RaindropPHP.com>
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
            \h2o\Soap\Fault::translatePrimeCode("VERSIONMISMATCH")
        );

        $this->assertSame(
        	"MustUnderstand",
            \h2o\Soap\Fault::translatePrimeCode("MustUnderstand")
        );

        $this->assertSame(
        	"DataEncodingUnknown",
            \h2o\Soap\Fault::translatePrimeCode("dataencodingunknown")
        );

        $this->assertSame(
        	"Sender",
            \h2o\Soap\Fault::translatePrimeCode("SeNdEr")
        );

        $this->assertSame(
        	"Receiver",
            \h2o\Soap\Fault::translatePrimeCode("   Receiver   ")
        );

        $this->assertNull(
            \h2o\Soap\Fault::translatePrimeCode("Other")
        );
    }

    public function testConstruct_bare ()
    {
        $fault = new \h2o\Soap\Fault("Fault!");

        $this->assertSame( "Fault!", $fault->getMessage() );
        $this->assertSame( 0, $fault->getCode() );
        $this->assertSame( "Sender", $fault->getPrimeCode() );
        $this->assertSame( array(), $fault->getSubCodes() );
    }

    public function testConstruct_full ()
    {
        $fault = new \h2o\Soap\Fault(
    		"Fault!",
    		"Receiver",
            array( "Sub Code!", "#two!", "   ", 3)
        );

        $this->assertSame( "Fault!", $fault->getMessage() );
        $this->assertSame( 0, $fault->getCode() );
        $this->assertSame( "Receiver", $fault->getPrimeCode() );
        $this->assertSame( array("SubCode", "two", "3"), $fault->getSubCodes() );
    }

}

?>