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

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_query_where_logic extends PHPUnit_Framework_TestCase
{

    public function testClauseAccessors ()
    {
        $logic = $this->getMock(
        		'cPHP\Query\Where\Logic',
                array( "getPrecedence", "getDelimiter" )
            );

        $this->assertSame( array(), $logic->getClauses() );

        $where1 = $this->getMock('cPHP\iface\Query\Where');
        $this->assertSame( $logic, $logic->addClause($where1) );
        $this->assertSame( array( $where1 ), $logic->getClauses() );

        $where2 = $this->getMock('cPHP\iface\Query\Where');
        $this->assertSame( $logic, $logic->addClause($where2) );
        $this->assertSame( array( $where1, $where2 ), $logic->getClauses() );

        $this->assertSame( $logic, $logic->addClause($where1) );
        $this->assertSame( array( $where1, $where2 ), $logic->getClauses() );
    }

    public function testToWhereSQL ()
    {
        // Put together the logic object
        $logic = $this->getMock(
        		'cPHP\Query\Where\Logic',
                array( "getPrecedence", "getDelimiter" )
            );
        $logic->expects( $this->once() )
            ->method( "getPrecedence" )
            ->will( $this->returnValue(20) );
        $logic->expects( $this->once() )
            ->method( "getDelimiter" )
            ->will( $this->returnValue( "DELIM" ) );

        // Create a higher precedence WHERE clause
        $where1 = $this->getMock('cPHP\iface\Query\Where');
        $where1->expects( $this->once() )
            ->method( "getPrecedence" )
            ->will( $this->returnValue(30) );
        $where1->expects( $this->once() )
            ->method( "toWhereSQL" )
            ->will( $this->returnValue( "Where1" ) );
        $logic->addClause( $where1 );

        // Create a lower precedence WHERE clause
        $where2 = $this->getMock('cPHP\iface\Query\Where');
        $where2->expects( $this->once() )
            ->method( "getPrecedence" )
            ->will( $this->returnValue(10) );
        $where2->expects( $this->once() )
            ->method( "toWhereSQL" )
            ->will( $this->returnValue( "Where2" ) );
        $logic->addClause( $where2 );

        // Run the actual conversion
        $link = new \cPHP\DB\BlackHole\Link;
        $this->assertSame(
        		"Where1 DELIM (Where2)",
                $logic->toWhereSQL( $link )
            );
    }

}

?>