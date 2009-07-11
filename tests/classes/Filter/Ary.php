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
class classes_filter_ary extends PHPUnit_Framework_TestCase
{

    public function testConstruct ()
    {
        $int = new \h2o\Filter\Integer;

        $filter = new \h2o\Filter\Ary( $int );

        $this->assertSame( $int, $filter->getFilter() );
    }

    public function testSetFilter ()
    {
        $int = new \h2o\Filter\Integer;

        $filter = new \h2o\Filter\Ary( $int );

        $this->assertSame( $int, $filter->getFilter() );

        $bool = new \h2o\Filter\Boolean;
        $this->assertSame( $filter, $filter->setFilter($bool) );

        $this->assertSame( $bool, $filter->getFilter() );
    }

    public function testFilter ()
    {
        $int = new \h2o\Filter\Integer;
        $filter = new \h2o\Filter\Ary( $int );

        $this->assertSame(
                array(5, 10, 20),
                $filter->filter(array("5", "10.5", 20.2))
            );
    }

    public function testFilter_nonAry ()
    {
        $int = new \h2o\Filter\Integer;
        $filter = new \h2o\Filter\Ary( $int );

        $this->assertSame(
                array( 28 ),
                $filter->filter("28")
            );

        $this->assertSame(
                array( 28 ),
                $filter->filter( array("28") )
            );
    }

}

?>