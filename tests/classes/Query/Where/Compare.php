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
 * @author James Frasca <james@Raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_query_where_compare extends PHPUnit_Framework_TestCase
{

    public function testConstruct ()
    {
        $left = $this->getMock('h2o\iface\Query\Atom');
        $right = $this->getMock('h2o\iface\Query\Atom');

        $compare = $this->getMock(
        		'\h2o\Query\Where\Compare',
                array( "toWhereSQL" ),
                array( $left, $right )
            );

        $this->assertSame( $left, $compare->getLeft() );
        $this->assertSame( $right, $compare->getRight() );
    }

    public function testGetPrecedence ()
    {
        $left = $this->getMock('h2o\iface\Query\Atom');
        $right = $this->getMock('h2o\iface\Query\Atom');

        $compare = $this->getMock(
        		'\h2o\Query\Where\Compare',
                array( "toWhereSQL" ),
                array( $left, $right )
            );

        $this->assertSame( 100, $compare->getPrecedence() );
    }

}

?>