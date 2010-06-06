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
class classes_filter_url extends PHPUnit_Framework_TestCase
{

    public function testValidChars ()
    {
        $filter = new \r8\Filter\URL;

        $this->assertEquals(
            "abcdefghijklmnopqrstuvwxyz",
            $filter->filter("abcdefghijklmnopqrstuvwxyz")
        );

        $this->assertEquals(
            "ABCDEFGHIJKLMNOPQRSTUVWXYZ",
            $filter->filter("ABCDEFGHIJKLMNOPQRSTUVWXYZ")
        );

        $this->assertEquals(
            "1234567890",
            $filter->filter("1234567890")
        );

        $this->assertEquals(
            "$-_.+!*'(),{}|\\^~[]`<>#%\";/?:@&=",
            $filter->filter("$-_.+!*'(),{}|\\^~[]`<>#%\";/?:@&=")
        );
    }

    public function testInvalidChars ()
    {
        $filter = new \r8\Filter\URL;

        $this->assertEquals(
            "",
            $filter->filter( implode( "", array_map("chr", range(0, 31) ) ) )
        );

        $this->assertEquals(
            "",
            $filter->filter( implode( "", array_map("chr", range(127, 255) ) ) )
        );
    }

    public function testMixedChars ()
    {
        $filter = new \r8\Filter\URL;

        $this->assertEquals(
            'ab12!@#$asd%<>?D{}',
            $filter->filter('ab12!@#$asd%<>?D{}')
        );
    }

}

?>