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

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_Query_From_Raw extends PHPUnit_Framework_TestCase
{

    public function testAliasAccessors ()
    {
        $obj = new \r8\Query\From\Raw('Sql');
        $this->assertFalse( $obj->aliasExists() );
        $this->assertNull( $obj->getAlias() );

        $this->assertSame( $obj, $obj->setAlias( 'As' ) );
        $this->assertTrue( $obj->aliasExists() );
        $this->assertSame( 'As', $obj->getAlias() );

        $this->assertSame( $obj, $obj->clearAlias() );
        $this->assertFalse( $obj->aliasExists() );
        $this->assertNull( $obj->getAlias() );

        $this->assertSame( $obj, $obj->setAlias( ' !@# ali as' ) );
        $this->assertTrue( $obj->aliasExists() );
        $this->assertSame( 'alias', $obj->getAlias() );

        $this->assertSame( $obj, $obj->setAlias( '   ' ) );
        $this->assertFalse( $obj->aliasExists() );
        $this->assertNull( $obj->getAlias() );
    }

    public function testToFromSQL ()
    {
        $link = new \r8\DB\Link( new \r8\DB\BlackHole\Link );
        $table = new \r8\Query\From\Raw( "( SELECT 1 )" );

        $this->assertSame( "( SELECT 1 )", $table->toFromSQL($link) );

        $table->setAlias("Alias");
        $this->assertSame( "( SELECT 1 ) AS `Alias`", $table->toFromSQL($link) );
    }

}

