<?php
/**
 * Unit Test File
 *
 * PHP version 5.3
 *
 * This source file is subject to version 2.0 of the Artistic License. A copy
 * of the license should have been bundled with this source file inside a file
 * named LICENSE.txt. It is also available through the world-wide-web at one
 * of the following URIs:
 * http://www.commonphp.com/license.php
 * http://www.opensource.org/licenses/artistic-license-2.0.php
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @author James Frasca <james@commonphp.com>
 * @license Artistic License 2.0 http://www.commonphp.com/license.php
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * unit tests
 */
class classes_env extends PHPUnit_Framework_TestCase
{

    public function testHasKey ()
    {
        $ary = array( "one" => "value", "two" => "" );

        $this->assertTrue( \cPHP\Env::hasKey($ary, "one") );
        $this->assertFalse( \cPHP\Env::hasKey($ary, "two") );
        $this->assertFalse( \cPHP\Env::hasKey($ary, "three") );
    }

    public function testIsLocal ()
    {
        $env = Stub_Env::fromArray(array(
                "SHELL" => "/bin/bash"
            ));

        $this->assertTrue( $env->local );
        $this->assertTrue( isset($env->local) );


        $env = Stub_Env::fromArray(array());
        $this->assertFalse( $env->local );
    }

    public function testIP ()
    {
        $env = Stub_Env::fromArray(array(
                "SERVER_ADDR" => "127.0.0.1"
            ));

        $this->assertTrue( isset($env->ip) );
        $this->assertSame( "127.0.0.1", $env->ip );


        $env = Stub_Env::fromArray(array());
        $this->assertFalse( isset($env->ip) );
        $this->assertNull( $env->ip );
    }

    public function testQuery ()
    {
        $env = Stub_Env::fromArray(array(
                "QUERY_STRING" => "var=value"
            ));

        $this->assertTrue( isset($env->query) );
        $this->assertSame( "var=value", $env->query );


        $env = Stub_Env::fromArray(array());
        $this->assertFalse( isset($env->query) );
        $this->assertNull( $env->query );
    }

    public function testPort ()
    {
        $env = Stub_Env::fromArray(array(
                "SERVER_PORT" => "40"
            ));

        $this->assertTrue( isset($env->port) );
        $this->assertSame( 40, $env->port );


        $env = Stub_Env::fromArray(array());
        $this->assertFalse( isset($env->port) );
        $this->assertNull( $env->port );
    }

    public function testScheme ()
    {
        $env = Stub_Env::fromArray(array(
                "SERVER_PROTOCOL" => "HTTP/1.1"
            ));

        $this->assertTrue( isset($env->scheme) );
        $this->assertSame( "http", $env->scheme );


        $env = Stub_Env::fromArray(array());
        $this->assertFalse( isset($env->scheme) );
        $this->assertNull( $env->scheme );
    }

    public function testFileInfo ()
    {

        $env = Stub_Env::fromArray(array(
                "SCRIPT_FILENAME" => "/home/user/public_html/info.php"
            ));

        $this->assertTrue( isset($env->path) );
        $this->assertSame( "/home/user/public_html/info.php", $env->path );

        $this->assertTrue( isset($env->basename) );
        $this->assertSame( "info.php", $env->basename );

        $this->assertTrue( isset($env->dir) );
        $this->assertSame( "/home/user/public_html", $env->dir );

        $this->assertTrue( isset($env->filename) );
        $this->assertSame( "info", $env->filename );

        $this->assertTrue( isset($env->extension) );
        $this->assertSame( "php", $env->extension );

    }

    public function testFileInfo_empty ()
    {
        $env = Stub_Env::fromArray(array());

        $this->assertFalse( isset($env->path) );
        $this->assertNull( $env->path );

        $this->assertFalse( isset($env->dir) );
        $this->assertNull( $env->dir );

        $this->assertFalse( isset($env->basename) );
        $this->assertNull( $env->basename );

        $this->assertFalse( isset($env->extension) );
        $this->assertNull( $env->extension );
    }

    public function testSetCWD ()
    {
        $env = Stub_Env::fromArray(array());

        $this->assertTrue( isset($env->cwd) );
        $this->assertType( "string", $env->cwd );
        $this->assertGreaterThan( 0, strlen($env->cwd) );
    }

    public function testLinkProperty ()
    {
        $env = Stub_Env::fromArray(array());

        try {
            $env->link;
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame( "Link property is not publicly available", $err->getMessage() );
        }

        try {
            isset( $env->link );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame( "Link property is not publicly available", $err->getMessage() );
        }
    }

    public function testGetLink_clone ()
    {
        $env = Stub_Env::fromArray(array());

        $this->assertNotSame(
                $env->getLink(),
                $env->getLink()
            );

        $url = $env->getLink();
        $this->assertThat( $url, $this->isInstanceOf("cPHP\\URL") );
        $this->assertNull( $url->getURL() );
        $url->getURL("http://www.example.com");

        $url = $env->getLink();
        $this->assertThat( $url, $this->isInstanceOf("cPHP\\URL") );
        $this->assertNull( $url->getURL() );
    }

    public function testGetLink_empty ()
    {
        $env = Stub_Env::fromArray(array());

        $url = $env->getLink();
        $this->assertThat( $url, $this->isInstanceOf("cPHP\\URL") );

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

    public function testGetLink_full ()
    {
        $env = Stub_Env::fromArray(array(
                "SERVER_PROTOCOL" => "HTTP/1.1",
                "HTTP_HOST" => "example.com",
                "SERVER_PORT" => "80"
            ));

        $url = $env->getLink();
        $this->assertThat( $url, $this->isInstanceOf("cPHP\\URL") );

        $this->assertSame(
                "http://example.com",
                $url->getURL()
            );


        $env = Stub_Env::fromArray(array(
                "SERVER_PROTOCOL" => "HTTP/1.1",
                "HTTP_HOST" => "example.com",
                "SERVER_PORT" => "8080",
                "QUERY_STRING" => "query=val",
                "SCRIPT_NAME" => "/dir/file.html",
                "PATH_INFO" => "/test/faux/dirs"
            ));

        $url = $env->getLink();
        $this->assertThat( $url, $this->isInstanceOf("cPHP\\URL") );

        $this->assertSame(
                "http://example.com:8080/dir/file.html/test/faux/dirs?query=val",
                $url->getURL()
            );
    }

    public function testSetHostInfo_empty ()
    {
        $env = Stub_Env::fromArray(array());

        $this->assertFalse( isset($env->host) );
        $this->assertNull( $env->host );

        $this->assertFalse( isset($env->hostWithPort) );
        $this->assertNull( $env->host );
    }

    public function testSetHostInfo_noPort ()
    {
        $env = Stub_Env::fromArray(array(
                "HTTP_HOST" => "example.com"
            ));

        $this->assertTrue( isset($env->host) );
        $this->assertSame( "example.com", $env->host );

        $this->assertTrue( isset($env->hostWithPort) );
        $this->assertSame( "example.com", $env->hostWithPort );


        $env = Stub_Env::fromArray(array(
                "HTTP_HOST" => "test.sub.example.com"
            ));

        $this->assertTrue( isset($env->host) );
        $this->assertSame( "test.sub.example.com", $env->host );

        $this->assertTrue( isset($env->hostWithPort) );
        $this->assertSame( "test.sub.example.com", $env->hostWithPort );
    }

    public function testSetHostInfo_withPort ()
    {
        $env = Stub_Env::fromArray(array(
                "HTTP_HOST" => "example.com",
                "SERVER_PORT" => "40"
            ));

        $this->assertTrue( isset($env->host) );
        $this->assertSame( "example.com", $env->host );

        $this->assertTrue( isset($env->hostWithPort) );
        $this->assertSame( "example.com:40", $env->hostWithPort );


        $env = Stub_Env::fromArray(array(
                "HTTP_HOST" => "test.sub.example.com",
                "SERVER_PORT" => "40"
            ));

        $this->assertTrue( isset($env->host) );
        $this->assertSame( "test.sub.example.com", $env->host );

        $this->assertTrue( isset($env->hostWithPort) );
        $this->assertSame( "test.sub.example.com:40", $env->hostWithPort );
    }

    public function testSetFauxDir ()
    {
        $env = Stub_Env::fromArray(array());

        $this->assertFalse( isset($env->fauxDirs) );
        $this->assertNull( $env->fauxDirs );

        $env = Stub_Env::fromArray(array(
                "PATH_INFO" => "/test/faux/dirs"
            ));

        $this->assertTrue( isset($env->fauxDirs) );
        $this->assertSame( "/test/faux/dirs", $env->fauxDirs );
    }

    public function testSetURLPath ()
    {
        $env = Stub_Env::fromArray(array(
                "SCRIPT_NAME" => "/path/to/file.php"
            ));

        $this->assertTrue( isset($env->urlPath) );
        $this->assertSame( "/path/to/file.php", $env->urlPath );

        $this->assertTrue( isset($env->urlDir) );
        $this->assertSame( "/path/to/", $env->urlDir );
    }

    public function testSetURLPath_empty ()
    {
        $env = Stub_Env::fromArray(array());

        $this->assertFalse( isset($env->urlPath) );
        $this->assertNull( $env->urlPath );
    }

    public function testSetURL ()
    {
        $env = Stub_Env::fromArray(array());

        $this->assertFalse( isset($env->url) );
        $this->assertNull( $env->url );

        $this->assertNull( $env->absURL );
        $this->assertFalse( isset($env->absURL) );


        $env = Stub_Env::fromArray(array(
                "HTTP_HOST" => "test.example.com"
            ));


        $this->assertFalse( isset($env->url) );
        $this->assertNull( $env->url );

        $this->assertTrue( isset($env->absURL) );
        $this->assertSame(
                "test.example.com",
                $env->absURL
            );

        $env = Stub_Env::fromArray(array(
                "HTTP_HOST" => "test.example.com",
                "SERVER_PROTOCOL" => "HTTP/1.1"
            ));

        $this->assertFalse( isset($env->url) );
        $this->assertNull( $env->url );

        $this->assertTrue( isset($env->absURL) );
        $this->assertSame(
                "http://test.example.com",
                $env->absURL
            );


        $env = Stub_Env::fromArray(array(
                "HTTP_HOST" => "test.example.com",
                "SERVER_PORT" => "40",
                "SERVER_PROTOCOL" => "HTTP/1.1"
            ));

        $this->assertFalse( isset($env->url) );
        $this->assertNull( $env->url );

        $this->assertTrue( isset($env->absURL) );
        $this->assertSame(
                "http://test.example.com:40",
                $env->absURL
            );

        $env = Stub_Env::fromArray(array(
                "HTTP_HOST" => "test.example.com",
                "SCRIPT_NAME" => "/path/to/file.php",
                "SERVER_PORT" => "40",
                "SERVER_PROTOCOL" => "HTTP/1.1"
            ));

        $this->assertTrue( isset($env->url) );
        $this->assertSame( "/path/to/file.php", $env->url );

        $this->assertTrue( isset($env->absURL) );
        $this->assertSame(
                "http://test.example.com:40/path/to/file.php",
                $env->absURL
            );


        $env = Stub_Env::fromArray(array(
                "HTTP_HOST" => "test.example.com",
                "SCRIPT_NAME" => "/path/to/file.php",
                "SERVER_PORT" => "40",
                "SERVER_PROTOCOL" => "HTTP/1.1"
            ));

        $this->assertTrue( isset($env->url) );
        $this->assertSame( "/path/to/file.php", $env->url );

        $this->assertTrue( isset($env->absURL) );
        $this->assertSame(
                "http://test.example.com:40/path/to/file.php",
                $env->absURL
            );


        $env = Stub_Env::fromArray(array(
                "HTTP_HOST" => "test.example.com",
                "SCRIPT_NAME" => "/path/to/file.php",
                "SERVER_PORT" => "40",
                "PATH_INFO" => "/test/faux/dirs",
                "SERVER_PROTOCOL" => "HTTP/1.1"
            ));

        $this->assertTrue( isset($env->url) );
        $this->assertSame( "/path/to/file.php/test/faux/dirs", $env->url );

        $this->assertTrue( isset($env->absURL) );
        $this->assertSame(
                "http://test.example.com:40/path/to/file.php/test/faux/dirs",
                $env->absURL
            );

        $env = Stub_Env::fromArray(array(
                "HTTP_HOST" => "test.example.com",
                "SCRIPT_NAME" => "/path/to/file.php",
                "SERVER_PORT" => "40",
                "PATH_INFO" => "/test/faux/dirs",
                "QUERY_STRING" => "var=value",
                "SERVER_PROTOCOL" => "HTTP/1.1"
            ));

        $this->assertTrue( isset($env->url) );
        $this->assertSame( "/path/to/file.php/test/faux/dirs?var=value", $env->url );

        $this->assertTrue( isset($env->absURL) );
        $this->assertSame(
                "http://test.example.com:40/path/to/file.php/test/faux/dirs?var=value",
                $env->absURL
            );

    }

}

?>