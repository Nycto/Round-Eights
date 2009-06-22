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
class classes_query_atom extends PHPUnit_Framework_TestCase
{

    public function testToOrderedSQL ()
    {
        $link = new \cPHP\DB\BlackHole\Link;

        $atom = $this->getMock("cPHP\Query\Atom", array("toAtomSQL"));
        $atom->expects( $this->once() )
            ->method( "toAtomSQL" )
            ->with( $this->equalTo($link) )
            ->will( $this->returnValue("fieldName") );

        $this->assertSame( "fieldName", $atom->toOrderedSQL( $link ) );
    }

    public function testToSelectSQL ()
    {
        $link = new \cPHP\DB\BlackHole\Link;

        $atom = $this->getMock("cPHP\Query\Atom", array("toAtomSQL"));
        $atom->expects( $this->once() )
            ->method( "toAtomSQL" )
            ->with( $this->equalTo($link) )
            ->will( $this->returnValue("fieldName") );

        $this->assertSame( "fieldName", $atom->toSelectSQL( $link ) );
    }

}

?>