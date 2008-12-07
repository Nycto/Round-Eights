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
class classes_filter_aryoffset extends PHPUnit_Framework_TestCase
{

    public function testSetFilter ()
    {
        $filter = new \cPHP\Filter\AryOffset;

        $intFilter = new \cPHP\Filter\Integer;
        $this->assertEquals(
                $filter,
                $filter->setFilter( 50, $intFilter )
            );

        $list = $filter->getFilters();
        $this->assertThat( $list, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame(
                array( 50 => $intFilter ),
                $list->get()
            );


        $boolFilter = new \cPHP\Filter\Boolean;
        $this->assertEquals(
                $filter,
                $filter->setFilter( 50, $boolFilter )
            );

        $list = $filter->getFilters();
        $this->assertThat( $list, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame(
                array( 50 => $boolFilter ),
                $list->get()
            );


        $this->assertEquals(
                $filter,
                $filter->setFilter( "str", $intFilter)
            );

        $list = $filter->getFilters();
        $this->assertThat( $list, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame(
                array( 50 => $boolFilter, "str" => $intFilter ),
                $list->get()
            );
    }

    public function testImport ()
    {
        $filter = new \cPHP\Filter\AryOffset;

        $filter->import(array(
                5 => new \cPHP\Filter\Number,
                "index" => new \cPHP\Filter\URL
            ));

        $list = $filter->getFilters();
        $this->assertThat( $list, $this->isInstanceOf("cPHP\Ary") );
        $list = $list->get();

        $this->assertArrayHasKey( 5, $list );
        $this->assertThat( $list[5], $this->isInstanceOf("cPHP\Filter\Number") );

        $this->assertArrayHasKey( "index", $list );
        $this->assertThat( $list["index"], $this->isInstanceOf("cPHP\Filter\URL") );
    }

    public function testConstruct ()
    {
        $filter = new \cPHP\Filter\AryOffset(array(
                5 => new \cPHP\Filter\Number,
                "index" => new \cPHP\Filter\URL
            ));

        $list = $filter->getFilters();
        $this->assertThat( $list, $this->isInstanceOf("cPHP\Ary") );
        $list = $list->get();

        $this->assertArrayHasKey( 5, $list );
        $this->assertThat( $list[5], $this->isInstanceOf("cPHP\Filter\Number") );

        $this->assertArrayHasKey( "index", $list );
        $this->assertThat( $list["index"], $this->isInstanceOf("cPHP\Filter\URL") );
    }

    public function testFilter ()
    {
        $filter = new \cPHP\Filter\AryOffset(array(
                1 => new \cPHP\Filter\Number,
                5 => new \cPHP\Filter\Boolean
            ));

        $this->assertSame(
                array( 1 => 10, 5 => true ),
                $filter->filter(array( 1 => "10", 5 => 1 ))
            );

        $ary = new \cPHP\Ary(array( 1 => "10", 5 => 1 ));
        $result = $filter->filter( $ary );

        $this->assertSame( $ary, $result );
        $this->assertSame(
                array( 1 => 10, 5 => true ),
                $result->get()
            );

    }

}

?>