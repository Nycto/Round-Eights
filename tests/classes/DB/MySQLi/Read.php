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
class classes_db_mysqli_read extends PHPUnit_MySQLi_Framework_TestCase
{

    public function testCount ()
    {
        $link = $this->getLink();

        $result = $link->query("SELECT * FROM ". MYSQLI_TABLE);

        $this->assertThat( $result, $this->isInstanceOf("cPHP\DB\MySQLi\Read") );

        $this->assertSame( 3, $result->count() );
    }

    public function testIteration ()
    {
        $link = $this->getLink();

        $result = $link->query("SELECT * FROM ". MYSQLI_TABLE);

        $this->assertThat( $result, $this->isInstanceOf("cPHP\DB\MySQLi\Read") );


        $copy = array();
        foreach($result AS $key => $value) {
            $copy[$key] = $value;
        }

        $this->assertSame(
                array(
                        array('id' => '1', 'label' => 'alpha', 'data' => 'one'),
                        array('id' => '2', 'label' => 'beta', 'data' => 'two'),
                        array('id' => '3', 'label' => 'gamma', 'data' => 'three')
                    ),
                $copy
            );


        $copy = array();
        foreach($result AS $key => $value) {
            $copy[$key] = $value;
        }

        $this->assertSame(
                array(
                        array('id' => '1', 'label' => 'alpha', 'data' => 'one'),
                        array('id' => '2', 'label' => 'beta', 'data' => 'two'),
                        array('id' => '3', 'label' => 'gamma', 'data' => 'three')
                    ),
                $copy
            );
    }

    public function testFields ()
    {
        $link = $this->getLink();

        $result = $link->query("SELECT * FROM ". MYSQLI_TABLE);

        $this->assertThat( $result, $this->isInstanceOf("cPHP\DB\MySQLi\Read") );

        $fields = $result->getFields();

        $this->assertThat( $fields, $this->isInstanceOf("cPHP\Ary") );

        $this->assertSame(
                array('id', 'label', 'data'),
                $fields->get()
            );
    }

    public function testFree ()
    {
        $link = $this->getLink();

        $result = $link->query("SELECT * FROM ". MYSQLI_TABLE);

        $this->assertThat( $result, $this->isInstanceOf("cPHP\DB\MySQLi\Read") );

        $this->assertTrue( $result->hasResult() );

        $this->assertSame( $result, $result->free() );

        $this->assertFalse( $result->hasResult() );
    }

}

?>