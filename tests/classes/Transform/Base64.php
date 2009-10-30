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
class classes_Transform_base64 extends PHPUnit_Framework_TestCase
{

    public function testEncode ()
    {
        $encode = new \h2o\Transform\Base64;

        $this->assertSame(
                "VGhpcyBpcyBhIHN0cmluZw==",
                $encode->encode("This is a string")
            );
    }

    public function testDecode ()
    {
        $encode = new \h2o\Transform\Base64;

        $this->assertSame(
                "This is a string",
                $encode->decode("VGhpcyBpcyBhIHN0cmluZw==")
            );
    }

}

?>