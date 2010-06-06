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
class classes_Filter_Ary extends PHPUnit_Framework_TestCase
{

    public function testConstruct ()
    {
        $int = new \r8\Filter\Integer;

        $filter = new \r8\Filter\Ary( $int );

        $this->assertSame( $int, $filter->getFilter() );
    }

    public function testFilter ()
    {
        $int = new \r8\Filter\Integer;
        $filter = new \r8\Filter\Ary( $int );

        $this->assertSame(
            array(5, 10, 20),
            $filter->filter(array("5", "10.5", 20.2))
        );
    }

    public function testFilter_nonAry ()
    {
        $int = new \r8\Filter\Integer;
        $filter = new \r8\Filter\Ary( $int );

        $this->assertSame( array( 28 ), $filter->filter("28") );

        $this->assertSame( array( 28 ), $filter->filter( array("28") ) );
    }

}

?>