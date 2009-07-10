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
 * @author James Frasca <james@Raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_validator_ipv4 extends PHPUnit_Framework_TestCase
{

    public function testValid ()
    {
        $validator = new \h2o\Validator\IPv4;

        $this->assertTrue( $validator->isValid("192.168.0.1") );
        $this->assertTrue( $validator->isValid("255.255.255.0") );
        $this->assertTrue( $validator->isValid("209.85.171.99") );
        $this->assertTrue( $validator->isValid("0.0.0.0") );
        $this->assertTrue( $validator->isValid("172.16.0.0") );
        $this->assertTrue( $validator->isValid("169.254.0.0") );
    }

    public function testInvalid ()
    {
        $validator = new \h2o\Validator\IPv4;


        $result = $validator->validate('example');
        $this->assertFalse( $result->isValid() );
        $this->assertSame(
                array("IP address is not valid"),
                $result->getErrors()
            );


        $result = $validator->validate('0.0.0');
        $this->assertFalse( $result->isValid() );
        $this->assertSame(
                array("IP address is not valid"),
                $result->getErrors()
            );


        $result = $validator->validate('2001:0db8:85a3:0000:0000:8a2e:0370:7334');
        $this->assertFalse( $result->isValid() );
        $this->assertSame(
                array("IP address is not valid"),
                $result->getErrors()
            );
    }

}

?>