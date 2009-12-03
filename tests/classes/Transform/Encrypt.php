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
class classes_Transform_Encrypt extends PHPUnit_Framework_TestCase
{

    public function testEncrypt ()
    {
        $encrypt = new \r8\Transform\Encrypt( new \r8\Random\Seed("Input") );

        $result = $encrypt->to("oh what a piece of data");

        $this->assertType( 'string', $result );
        $this->assertNotEquals( "oh what a piece of data", $result );
        $this->assertSame( 55, strlen($result) );
        $this->assertSame( "oh what a piece of data", $encrypt->from( $result ) );

        $this->assertNotEquals( $result, $encrypt->to("oh what a piece of data") );
        $this->assertNotEquals( $result, $encrypt->to("oh what a piece of data") );
        $this->assertNotEquals( $result, $encrypt->to("oh what a piece of data") );
    }

    public function testDecrypt ()
    {
        $encrypt = new \r8\Transform\Encrypt( new \r8\Random\Seed("Input") );

        $this->assertSame(
            "oh what a piece of data",
            $encrypt->from( base64_decode(
                "n3qkbK3o8GYCatUzy8psadXNjBVZaLNQnveIcYfKLjI83/3oSQn1jtqyQv7ManhdxjpuSYPrmQ=="
            ))
        );

        $this->assertSame(
            "oh what a piece of data",
            $encrypt->from( base64_decode(
                "4Qkfj77KYCcN9G3JvcMWTv1mjQtmSejFUkmtpxUBAlEn6/+R76NoTehya2jeh0/pRiljapKA7g=="
            ))
        );
    }

    public function testDecrypt_WrongKey ()
    {
        $encrypt = new \r8\Transform\Encrypt( new \r8\Random\Seed("Different Key") );

        $this->assertNotEquals(
            "oh what a piece of data",
            $encrypt->from( base64_decode(
                "n3qkbK3o8GYCatUzy8psadXNjBVZaLNQnveIcYfKLjI83/3oSQn1jtqyQv7ManhdxjpuSYPrmQ=="
            ))
        );
    }

    public function testDecrypt_ShortData ()
    {
        $encrypt = new \r8\Transform\Encrypt( new \r8\Random\Seed("Different Key") );

        try {
            $encrypt->from( "Bloopity bloop" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Interaction $err ) {
            $this->assertSame( "Unable to derive initialization vector", $err->getMessage() );
        }
    }

}

?>