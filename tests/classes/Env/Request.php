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

    public function testGetURL_empty ()
    {
        $req = new \cPHP\Env\Request( array(), array(), array() );

        $url = $req->getURL();
        $this->assertThat( $url, $this->isInstanceOf('cPHP\URL') );

        $this->assertFalse( $url->schemeExists() );
        $this->assertFalse( $url->userNameExists() );
        $this->assertFalse( $url->passwordExists() );
        $this->assertFalse( $url->hostExists() );
        $this->assertFalse( $url->dirExists() );
        $this->assertFalse( $url->filenameExists() );
        $this->assertFalse( $url->extExists() );
        $this->assertFalse( $url->queryExists() );
        $this->assertFalse( $url->fragmentExists() );
    }

    public function testGetURL_partial ()
    {
        $env = new \cPHP\Env\Request(
                array(
                        "SERVER_PROTOCOL" => "HTTP/1.1",
                        "HTTP_HOST" => "example.com",
                        "SERVER_PORT" => "80"
                    ),
                array(),
                array()
            );

        $url = $env->getURL();
        $this->assertThat( $url, $this->isInstanceOf('cPHP\URL') );

        $this->assertSame(
                "http://example.com",
                $url->getURL()
            );
    }

    public function testGetURL_full ()
    {
        $env = new \cPHP\Env\Request(
                array(
                        "SERVER_PROTOCOL" => "HTTP/1.1",
                        "HTTP_HOST" => "example.com",
                        "SERVER_PORT" => "8080",
                        "QUERY_STRING" => "query=val",
                        "SCRIPT_NAME" => "/dir/file.html",
                        "PATH_INFO" => "/test/faux/dirs"
                    ),
                array(),
                array()
            );

        $url = $env->getURL();
        $this->assertThat( $url, $this->isInstanceOf('cPHP\URL') );

        $this->assertSame(
                "http://example.com:8080/dir/file.html/test/faux/dirs?query=val",
                $url->getURL()
            );
    }

    public function testGetURL_IP ()
    {
        $env = new \cPHP\Env\Request(
                array(
                        "SERVER_PROTOCOL" => "HTTP/1.1",
                        "SERVER_ADDR" => "127.0.0.1",
                        "SERVER_PORT" => "8080",
                        "QUERY_STRING" => "query=val&other=here",
                        "SCRIPT_NAME" => "/dir/file.html",
                        "PATH_INFO" => "/test/faux/dirs"
                    ),
                array(),
                array()
            );

        $url = $env->getURL();
        $this->assertThat( $url, $this->isInstanceOf('cPHP\URL') );

        $this->assertSame(
                "http://127.0.0.1:8080/dir/file.html/test/faux/dirs?query=val&other=here",
                $url->getURL()
            );
    }

    public function testGetURL_clone ()
    {
        $env = new \cPHP\Env\Request( array(), array(), array() );

        $url = $env->getURL();
        $this->assertThat( $url, $this->isInstanceOf('cPHP\URL') );

        $this->assertNotSame( $url, $env->getURL() );
        $this->assertNotSame( $url, $env->getURL() );
        $this->assertNotSame( $url, $env->getURL() );
    }

    public function testGetFile ()
    {
        $this->markTestIncomplete();
    }

}

?>