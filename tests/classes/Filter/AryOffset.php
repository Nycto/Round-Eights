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
class classes_filter_aryoffset extends PHPUnit_Framework_TestCase
{

    public function testSetFilter ()
    {
        $filter = new \r8\Filter\AryOffset;

        $intFilter = new \r8\Filter\Integer;
        $this->assertEquals(
                $filter,
                $filter->setFilter( 50, $intFilter )
            );

        $this->assertSame(
                array( 50 => $intFilter ),
                $filter->getFilters()
            );


        $boolFilter = new \r8\Filter\Boolean;
        $this->assertEquals(
                $filter,
                $filter->setFilter( 50, $boolFilter )
            );

        $this->assertSame(
                array( 50 => $boolFilter ),$filter->getFilters()
            );


        $this->assertEquals(
                $filter,
                $filter->setFilter( "str", $intFilter)
            );

        $this->assertSame(
                array( 50 => $boolFilter, "str" => $intFilter ),
                $filter->getFilters()
            );
    }

    public function testImport ()
    {
        $filter = new \r8\Filter\AryOffset;

        $filter1 = new \r8\Filter\Number;
        $filter2 = new \r8\Filter\URL;

        $filter->import(array(
                5 => new \r8\Filter\Number,
                "index" => new \r8\Filter\URL
            ));

        $this->assertEquals(
                array(
                        5 => $filter1,
                        "index" => $filter2
                    ),
                $filter->getFilters()
            );
    }

    public function testConstruct ()
    {
        $filter1 = new \r8\Filter\Number;
        $filter2 = new \r8\Filter\URL;

        $filter = new \r8\Filter\AryOffset(array(
                5 => $filter1,
                "index" => $filter2
            ));

        $this->assertSame(
                array(
                        5 => $filter1,
                        "index" => $filter2
                    ),
                $filter->getFilters()
            );
    }

    public function testFilter ()
    {
        $filter = new \r8\Filter\AryOffset(array(
                1 => new \r8\Filter\Number,
                5 => new \r8\Filter\Boolean
            ));

        $this->assertSame(
                array( 1 => 10, 5 => true ),
                $filter->filter( array( 1 => "10", 5 => 1 ) )
            );
    }

}

?>