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
class classes_env_request extends PHPUnit_Framework_TestCase
{

    public function testHasKey ()
    {
        $ary = array( "one" => "value", "two" => "" );

        $this->assertTrue( \cPHP\Env\Request::hasKey($ary, "one") );
        $this->assertFalse( \cPHP\Env\Request::hasKey($ary, "two") );
        $this->assertFalse( \cPHP\Env\Request::hasKey($ary, "three") );
    }

    public function testGetPost ()
    {
        $req = new \cPHP\Env\Request(
                array(),
                array( 'one' => 'first', 'two' => 'second' ),
                array()
            );

        $this->assertSame(
                array( 'one' => 'first', 'two' => 'second' ),
                $req->getPost()
            );
    }

    public function testGetFiles ()
    {
        $req = new \cPHP\Env\Request(
                array(),
                array(),
                array( 'one' => 'first', 'two' => 'second' )
            );

        $this->assertSame(
                array( 'one' => 'first', 'two' => 'second' ),
                $req->getFiles()
            );
    }

    public function testGetGet ()
    {
        $req = new \cPHP\Env\Request(
                array( 'QUERY_STRING' => 'one=first&two=second' ),
                array(),
                array()
            );

        $this->assertSame(
                array( 'one' => 'first', 'two' => 'second' ),
                $req->getGet()
            );

        $this->assertSame(
                array( 'one' => 'first', 'two' => 'second' ),
                $req->getGet()
            );
    }

    public function testGetURL ()
    {
        $this->markTestIncomplete();
    }

    public function testGetFile ()
    {
        $this->markTestIncomplete();
    }

}

?>