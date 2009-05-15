<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of commonPHP.
 *
 * commonPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * commonPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with commonPHP. If not, see <http://www.commonphp.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@commonphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_filter_digits extends PHPUnit_Framework_TestCase
{

    public function testValid ()
    {
        $filter = new \cPHP\Filter\Digits;

        $this->assertSame(
                '1234567890',
                $filter->filter('1234567890')
            );

        $this->assertSame(
                '0987654321',
                $filter->filter('0987654321')
            );

        $this->assertSame(
                '1234',
                $filter->filter(1234)
            );
    }

    public function testInvalidChars ()
    {
        $filter = new \cPHP\Filter\Digits;

        $this->assertEquals("", $filter->filter('ABCDEFGHIJKLMNOPQRSTUVWXYZ'));
        $this->assertEquals("", $filter->filter('abcdefghijklmnopqrstuvwxyz'));
        $this->assertEquals("", $filter->filter('!"#$%&\'()*+,-/:;<=>?@[\]^`{|}~'));

        $this->assertEquals(
                "",
                $filter->filter(
                        implode( "", array_map("chr", range(127, 255) ) )
                    )
            );

    }

}

?>