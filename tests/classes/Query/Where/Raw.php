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

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_query_where_raw extends PHPUnit_Framework_TestCase
{

    public function testConstruct ()
    {
        $clause = new \h2o\Query\Where\Raw("Value");
        $this->assertSame( "Value", $clause->getValue() );

        $clause = new \h2o\Query\Where\Raw("  Value = 5  ");
        $this->assertSame( "Value = 5", $clause->getValue() );
    }

    public function testGetPrecedence ()
    {
        $clause = new \h2o\Query\Where\Raw("Value");
        $this->assertSame( 0, $clause->getPrecedence() );
    }

    public function testToWhereSQL ()
    {
        $link = new \h2o\DB\BlackHole\Link;

        $clause = new \h2o\Query\Where\Raw("Value");
        $this->assertSame( "Value", $clause->toWhereSQL( $link ) );

        $clause = new \h2o\Query\Where\Raw("  Value = 5  ");
        $this->assertSame( "Value = 5", $clause->toWhereSQL( $link ) );
    }

}

?>