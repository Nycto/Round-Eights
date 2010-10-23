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
class classes_Env_Request extends PHPUnit_Framework_TestCase
{

    public function testGetPost ()
    {
        $input = $this->getMock('\r8\iface\Input');
        $req = new \r8\Env\Request( array(), $input );
        $this->assertSame( $input, $req->getPost() );
    }

    public function testGetFiles ()
    {
        $files = $this->getMock('\r8\Input\Files');

        $req = new \r8\Env\Request(
                array(),
                $this->getMock('\r8\iface\Input'),
                $files
            );

        $this->assertSame( $files, $req->getFiles() );
    }

    public function testGetGet ()
    {
        $req = new \r8\Env\Request(
                array( 'QUERY_STRING' => 'one=first&two=second' )
            );

        $get = $req->getGet();

        $this->assertThat( $get, $this->isInstanceOf('\r8\iface\Input') );
        $this->assertSame( $get, $req->getGet() );
        $this->assertSame( $get, $req->getGet() );

        $this->assertSame(
                array( 'one' => 'first', 'two' => 'second' ),
                $get->toArray()
            );
    }

    public function testGetURL_empty ()
    {
        $req = new \r8\Env\Request( array() );

        $url = $req->getURL();
        $this->assertThat( $url, $this->isInstanceOf('r8\URL') );

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
        $env = new \r8\Env\Request(
                array(
                        "SERVER_PROTOCOL" => "HTTP/1.1",
                        "HTTP_HOST" => "example.com",
                        "SERVER_PORT" => "80"
                    )
            );

        $url = $env->getURL();
        $this->assertThat( $url, $this->isInstanceOf('r8\URL') );

        $this->assertSame(
                "http://example.com",
                $url->getURL()
            );
    }

    public function testGetURL_full ()
    {
        $env = new \r8\Env\Request(
                array(
                        "SERVER_PROTOCOL" => "HTTP/1.1",
                        "HTTP_HOST" => "example.com",
                        "SERVER_PORT" => "8080",
                        "QUERY_STRING" => "query=val",
                        "SCRIPT_NAME" => "/dir/file.html",
                        "PATH_INFO" => "/test/faux/dirs"
                    )
            );

        $url = $env->getURL();
        $this->assertThat( $url, $this->isInstanceOf('r8\URL') );

        $this->assertSame(
                "http://example.com:8080/dir/file.html/test/faux/dirs?query=val",
                $url->getURL()
            );
    }

    public function testGetURL_IP ()
    {
        $env = new \r8\Env\Request(
                array(
                        "SERVER_PROTOCOL" => "HTTP/1.1",
                        "SERVER_ADDR" => "127.0.0.1",
                        "SERVER_PORT" => "8080",
                        "QUERY_STRING" => "query=val&other=here",
                        "SCRIPT_NAME" => "/dir/file.html",
                        "PATH_INFO" => "/test/faux/dirs"
                    )
            );

        $url = $env->getURL();
        $this->assertThat( $url, $this->isInstanceOf('r8\URL') );

        $this->assertSame(
                "http://127.0.0.1:8080/dir/file.html/test/faux/dirs?query=val&other=here",
                $url->getURL()
            );
    }

    public function testGetURL_clone ()
    {
        $env = new \r8\Env\Request;

        $url = $env->getURL();
        $this->assertThat( $url, $this->isInstanceOf('r8\URL') );

        $this->assertNotSame( $url, $env->getURL() );
        $this->assertNotSame( $url, $env->getURL() );
        $this->assertNotSame( $url, $env->getURL() );
    }

    public function testGetFile_empty ()
    {
        $env = new \r8\Env\Request;

        $this->assertEquals(
                new \r8\FileSys\File,
                $env->getFile()
            );
    }

    public function testGetFile_full ()
    {
        $env = new \r8\Env\Request(
                array('SCRIPT_FILENAME' => '/example/path/file.php')
            );

        $this->assertEquals(
                new \r8\FileSys\File('/example/path/file.php'),
                $env->getFile()
            );
    }

    public function testGetFile_clone ()
    {
        $env = new \r8\Env\Request;

        $file = $env->getFile();
        $this->assertThat( $file, $this->isInstanceOf('r8\FileSys\File') );

        $this->assertNotSame( $file, $env->getFile() );
        $this->assertNotSame( $file, $env->getFile() );
        $this->assertNotSame( $file, $env->getFile() );
    }

    public function testGetHeaders ()
    {
        $req = new \r8\Env\Request(
                array(),
                $this->getMock('\r8\iface\Input'),
                $this->getMock('\r8\Input\Files'),
                array( 'one' => 'first', 'two' => 'second' )
            );

        $this->assertSame(
                array( 'one' => 'first', 'two' => 'second' ),
                $req->getHeaders()
            );
    }

    public function testIsCLI ()
    {
        $req = new \r8\Env\Request;
        $this->assertFalse( $req->isCLI() );

        $req = new \r8\Env\Request(
            array(),
            $this->getMock('\r8\iface\Input'),
            $this->getMock('\r8\Input\Files'),
            array(),
            FALSE
        );

        $this->assertFalse( $req->isCLI() );


        $req = new \r8\Env\Request(
            array(),
            $this->getMock('\r8\iface\Input'),
            $this->getMock('\r8\Input\Files'),
            array(),
            TRUE
        );

        $this->assertTrue( $req->isCLI() );
    }

    public function testGetCLIArgs_WithArgs ()
    {
        $req = new \r8\Env\Request( array( 'argv' => array('arg') ) );
        $args = $req->getCLIArgs();
        $this->assertEquals( new \r8\CLI\Input(array('arg')), $args );
        $this->assertSame( $args, $req->getCLIArgs() );
    }

    public function testGetCLIArgs_WithoutArgs ()
    {
        $req = new \r8\Env\Request;
        $args = $req->getCLIArgs();
        $this->assertEquals( new \r8\CLI\Input(array()), $args );
        $this->assertSame( $args, $req->getCLIArgs() );
    }

    public function testIsSecure ()
    {
        $req = new \r8\Env\Request;
        $this->assertFalse( $req->isSecure() );

        $req = new \r8\Env\Request( array('HTTPS' => 1) );
        $this->assertTrue( $req->isSecure() );

        $req = new \r8\Env\Request( array('HTTPS' => 'on') );
        $this->assertTrue( $req->isSecure() );

        $req = new \r8\Env\Request( array('SERVER_PORT' => 443) );
        $this->assertTrue( $req->isSecure() );

        $req = new \r8\Env\Request( array('HTTPS' => 'off') );
        $this->assertFalse( $req->isSecure() );
    }

}

