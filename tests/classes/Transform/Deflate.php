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

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_Transform_Deflate extends PHPUnit_Framework_TestCase
{

    public function testTo_Zero ()
    {
        $cmpr = new \r8\Transform\Deflate( 0 );

        $this->assertSame(
    		"AQcA+P9UZXN0aW5n",
            base64_encode($cmpr->to("Testing"))
        );
    }

    public function testTo_Nine ()
    {
        $cmpr = new \r8\Transform\Deflate( 9 );

        $this->assertSame(
    		"C0ktLsnMSwcA",
            base64_encode($cmpr->to("Testing"))
        );
    }

    public function testTo_Default ()
    {
        $cmpr = new \r8\Transform\Deflate;

        $this->assertSame(
    		"C0ktLsnMS1coQaUB",
            base64_encode($cmpr->to("Testing testing testing"))
        );
    }

    public function testFrom ()
    {
        $cmpr = new \r8\Transform\Deflate;

        $this->assertSame(
            "Testing testing testing",
            $cmpr->from( base64_decode("C0ktLsnMS1coQaUB") )
        );

        $this->assertSame(
            "Testing",
            $cmpr->from( base64_decode("C0ktLsnMSwcA") )
        );
    }

}

?>