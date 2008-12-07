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
class classes_filter_float extends PHPUnit_Framework_TestCase
{

    public function testInteger ()
    {
        $filter = new \cPHP\Filter\Float;
        $this->assertSame( 1.0, $filter->filter(1) );
        $this->assertSame( 20.0, $filter->filter(20) );
        $this->assertSame( -10.0, $filter->filter(-10) );
        $this->assertSame( 0.0, $filter->filter(0) );
    }

    public function testBoolean ()
    {
        $filter = new \cPHP\Filter\Float;
        $this->assertSame( 1.0, $filter->filter(TRUE) );
        $this->assertSame( 0.0, $filter->filter(FALSE) );
    }

    public function testFloat ()
    {
        $filter = new \cPHP\Filter\Float;
        $this->assertSame( 1.0, $filter->filter(1.0) );
        $this->assertSame( .5, $filter->filter(.5) );
        $this->assertSame( 20.25, $filter->filter(20.25) );
        $this->assertSame( -10.75, $filter->filter(-10.75) );
        $this->assertSame( 0.0, $filter->filter(0.0) );
    }

    public function testNull ()
    {
        $filter = new \cPHP\Filter\Float;
        $this->assertSame( 0.0, $filter->filter(NULL) );
    }

    public function testIntegerString ()
    {
        $filter = new \cPHP\Filter\Float;

        $this->assertSame( 0.0, $filter->filter("Some String") );
        $this->assertSame( 20.0, $filter->filter("20") );
        $this->assertSame( -20.0, $filter->filter("-20") );
        $this->assertSame( -40.0, $filter->filter("- 40") );
        $this->assertSame( 404040.0, $filter->filter("40-40-40") );
        $this->assertSame( -402030.0, $filter->filter("-40-20-30") );
        $this->assertSame( 50.0, $filter->filter("Some50String") );

    }

    public function testFloatString ()
    {
        $filter = new \cPHP\Filter\Float;

        $this->assertSame( 20.0, $filter->filter("20.0") );
        $this->assertSame( -20.04, $filter->filter("-20.04") );
        $this->assertSame( -40.90, $filter->filter("- 40.90d") );
        $this->assertSame( 50.123, $filter->filter("Some50.123String") );
        $this->assertSame( 50.12, $filter->filter("Some50.12.3String") );
    }

    public function testArray ()
    {
        $filter = new \cPHP\Filter\Float;

        $this->assertSame( 50.5, $filter->filter( array(50.5) ) );
        $this->assertSame( 0.0, $filter->filter( array() ) );
    }

    public function testObject ()
    {
        $filter = new \cPHP\Filter\Float;

        $this->assertSame( 1.0, $filter->filter( $this->getMock("stub_random_obj") ) );
    }

}

?>