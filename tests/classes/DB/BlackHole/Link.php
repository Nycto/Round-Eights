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
class classes_db_blackhole_link extends PHPUnit_Framework_TestCase
{

    public function testQuery ()
    {
        $link = new \h2o\DB\BlackHole\Link;
        $this->assertSame( $link, $link->query("SELECT * FROM table") );
    }

    public function testEscapeString ()
    {
        $link = new \h2o\DB\BlackHole\Link;
        $this->assertSame( "\\'escape\\'", $link->escapeString("'escape'") );
    }

    public function testEscape ()
    {
        $link = new \h2o\DB\BlackHole\Link;
        $this->assertSame( "\\'escape\\'", $link->escape("'escape'") );
        $this->assertSame( "100", $link->escape(100) );
    }

    public function testQuote ()
    {
        $link = new \h2o\DB\BlackHole\Link;
        $this->assertSame( "'\\'escape\\''", $link->quote("'escape'") );
        $this->assertSame( "100", $link->quote(100) );
    }

}

?>