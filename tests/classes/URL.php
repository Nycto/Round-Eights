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
class classes_url extends PHPUnit_Framework_TestCase
{

    public function testSchemeAccessors()
    {
        $uri = new cPHP::URL;

        $this->assertNull( $uri->getScheme() );
        $this->assertFalse( $uri->schemeExists() );

        $this->assertSame( $uri, $uri->setScheme("ftp") );
        $this->assertSame( "ftp", $uri->getScheme() );
        $this->assertTrue( $uri->schemeExists() );

        $this->assertSame( $uri, $uri->setScheme("") );
        $this->assertNull( $uri->getScheme() );
        $this->assertFalse( $uri->schemeExists() );

        $this->assertSame( $uri, $uri->setScheme("  S F T P !@#$ 1") );
        $this->assertSame( "sftp1", $uri->getScheme() );
        $this->assertTrue( $uri->schemeExists() );

        $this->assertSame( $uri, $uri->clearScheme() );
        $this->assertNull( $uri->getScheme() );
        $this->assertFalse( $uri->schemeExists() );
    }

    public function isSameScheme_NoEnv ()
    {
        $uri = $this->getMock("cPHP::URL", array("getEnv"));
        $uri->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    Stub_Env::fromArray(array())
                ));

        $this->assertFalse( $uri->isSameScheme() );

        $uri->setScheme("http");
        $this->assertFalse( $uri->isSameScheme() );
    }

    public function isSameScheme_WithEnv ()
    {
        $uri = $this->getMock("cPHP::URL", array("getEnv"));
        $uri->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    Stub_Env::fromArray(array("SERVER_PROTOCOL" => "HTTP/1.1"))
                ));

        $this->assertFalse( $uri->isSameScheme() );

        $uri->setScheme("http");
        $this->assertTrue( $uri->isSameScheme() );

        $uri->setScheme("ftp");
        $this->assertFalse( $uri->isSameScheme() );
    }

    public function testUserNameAccessors ()
    {
        $uri = new cPHP::URL;
        $this->assertFalse( $uri->userNameExists() );
        $this->assertNull( $uri->getUserName() );

        $this->assertSame( $uri, $uri->setUserName("uname") );
        $this->assertTrue( $uri->userNameExists() );
        $this->assertSame( "uname", $uri->getUserName() );

        $this->assertSame( $uri, $uri->clearUserName() );
        $this->assertFalse( $uri->userNameExists() );
        $this->assertNull( $uri->getUserName() );

        $this->assertSame( $uri, $uri->setUserName("uname") );
        $this->assertTrue( $uri->userNameExists() );
        $this->assertSame( "uname", $uri->getUserName() );

        $this->assertSame( $uri, $uri->setUserName("  ") );
        $this->assertFalse( $uri->userNameExists() );
        $this->assertNull( $uri->getUserName() );
    }

    public function testPasswordAccessors ()
    {
        $uri = new cPHP::URL;
        $this->assertFalse( $uri->passwordExists() );
        $this->assertNull( $uri->getPassword() );

        $this->assertSame( $uri, $uri->setPassword("pword") );
        $this->assertTrue( $uri->passwordExists() );
        $this->assertSame( "pword", $uri->getPassword() );

        $this->assertSame( $uri, $uri->clearPassword() );
        $this->assertFalse( $uri->passwordExists() );
        $this->assertNull( $uri->getPassword() );

        $this->assertSame( $uri, $uri->setPassword("pword") );
        $this->assertTrue( $uri->passwordExists() );
        $this->assertSame( "pword", $uri->getPassword() );

        $this->assertSame( $uri, $uri->setPassword("  ") );
        $this->assertFalse( $uri->passwordExists() );
        $this->assertNull( $uri->getPassword() );
    }

    public function testUserInfoAccessors ()
    {
        $uri = new cPHP::URL;
        $this->assertFalse( $uri->userInfoExists() );
        $this->assertNull( $uri->getUserInfo() );

        $uri->setPassword("pword");
        $this->assertFalse( $uri->userInfoExists() );
        $this->assertNull( $uri->getUserInfo() );

        $uri->setUserName("uname");
        $this->assertTrue( $uri->userInfoExists() );
        $this->assertSame("uname:pword", $uri->getUserInfo() );


        $this->assertSame( $uri, $uri->setUserInfo("user%20name:pass%2Dword") );
        $this->assertTrue( $uri->usernameExists() );
        $this->assertTrue( $uri->passwordExists() );
        $this->assertTrue( $uri->userInfoExists() );
        $this->assertSame( "user name", $uri->getUsername() );
        $this->assertSame( "pass-word", $uri->getPassword() );
        $this->assertSame("user+name:pass-word", $uri->getUserInfo() );


        $this->assertSame( $uri, $uri->setUserInfo("uname:pword@example.com") );
        $this->assertTrue( $uri->usernameExists() );
        $this->assertTrue( $uri->passwordExists() );
        $this->assertTrue( $uri->userInfoExists() );
        $this->assertSame( "uname", $uri->getUsername() );
        $this->assertSame( "pword", $uri->getPassword() );
        $this->assertSame("uname:pword", $uri->getUserInfo() );


        $this->assertSame( $uri, $uri->setUserInfo("uname") );
        $this->assertTrue( $uri->usernameExists() );
        $this->assertFalse( $uri->passwordExists() );
        $this->assertTrue( $uri->userInfoExists() );
        $this->assertSame( "uname", $uri->getUsername() );
        $this->assertSame("uname", $uri->getUserInfo() );


        $this->assertSame( $uri, $uri->clearUserInfo() );
        $this->assertFalse( $uri->usernameExists() );
        $this->assertFalse( $uri->passwordExists() );
        $this->assertFalse( $uri->userInfoExists() );
        $this->assertNull($uri->getUserInfo() );
    }

    public function testHostAccessors ()
    {
        $uri = new cPHP::URL;

        $this->assertNull( $uri->getHost() );
        $this->assertFalse( $uri->hostExists() );

        $this->assertSame( $uri, $uri->setHost("example.com") );
        $this->assertSame( "example.com", $uri->getHost() );
        $this->assertTrue( $uri->hostExists() );

        $this->assertSame( $uri, $uri->setHost("") );
        $this->assertNull( $uri->getHost() );
        $this->assertFalse( $uri->hostExists() );

        $this->assertSame( $uri, $uri->setHost(".. s ub. . exam!@#ple-domain.com....   ") );
        $this->assertSame( "sub.example-domain.com", $uri->getHost() );
        $this->assertTrue( $uri->hostExists() );

        $this->assertSame( $uri, $uri->clearHost() );
        $this->assertNull( $uri->getHost() );
        $this->assertFalse( $uri->hostExists() );
    }

    public function testIsSameHost_withSub ()
    {
        $uri = $this->getMock("cPHP::URL", array("getEnv"));
        $uri->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    Stub_Env::fromArray(array('HTTP_HOST' => 'sub.example.edu'))
                ));

        $this->assertFalse( $uri->isSameHost() );

        $uri->setHost("notTheDomain.com");
        $this->assertFalse( $uri->isSameHost() );

        $uri->setHost("example.edu");
        $this->assertFalse( $uri->isSameHost() );

        $uri->setHost("sub.example.edu");
        $this->assertTrue( $uri->isSameHost() );

        $uri->setHost("www.sub.example.edu");
        $this->assertTrue( $uri->isSameHost() );
    }

    public function testIsSameHost_wwwSub ()
    {
        $uri = $this->getMock("cPHP::URL", array("getEnv"));
        $uri->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    Stub_Env::fromArray(array('HTTP_HOST' => 'www.example.edu'))
                ));

        $this->assertFalse( $uri->isSameHost() );

        $uri->setHost("example.edu");
        $this->assertTrue( $uri->isSameHost() );

        $uri->setHost("www.example.edu");
        $this->assertTrue( $uri->isSameHost() );

        $uri->setHost("test.com");
        $this->assertFalse( $uri->isSameHost() );
    }

    public function testIsSameHost_noSub ()
    {
        $uri = $this->getMock("cPHP::URL", array("getEnv"));
        $uri->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    Stub_Env::fromArray(array('HTTP_HOST' => 'example.edu'))
                ));

        $this->assertFalse( $uri->isSameHost() );

        $uri->setHost("example.edu");
        $this->assertTrue( $uri->isSameHost() );

        $uri->setHost("www.example.edu");
        $this->assertTrue( $uri->isSameHost() );

        $uri->setHost("test.com");
        $this->assertFalse( $uri->isSameHost() );
    }

    public function testIsSameHost_noEnv ()
    {
        $uri = $this->getMock("cPHP::URL", array("getEnv"));
        $uri->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    Stub_Env::fromArray(array())
                ));

        // Since neither the SLD or TLD are set, this defaults to the current domain
        $this->assertFalse( $uri->isSameHost() );

        $uri->setHost('sub.example.com');
        $this->assertFalse( $uri->isSameHost() );
    }

    public function testPortAccessors()
    {
        $uri = new cPHP::URL;

        $this->assertNull( $uri->getPort() );
        $this->assertFalse( $uri->portExists() );

        $this->assertSame( $uri, $uri->setPort(80) );
        $this->assertSame( 80, $uri->getPort() );
        $this->assertTrue( $uri->portExists() );

        $this->assertSame( $uri, $uri->setPort("22") );
        $this->assertSame( 22, $uri->getPort() );
        $this->assertTrue( $uri->portExists() );

        $this->assertSame( $uri, $uri->setPort(0) );
        $this->assertNull( $uri->getPort() );
        $this->assertFalse( $uri->portExists() );

        $this->assertSame( $uri, $uri->clearPort() );
        $this->assertNull( $uri->getPort() );
        $this->assertFalse( $uri->portExists() );
    }

    public function isSamePort_NoEnv ()
    {
        $uri = $this->getMock("cPHP::URL", array("getEnv"));
        $uri->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    Stub_Env::fromArray(array())
                ));

        $this->assertFalse( $uri->isSamePort() );

        $uri->setPort(2020);
        $this->assertFalse( $uri->isSamePort() );

        $uri->setPort(80);
        $this->assertFalse( $uri->isSamePort() );
    }

    public function isSamePort_WithEnvPort40 ()
    {
        $uri = $this->getMock("cPHP::URL", array("getEnv"));
        $uri->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    Stub_Env::fromArray(array("SERVER_PORT" => "40"))
                ));

        $this->assertFalse( $uri->isSamePort() );

        $uri->setPort(2020);
        $this->assertFalse( $uri->isSamePort() );

        $uri->setPort(40);
        $this->assertTrue( $uri->isSamePort() );
    }

    public function isSamePort_WithEnvPort80 ()
    {
        $uri = $this->getMock("cPHP::URL", array("getEnv"));
        $uri->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    Stub_Env::fromArray(array("SERVER_PORT" => "80"))
                ));

        $this->assertTrue( $uri->isSamePort() );

        $uri->setPort(2020);
        $this->assertFalse( $uri->isSamePort() );

        $uri->setPort(80);
        $this->assertTrue( $uri->isSamePort() );
    }

    public function testGetHostAndPort ()
    {
        $uri = new cPHP::URL;

        $this->assertNull( $uri->getHostAndPort() );

        $uri->setPort(90);
        $this->assertNull( $uri->getHostAndPort() );

        $uri->setHost("example.com");
        $this->assertSame( "example.com:90", $uri->getHostAndPort() );

        $uri->clearPort();
        $this->assertSame( "example.com", $uri->getHostAndPort() );

        $uri->clearHost();
        $this->assertNull( $uri->getHostAndPort() );
    }

    public function testSetHostAndPort ()
    {
        $uri = new cPHP::URL;

        $this->assertSame( $uri, $uri->setHostAndPort( "sub.example.com:2020" ) );
        $this->assertSame( "sub.example.com", $uri->getHost() );
        $this->assertSame( 2020, $uri->getPort() );

        $this->assertSame( $uri, $uri->setHostAndPort( "sub.example.com" ) );
        $this->assertSame( "sub.example.com", $uri->getHost() );
        $this->assertFalse( $uri->portExists() );
    }

    public function testGetBase ()
    {
        $uri = new cPHP::URL;

        $this->assertNull( $uri->getBase() );

        $uri->setPort(21);
        $this->assertNull( $uri->getBase() );

        $uri->setScheme("ftp");
        $this->assertNull( $uri->getBase() );

        $uri->setUserInfo("uname:pword");
        $this->assertNull( $uri->getBase() );

        $uri->setHost("example.com");
        $this->assertSame("ftp://uname:pword@example.com:21", $uri->getBase());

        $uri->clearPort();
        $this->assertSame("ftp://uname:pword@example.com", $uri->getBase());

        $uri->clearPassword();
        $this->assertSame("ftp://uname@example.com", $uri->getBase());

        $uri->clearUsername();
        $this->assertSame("ftp://example.com", $uri->getBase());

        $uri->clearScheme();
        $this->assertSame("example.com", $uri->getBase());

        $uri->clearHost();
        $this->assertNull( $uri->getBase() );
    }

    public function testSetBase ()
    {
        $uri = new cPHP::URL;

        $this->assertSame( $uri, $uri->setBase("sftp://uname:pword@sub.example.com:8080") );
        $this->assertSame( "sftp", $uri->getScheme() );
        $this->assertSame( "uname", $uri->getUsername() );
        $this->assertSame( "pword", $uri->getPassword() );
        $this->assertSame( "sub.example.com", $uri->getHost() );
        $this->assertSame( 8080, $uri->getPort() );

        $this->assertSame( $uri, $uri->setBase("test.net") );
        $this->assertFalse( $uri->schemeExists() );
        $this->assertFalse( $uri->usernameExists() );
        $this->assertFalse( $uri->passwordExists() );
        $this->assertSame( "test.net", $uri->getHost() );
        $this->assertFalse( $uri->portExists() );

        $this->assertSame( $uri, $uri->setBase("sftp://uname@sub.example.com:8080") );
        $this->assertSame( "sftp", $uri->getScheme() );
        $this->assertSame( "uname", $uri->getUsername() );
        $this->assertFalse( $uri->passwordExists() );
        $this->assertSame( "sub.example.com", $uri->getHost() );
        $this->assertSame( 8080, $uri->getPort() );

        $this->assertSame( $uri, $uri->setBase("sftp://sub.example.com:8080") );
        $this->assertSame( "sftp", $uri->getScheme() );
        $this->assertFalse( $uri->usernameExists() );
        $this->assertFalse( $uri->passwordExists() );
        $this->assertSame( "sub.example.com", $uri->getHost() );
        $this->assertSame( 8080, $uri->getPort() );

        $this->assertSame( $uri, $uri->setBase("sftp://sub.example.com") );
        $this->assertSame( "sftp", $uri->getScheme() );
        $this->assertFalse( $uri->usernameExists() );
        $this->assertFalse( $uri->passwordExists() );
        $this->assertSame( "sub.example.com", $uri->getHost() );
        $this->assertFalse( $uri->portExists() );

        $this->assertSame( $uri, $uri->setBase("sub.example.com") );
        $this->assertFalse( $uri->schemeExists() );
        $this->assertFalse( $uri->usernameExists() );
        $this->assertFalse( $uri->passwordExists() );
        $this->assertSame( "sub.example.com", $uri->getHost() );
        $this->assertFalse( $uri->portExists() );

        $this->assertSame( $uri, $uri->setBase("uname:pword@sub.example.com") );
        $this->assertFalse( $uri->schemeExists() );
        $this->assertSame( "uname", $uri->getUsername() );
        $this->assertSame( "pword", $uri->getPassword() );
        $this->assertSame( "sub.example.com", $uri->getHost() );
        $this->assertFalse( $uri->portExists() );

        $this->assertSame( $uri, $uri->setBase("sub.example.com:8080") );
        $this->assertFalse( $uri->schemeExists() );
        $this->assertFalse( $uri->usernameExists() );
        $this->assertFalse( $uri->passwordExists() );
        $this->assertSame( "sub.example.com", $uri->getHost() );
        $this->assertSame( 8080, $uri->getPort() );
    }

    public function testIsSameBase_noEnv ()
    {
        $uri = $this->getMock("cPHP::URL", array("getEnv"));
        $uri->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    Stub_Env::fromArray(array())
                ));

        $this->assertFalse( $uri->isSameBase() );
    }

    public function isSameBase_WithEnvPort80 ()
    {
        $uri = $this->getMock("cPHP::URL", array("getEnv"));
        $uri->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    Stub_Env::fromArray(array(
                            "SERVER_PROTOCOL" => "HTTP/1.1",
                            'HTTP_HOST' => 'example.edu',
                            "SERVER_PORT" => "80"
                        ))
                ));

        $this->assertFalse( $uri->isSameBase() );

        $uri->setScheme("http");
        $this->assertFalse( $uri->isSameBase() );

        $uri->setPort(80);
        $this->assertFalse( $uri->isSameBase() );

        $uri->setScheme("example.edu");
        $this->assertTrue( $uri->isSameBase() );

        $uri->clearPort();
        $this->assertTrue( $uri->isSameBase() );
    }

}

?>