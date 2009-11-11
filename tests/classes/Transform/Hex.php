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
class classes_Transform_Hex extends PHPUnit_Framework_TestCase
{

    public function testTo ()
    {
        $encode = new \h2o\Transform\Hex;

        $this->assertSame(
                "54686973206973206120737472696e67",
                $encode->to("This is a string")
            );
    }

    public function testFrom_simple ()
    {
        $encode = new \h2o\Transform\Hex;

        $this->assertSame(
                "This is a string",
                $encode->from("54686973206973206120737472696e67")
            );
    }

    public function testFrom_cleanup ()
    {
        $encode = new \h2o\Transform\Hex;

        $this->assertSame(
                "This is a string",
                $encode->from("  54 68 69 73 \x 20 69 \x 73 \x 20 61 20 73 74 72 69 6e 67  ")
            );
    }

    public function testFrom_invalid ()
    {
        $encode = new \h2o\Transform\Hex;

        $this->assertNull( $encode->from("GhpcyBpcyBhIHN0cmluZw==") );
    }

}

?>