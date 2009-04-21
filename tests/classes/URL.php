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

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * unit tests
 */
class classes_url extends PHPUnit_Framework_TestCase
{

    public function testFromBase ()
    {
        $this->assertThat(
                \cPHP\URL::fromBase(),
                $this->isInstanceOf("cPHP\\URL")
            );
    }

    public function testFromURL ()
    {
        $this->assertThat(
                \cPHP\URL::fromURL(),
                $this->isInstanceOf("cPHP\\URL")
            );
    }

    public function testConstruct ()
    {
        $url = new \cPHP\URL("http://example.net/test.html");

        $this->assertSame(
                "http://example.net/test.html",
                $url->getURL()
            );


        $url = new \cPHP\URL(
                new \cPHP\URL("http://example.net:80/test.html")
            );

        $this->assertSame(
                "http://example.net/test.html",
                $url->getURL()
            );
    }

    public function testToString ()
    {
        $url = new \cPHP\URL;

        $this->assertSame( "", $url->__toString() );
        $this->assertSame( "", "$url" );

        $url->setURL("http://example.net/test.html");

        $this->assertSame( "http://example.net/test.html", $url->__toString() );
        $this->assertSame( "http://example.net/test.html", "$url" );
    }

    public function testSchemeAccessors()
    {
        $url = new \cPHP\URL;

        $this->assertNull( $url->getScheme() );
        $this->assertFalse( $url->schemeExists() );

        $this->assertSame( $url, $url->setScheme("ftp") );
        $this->assertSame( "ftp", $url->getScheme() );
        $this->assertTrue( $url->schemeExists() );

        $this->assertSame( $url, $url->setScheme("") );
        $this->assertNull( $url->getScheme() );
        $this->assertFalse( $url->schemeExists() );

        $this->assertSame( $url, $url->setScheme("  S F T P !@#$ 1") );
        $this->assertSame( "sftp1", $url->getScheme() );
        $this->assertTrue( $url->schemeExists() );

        $this->assertSame( $url, $url->clearScheme() );
        $this->assertNull( $url->getScheme() );
        $this->assertFalse( $url->schemeExists() );
    }

    public function isSameScheme_NoEnv ()
    {
        $url = $this->getMock('cPHP\\URL', array("getEnv"));

        $url->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    new \cPHP\Env\Request(array())
                ));

        $this->assertFalse( $url->isSameScheme() );

        $url->setScheme("http");
        $this->assertFalse( $url->isSameScheme() );
    }

    public function isSameScheme_WithEnv ()
    {
        $url = $this->getMock("cPHP\\URL", array("getEnv"));
        $url->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    new \cPHP\Env\Request(array("SERVER_PROTOCOL" => "HTTP/1.1"))
                ));

        $this->assertFalse( $url->isSameScheme() );

        $url->setScheme("http");
        $this->assertTrue( $url->isSameScheme() );

        $url->setScheme("ftp");
        $this->assertFalse( $url->isSameScheme() );
    }

    public function testFillScheme ()
    {
        $url = $this->getMock("cPHP\\URL", array("getEnv"));
        $url->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    new \cPHP\Env\Request(array("SERVER_PROTOCOL" => "SFTP/1.1"))
                ));

        $this->assertFalse( $url->schemeExists() );
        $this->assertSame( $url, $url->fillScheme() );
        $this->assertSame( "sftp", $url->getScheme() );


        $url = $this->getMock("cPHP\\URL", array("getEnv"));
        $url->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    new \cPHP\Env\Request(array())
                ));

        $this->assertFalse( $url->schemeExists() );
        $this->assertSame( $url, $url->fillScheme() );
        $this->assertFalse( $url->schemeExists() );
    }

    public function testUserNameAccessors ()
    {
        $url = new \cPHP\URL;
        $this->assertFalse( $url->userNameExists() );
        $this->assertNull( $url->getUserName() );


        $this->assertSame( $url, $url->setUserName("uname") );
        $this->assertTrue( $url->userNameExists() );
        $this->assertSame( "uname", $url->getUserName() );

        $this->assertSame( $url, $url->clearUserName() );
        $this->assertFalse( $url->userNameExists() );
        $this->assertNull( $url->getUserName() );

        $this->assertSame( $url, $url->setUserName("uname") );
        $this->assertTrue( $url->userNameExists() );
        $this->assertSame( "uname", $url->getUserName() );

        $this->assertSame( $url, $url->setUserName("  ") );
        $this->assertFalse( $url->userNameExists() );
        $this->assertNull( $url->getUserName() );
    }

    public function testPasswordAccessors ()
    {
        $url = new \cPHP\URL;
        $this->assertFalse( $url->passwordExists() );
        $this->assertNull( $url->getPassword() );

        $this->assertSame( $url, $url->setPassword("pword") );
        $this->assertTrue( $url->passwordExists() );
        $this->assertSame( "pword", $url->getPassword() );

        $this->assertSame( $url, $url->clearPassword() );
        $this->assertFalse( $url->passwordExists() );
        $this->assertNull( $url->getPassword() );

        $this->assertSame( $url, $url->setPassword("pword") );
        $this->assertTrue( $url->passwordExists() );
        $this->assertSame( "pword", $url->getPassword() );

        $this->assertSame( $url, $url->setPassword("  ") );
        $this->assertFalse( $url->passwordExists() );
        $this->assertNull( $url->getPassword() );
    }

    public function testUserInfoAccessors ()
    {
        $url = new \cPHP\URL;
        $this->assertFalse( $url->userInfoExists() );
        $this->assertNull( $url->getUserInfo() );

        $url->setPassword("pword");
        $this->assertFalse( $url->userInfoExists() );
        $this->assertNull( $url->getUserInfo() );

        $url->setUserName("uname");
        $this->assertTrue( $url->userInfoExists() );
        $this->assertSame("uname:pword", $url->getUserInfo() );


        $this->assertSame( $url, $url->setUserInfo("user%20name:pass%2Dword") );
        $this->assertTrue( $url->usernameExists() );
        $this->assertTrue( $url->passwordExists() );
        $this->assertTrue( $url->userInfoExists() );
        $this->assertSame( "user name", $url->getUsername() );
        $this->assertSame( "pass-word", $url->getPassword() );
        $this->assertSame("user+name:pass-word", $url->getUserInfo() );


        $this->assertSame( $url, $url->setUserInfo("uname:pword@example.com") );
        $this->assertTrue( $url->usernameExists() );
        $this->assertTrue( $url->passwordExists() );
        $this->assertTrue( $url->userInfoExists() );
        $this->assertSame( "uname", $url->getUsername() );
        $this->assertSame( "pword", $url->getPassword() );
        $this->assertSame("uname:pword", $url->getUserInfo() );


        $this->assertSame( $url, $url->setUserInfo("uname") );
        $this->assertTrue( $url->usernameExists() );
        $this->assertFalse( $url->passwordExists() );
        $this->assertTrue( $url->userInfoExists() );
        $this->assertSame( "uname", $url->getUsername() );
        $this->assertSame("uname", $url->getUserInfo() );


        $this->assertSame( $url, $url->clearUserInfo() );
        $this->assertFalse( $url->usernameExists() );
        $this->assertFalse( $url->passwordExists() );
        $this->assertFalse( $url->userInfoExists() );
        $this->assertNull($url->getUserInfo() );

    }

    public function testHostAccessors ()
    {
        $url = new \cPHP\URL;

        $this->assertNull( $url->getHost() );
        $this->assertFalse( $url->hostExists() );

        $this->assertSame( $url, $url->setHost("example.com") );
        $this->assertSame( "example.com", $url->getHost() );
        $this->assertTrue( $url->hostExists() );

        $this->assertSame( $url, $url->setHost("") );
        $this->assertNull( $url->getHost() );
        $this->assertFalse( $url->hostExists() );

        $this->assertSame( $url, $url->setHost(".. s ub. . exam!@#ple-domain.com....   ") );
        $this->assertSame( "sub.example-domain.com", $url->getHost() );
        $this->assertTrue( $url->hostExists() );

        $this->assertSame( $url, $url->clearHost() );
        $this->assertNull( $url->getHost() );
        $this->assertFalse( $url->hostExists() );
    }

    public function testIsSameHost_withSub ()
    {
        $url = $this->getMock("cPHP\\URL", array("getEnv"));
        $url->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    new \cPHP\Env\Request(array('HTTP_HOST' => 'sub.example.edu'))
                ));

        $this->assertFalse( $url->isSameHost() );

        $url->setHost("notTheDomain.com");
        $this->assertFalse( $url->isSameHost() );

        $url->setHost("example.edu");
        $this->assertFalse( $url->isSameHost() );

        $url->setHost("sub.example.edu");
        $this->assertTrue( $url->isSameHost() );

        $url->setHost("www.sub.example.edu");
        $this->assertTrue( $url->isSameHost() );
    }

    public function testIsSameHost_wwwSub ()
    {
        $url = $this->getMock("cPHP\\URL", array("getEnv"));
        $url->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    new \cPHP\Env\Request(array('HTTP_HOST' => 'www.example.edu'))
                ));

        $this->assertFalse( $url->isSameHost() );

        $url->setHost("example.edu");
        $this->assertTrue( $url->isSameHost() );

        $url->setHost("www.example.edu");
        $this->assertTrue( $url->isSameHost() );

        $url->setHost("test.com");
        $this->assertFalse( $url->isSameHost() );
    }

    public function testIsSameHost_noSub ()
    {
        $url = $this->getMock("cPHP\\URL", array("getEnv"));
        $url->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    new \cPHP\Env\Request(array('HTTP_HOST' => 'example.edu'))
                ));

        $this->assertFalse( $url->isSameHost() );

        $url->setHost("example.edu");
        $this->assertTrue( $url->isSameHost() );

        $url->setHost("www.example.edu");
        $this->assertTrue( $url->isSameHost() );

        $url->setHost("test.com");
        $this->assertFalse( $url->isSameHost() );
    }

    public function testIsSameHost_noEnv ()
    {
        $url = $this->getMock("cPHP\\URL", array("getEnv"));
        $url->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    new \cPHP\Env\Request(array())
                ));

        // Since neither the SLD or TLD are set, this defaults to the current domain
        $this->assertFalse( $url->isSameHost() );

        $url->setHost('sub.example.com');
        $this->assertFalse( $url->isSameHost() );
    }

    public function testFillHost ()
    {
        $url = $this->getMock("cPHP\\URL", array("getEnv"));
        $url->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    new \cPHP\Env\Request(array('HTTP_HOST' => 'example.com'))
                ));

        $this->assertFalse( $url->hostExists() );
        $this->assertSame( $url, $url->fillHost() );
        $this->assertSame( "example.com", $url->getHost() );


        $url = $this->getMock("cPHP\\URL", array("getEnv"));
        $url->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    new \cPHP\Env\Request(array())
                ));

        $this->assertFalse( $url->hostExists() );
        $this->assertSame( $url, $url->fillHost() );
        $this->assertFalse( $url->hostExists() );
    }

    public function testPortAccessors()
    {
        $url = new \cPHP\URL;

        $this->assertNull( $url->getPort() );
        $this->assertFalse( $url->portExists() );

        $this->assertSame( $url, $url->setPort(80) );
        $this->assertSame( 80, $url->getPort() );
        $this->assertTrue( $url->portExists() );

        $this->assertSame( $url, $url->setPort("22") );
        $this->assertSame( 22, $url->getPort() );
        $this->assertTrue( $url->portExists() );

        $this->assertSame( $url, $url->setPort(0) );
        $this->assertNull( $url->getPort() );
        $this->assertFalse( $url->portExists() );

        $this->assertSame( $url, $url->clearPort() );
        $this->assertNull( $url->getPort() );
        $this->assertFalse( $url->portExists() );
    }

    public function isSamePort_NoEnv ()
    {
        $url = $this->getMock("cPHP\\URL", array("getEnv"));
        $url->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    new \cPHP\Env\Request(array())
                ));

        $this->assertFalse( $url->isSamePort() );

        $url->setPort(2020);
        $this->assertFalse( $url->isSamePort() );

        $url->setPort(80);
        $this->assertFalse( $url->isSamePort() );
    }

    public function isSamePort_WithEnvPort40 ()
    {
        $url = $this->getMock("cPHP\\URL", array("getEnv"));
        $url->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    new \cPHP\Env\Request(array("SERVER_PORT" => "40"))
                ));

        $this->assertFalse( $url->isSamePort() );

        $url->setPort(2020);
        $this->assertFalse( $url->isSamePort() );

        $url->setPort(40);
        $this->assertTrue( $url->isSamePort() );
    }

    public function isSamePort_WithEnvPort80 ()
    {
        $url = $this->getMock("cPHP\\URL", array("getEnv"));
        $url->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    new \cPHP\Env\Request(array("SERVER_PORT" => "80"))
                ));

        $this->assertTrue( $url->isSamePort() );

        $url->setPort(2020);
        $this->assertFalse( $url->isSamePort() );

        $url->setPort(80);
        $this->assertTrue( $url->isSamePort() );
    }

    public function testIsDefaultPort_empty ()
    {
        $url = new \cPHP\URL;
        $this->assertFalse( $url->isDefaultPort() );
    }

    public function testIsDefaultPort_noScheme ()
    {
        $url = new \cPHP\URL;

        $url->setPort( 80 );
        $this->assertFalse( $url->isDefaultPort() );
    }

    public function testIsDefaultPort_noPort ()
    {
        $url = new \cPHP\URL;

        $url->setScheme( "http" );
        $this->assertTrue( $url->isDefaultPort() );
    }

    public function testIsDefaultPort_unknown ()
    {
        $url = new \cPHP\URL;

        $url->setScheme( "notARealScheme" )->setPort(80);
        $this->assertFalse( $url->isDefaultPort() );
    }

    public function testIsDefaultPort_various ()
    {
        $url = new \cPHP\URL;

        $url->setScheme( "http" )->setPort(80);
        $this->assertTrue( $url->isDefaultPort() );
        $url->setPort(2020);
        $this->assertFalse( $url->isDefaultPort() );

        $url->setScheme( "https" )->setPort(443);
        $this->assertTrue( $url->isDefaultPort() );
        $url->setPort(80);
        $this->assertFalse( $url->isDefaultPort() );

        $url->setScheme( "ftp" )->setPort(21);
        $this->assertTrue( $url->isDefaultPort() );
        $url->setPort(80);
        $this->assertFalse( $url->isDefaultPort() );

        $url->setScheme( "ftps" )->setPort(990);
        $this->assertTrue( $url->isDefaultPort() );
        $url->setPort(80);
        $this->assertFalse( $url->isDefaultPort() );

        $url->setScheme( "sftp" )->setPort(115);
        $this->assertTrue( $url->isDefaultPort() );
        $url->setPort(80);
        $this->assertFalse( $url->isDefaultPort() );

        $url->setScheme( "ldap" )->setPort(389);
        $this->assertTrue( $url->isDefaultPort() );
        $url->setPort(80);
        $this->assertFalse( $url->isDefaultPort() );
    }

    public function testFillPort ()
    {
        $url = $this->getMock("cPHP\\URL", array("getEnv"));
        $url->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    new \cPHP\Env\Request(array("SERVER_PORT" => "2020"))
                ));

        $this->assertFalse( $url->portExists() );
        $this->assertSame( $url, $url->fillPort() );
        $this->assertSame( 2020, $url->getPort() );


        $url = $this->getMock("cPHP\\URL", array("getEnv"));
        $url->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    new \cPHP\Env\Request(array())
                ));

        $this->assertFalse( $url->portExists() );
        $this->assertSame( $url, $url->fillPort() );
        $this->assertFalse( $url->portExists() );
    }

    public function testGetHostAndPort ()
    {
        $url = new \cPHP\URL;

        $this->assertNull( $url->getHostAndPort() );

        $url->setPort(90);
        $this->assertNull( $url->getHostAndPort() );

        $url->setHost("example.com");
        $this->assertSame( "example.com:90", $url->getHostAndPort() );

        $url->clearPort();
        $this->assertSame( "example.com", $url->getHostAndPort() );

        $url->clearHost();
        $this->assertNull( $url->getHostAndPort() );
    }

    public function testSetHostAndPort ()
    {
        $url = new \cPHP\URL;

        $this->assertSame( $url, $url->setHostAndPort( "sub.example.com:2020" ) );
        $this->assertSame( "sub.example.com", $url->getHost() );
        $this->assertSame( 2020, $url->getPort() );

        $this->assertSame( $url, $url->setHostAndPort( "sub.example.com" ) );
        $this->assertSame( "sub.example.com", $url->getHost() );
        $this->assertFalse( $url->portExists() );
    }

    public function testGetBase ()
    {
        $url = new \cPHP\URL;

        $this->assertNull( $url->getBase() );

        $url->setPort(21);
        $this->assertNull( $url->getBase() );

        $url->setScheme("ftp");
        $this->assertNull( $url->getBase() );

        $url->setUserInfo("uname:pword");
        $this->assertNull( $url->getBase() );

        $url->setHost("example.com");
        $this->assertSame("ftp://uname:pword@example.com", $url->getBase());

        $url->setPort(50);
        $this->assertSame("ftp://uname:pword@example.com:50", $url->getBase());

        $url->clearPort();
        $this->assertSame("ftp://uname:pword@example.com", $url->getBase());

        $url->clearPassword();
        $this->assertSame("ftp://uname@example.com", $url->getBase());

        $url->clearUsername();
        $this->assertSame("ftp://example.com", $url->getBase());

        $url->clearScheme();
        $this->assertSame("example.com", $url->getBase());

        $url->clearHost();
        $this->assertNull( $url->getBase() );
    }

    public function testSetBase ()
    {
        $url = new \cPHP\URL;

        $this->assertSame( $url, $url->setBase("sftp://uname:pword@sub.example.com:8080") );
        $this->assertSame( "sftp", $url->getScheme() );
        $this->assertSame( "uname", $url->getUsername() );
        $this->assertSame( "pword", $url->getPassword() );
        $this->assertSame( "sub.example.com", $url->getHost() );
        $this->assertSame( 8080, $url->getPort() );

        $this->assertSame( $url, $url->setBase("test.net") );
        $this->assertFalse( $url->schemeExists() );
        $this->assertFalse( $url->usernameExists() );
        $this->assertFalse( $url->passwordExists() );
        $this->assertSame( "test.net", $url->getHost() );
        $this->assertFalse( $url->portExists() );

        $this->assertSame( $url, $url->setBase("sftp://uname@sub.example.com:8080") );
        $this->assertSame( "sftp", $url->getScheme() );
        $this->assertSame( "uname", $url->getUsername() );
        $this->assertFalse( $url->passwordExists() );
        $this->assertSame( "sub.example.com", $url->getHost() );
        $this->assertSame( 8080, $url->getPort() );

        $this->assertSame( $url, $url->setBase("sftp://sub.example.com:8080") );
        $this->assertSame( "sftp", $url->getScheme() );
        $this->assertFalse( $url->usernameExists() );
        $this->assertFalse( $url->passwordExists() );
        $this->assertSame( "sub.example.com", $url->getHost() );
        $this->assertSame( 8080, $url->getPort() );

        $this->assertSame( $url, $url->setBase("sftp://sub.example.com") );
        $this->assertSame( "sftp", $url->getScheme() );
        $this->assertFalse( $url->usernameExists() );
        $this->assertFalse( $url->passwordExists() );
        $this->assertSame( "sub.example.com", $url->getHost() );
        $this->assertFalse( $url->portExists() );

        $this->assertSame( $url, $url->setBase("sub.example.com") );
        $this->assertFalse( $url->schemeExists() );
        $this->assertFalse( $url->usernameExists() );
        $this->assertFalse( $url->passwordExists() );
        $this->assertSame( "sub.example.com", $url->getHost() );
        $this->assertFalse( $url->portExists() );

        $this->assertSame( $url, $url->setBase("uname:pword@sub.example.com") );
        $this->assertFalse( $url->schemeExists() );
        $this->assertSame( "uname", $url->getUsername() );
        $this->assertSame( "pword", $url->getPassword() );
        $this->assertSame( "sub.example.com", $url->getHost() );
        $this->assertFalse( $url->portExists() );

        $this->assertSame( $url, $url->setBase("sub.example.com:8080") );
        $this->assertFalse( $url->schemeExists() );
        $this->assertFalse( $url->usernameExists() );
        $this->assertFalse( $url->passwordExists() );
        $this->assertSame( "sub.example.com", $url->getHost() );
        $this->assertSame( 8080, $url->getPort() );
    }

    public function testIsSameBase_noEnv ()
    {
        $url = $this->getMock("cPHP\\URL", array("getEnv"));
        $url->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    new \cPHP\Env\Request(array())
                ));

        $this->assertFalse( $url->isSameBase() );
    }

    public function isSameBase_WithEnvPort80 ()
    {
        $url = $this->getMock("cPHP\\URL", array("getEnv"));
        $url->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    new \cPHP\Env\Request(array(
                            "SERVER_PROTOCOL" => "HTTP/1.1",
                            'HTTP_HOST' => 'example.edu',
                            "SERVER_PORT" => "80"
                        ))
                ));

        $this->assertFalse( $url->isSameBase() );

        $url->setScheme("http");
        $this->assertFalse( $url->isSameBase() );

        $url->setPort(80);
        $this->assertFalse( $url->isSameBase() );

        $url->setScheme("example.edu");
        $this->assertTrue( $url->isSameBase() );

        $url->clearPort();
        $this->assertTrue( $url->isSameBase() );
    }

    public function testFillBase ()
    {
        $url = $this->getMock("cPHP\\URL", array("getEnv"));
        $url->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    new \cPHP\Env\Request(array(
                            "SERVER_PROTOCOL" => "HTTP/1.1",
                            'HTTP_HOST' => 'example.edu',
                            "SERVER_PORT" => "80"
                        ))
                ));

        $this->assertFalse( $url->schemeExists() );
        $this->assertFalse( $url->portExists() );
        $this->assertFalse( $url->hostExists() );
        $this->assertSame( $url, $url->fillBase() );
        $this->assertSame( "http", $url->getScheme() );
        $this->assertSame( "example.edu", $url->getHost() );
        $this->assertSame( 80, $url->getPort() );


        $url = $this->getMock("cPHP\\URL", array("getEnv"));
        $url->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    new \cPHP\Env\Request(array())
                ));

        $this->assertFalse( $url->schemeExists() );
        $this->assertFalse( $url->portExists() );
        $this->assertFalse( $url->hostExists() );
        $this->assertSame( $url, $url->fillBase() );
        $this->assertFalse( $url->schemeExists() );
        $this->assertFalse( $url->portExists() );
        $this->assertFalse( $url->hostExists() );
    }

    public function testDirAccessors ()
    {
        $url = new \cPHP\URL;
        $this->assertFalse( $url->dirExists() );
        $this->assertNull( $url->getDir() );

        $this->assertSame( $url, $url->setDir("/dir/path") );
        $this->assertTrue( $url->dirExists() );
        $this->assertSame( "/dir/path/", $url->getDir() );

        $this->assertSame( $url, $url->clearDir() );
        $this->assertFalse( $url->dirExists() );
        $this->assertNull( $url->getDir() );

        $this->assertSame( $url, $url->setDir("dir/path/") );
        $this->assertTrue( $url->dirExists() );
        $this->assertSame( "/dir/path/", $url->getDir() );

        $this->assertSame( $url, $url->setDir("  ") );
        $this->assertFalse( $url->dirExists() );
        $this->assertNull( $url->getDir() );
    }

    public function testFilenameAccessors ()
    {
        $url = new \cPHP\URL;
        $this->assertFalse( $url->filenameExists() );
        $this->assertNull( $url->getFilename() );

        $this->assertSame( $url, $url->setFilename("filenm") );
        $this->assertTrue( $url->filenameExists() );
        $this->assertSame( "filenm", $url->getFilename() );

        $this->assertSame( $url, $url->clearFilename() );
        $this->assertFalse( $url->filenameExists() );
        $this->assertNull( $url->getFilename() );

        $this->assertSame( $url, $url->setFilename("Filename.2008") );
        $this->assertTrue( $url->filenameExists() );
        $this->assertSame( "Filename.2008", $url->getFilename() );

        $this->assertSame( $url, $url->setFilename("  ") );
        $this->assertFalse( $url->filenameExists() );
        $this->assertNull( $url->getFilename() );
    }

    public function testExtAccessors ()
    {
        $url = new \cPHP\URL;
        $this->assertFalse( $url->extExists() );
        $this->assertNull( $url->getExt() );

        $this->assertSame( $url, $url->setExt("html") );
        $this->assertTrue( $url->extExists() );
        $this->assertSame( "html", $url->getExt() );

        $this->assertSame( $url, $url->clearExt() );
        $this->assertFalse( $url->extExists() );
        $this->assertNull( $url->getExt() );

        $this->assertSame( $url, $url->setExt(".CSS") );
        $this->assertTrue( $url->extExists() );
        $this->assertSame( "CSS", $url->getExt() );

        $this->assertSame( $url, $url->setExt("  ") );
        $this->assertFalse( $url->extExists() );
        $this->assertNull( $url->getExt() );
    }

    public function testSetBasename ()
    {
        $url = new \cPHP\URL;

        $this->assertSame( $url, $url->setBasename("example.php") );
        $this->assertSame( "example", $url->getFilename() );
        $this->assertSame( "php", $url->getExt() );

        $this->assertSame( $url, $url->setBasename("example") );
        $this->assertSame( "example", $url->getFilename() );
        $this->assertNull( $url->getExt() );

        $this->assertSame( $url, $url->setBasename(".php") );
        $this->assertNull( $url->getFilename() );
        $this->assertSame( "php", $url->getExt() );

        $this->assertSame( $url, $url->setBasename("dir/to/example.php") );
        $this->assertSame( "example", $url->getFilename() );
        $this->assertSame( "php", $url->getExt() );

        $this->assertSame( $url, $url->setBasename("") );
        $this->assertNull( $url->getFilename() );
        $this->assertNull( $url->getExt() );
    }

    public function testGetBasename ()
    {
        $url = new \cPHP\URL;
        $this->assertNull( $url->getBasename() );

        $url->setExt("php");
        $this->assertNull( $url->getBasename() );

        $url->setFilename("example");
        $this->assertSame( "example.php", $url->getBasename() );

        $url->clearExt();
        $this->assertSame( "example", $url->getBasename() );

        $url->clearFilename();
        $this->assertNull( $url->getBasename() );
    }

    public function testSetPath ()
    {
        $url = new \cPHP\URL;

        $this->assertSame( $url, $url->setPath("/dir/to/example.php") );
        $this->assertSame( "/dir/to/", $url->getDir() );
        $this->assertSame( "example", $url->getFilename() );
        $this->assertSame( "php", $url->getExt() );

        $this->assertSame( $url, $url->setPath("/dir/to/example.php.BAK") );
        $this->assertSame( "/dir/to/", $url->getDir() );
        $this->assertSame( "example.php", $url->getFilename() );
        $this->assertSame( "BAK", $url->getExt() );

        $this->assertSame( $url, $url->setPath("dir/to/example") );
        $this->assertSame( "/dir/to/", $url->getDir() );
        $this->assertSame( "example", $url->getFilename() );
        $this->assertNull( $url->getExt() );

        $this->assertSame( $url, $url->setPath("example.php") );
        $this->assertSame( "/", $url->getDir() );
        $this->assertSame( "example", $url->getFilename() );
        $this->assertSame( "php", $url->getExt() );

        $this->assertSame( $url, $url->setPath("example") );
        $this->assertSame( "/", $url->getDir() );
        $this->assertSame( "example", $url->getFilename() );
        $this->assertNull( $url->getExt() );

        $this->assertSame( $url, $url->setPath("") );
        $this->assertNull( $url->getDir() );
        $this->assertNull( $url->getFilename() );
        $this->assertNull( $url->getExt() );
    }

    public function testGetPath ()
    {
        $url = new \cPHP\URL;

        $this->assertNull( $url->getPath() );

        $url->setDir("dir/to");
        $this->assertSame( "/dir/to/", $url->getPath() );

        $url->setDir("/dir/to/");
        $this->assertSame( "/dir/to/", $url->getPath() );

        $url->setExt("php");
        $this->assertSame( "/dir/to/", $url->getPath() );

        $url->setFilename("Example");
        $this->assertSame( "/dir/to/Example.php", $url->getPath() );

        $url->clearExt();
        $this->assertSame( "/dir/to/Example", $url->getPath() );

        $url->clearDir();
        $this->assertSame( "Example", $url->getPath() );

        $url->setExt("php");
        $this->assertSame( "Example.php", $url->getPath() );

        $url->clearFilename()->clearExt();
        $this->assertNull( $url->getPath() );
    }

    public function testClearPath ()
    {
        $url = new \cPHP\URL;

        $url->setPath("/dir/to/example.php");

        $this->assertSame( $url, $url->clearPath() );

        $this->assertFalse( $url->dirExists() );
        $this->assertFalse( $url->filenameExists() );
        $this->assertFalse( $url->extExists() );
    }

    public function testPathExists ()
    {
        $url = new \cPHP\URL;
        $this->assertFalse( $url->pathExists() );

        $url->setExt("html");
        $this->assertFalse( $url->pathExists() );

        $url->setFilename("test");
        $this->assertTrue( $url->pathExists() );

        $url->setDir("/dir/");
        $this->assertTrue( $url->pathExists() );

        $url->clearFilename();
        $this->assertTrue( $url->pathExists() );

        $url->clearExt();
        $this->assertTrue( $url->pathExists() );

        $url->clearDir();
        $this->assertFalse( $url->pathExists() );
    }

    public function testFillPath ()
    {
        $url = $this->getMock("cPHP\\URL", array("getEnv"));
        $url->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    new \cPHP\Env\Request(array())
                ));

        $this->assertSame( $url, $url->fillPath() );
        $this->assertNull( $url->getPath() );


        $url = $this->getMock("cPHP\\URL", array("getEnv"));
        $url->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    new \cPHP\Env\Request(array(
                            "SCRIPT_NAME" => "/path/to/file.php",
                        ))
                ));

        $this->assertSame( $url, $url->fillPath() );
        $this->assertSame( "/path/to/", $url->getDir() );
        $this->assertSame( "file", $url->getFilename() );
        $this->assertSame( "php", $url->getExt() );
    }

    public function testFauxDirAccessors ()
    {
        $url = new \cPHP\URL;

        $this->assertNull( $url->getFauxDir() );
        $this->assertFalse( $url->fauxDirExists() );

        $this->assertSame( $url, $url->setFauxDir("/test/of/dirs") );
        $this->assertSame( "/test/of/dirs", $url->getFauxDir() );
        $this->assertTrue( $url->fauxDirExists() );

        $this->assertSame( $url, $url->setFauxDir("") );
        $this->assertNull( $url->getFauxDir() );
        $this->assertFalse( $url->fauxDirExists() );

        $this->assertSame( $url, $url->setFauxDir("dirs") );
        $this->assertSame( "/dirs", $url->getFauxDir() );
        $this->assertTrue( $url->fauxDirExists() );

        $this->assertSame( $url, $url->clearFauxDir() );
        $this->assertNull( $url->getFauxDir() );
        $this->assertFalse( $url->fauxDirExists() );
    }

    public function testFillFauxDir ()
    {
        $url = $this->getMock("cPHP\\URL", array("getEnv"));
        $url->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    new \cPHP\Env\Request(array())
                ));

        $this->assertSame( $url, $url->fillFauxDir() );
        $this->assertNull( $url->getFauxDir() );


        $url = $this->getMock("cPHP\\URL", array("getEnv"));
        $url->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    new \cPHP\Env\Request(array(
                            "PATH_INFO" => "/fake/dir",
                        ))
                ));

        $this->assertSame( $url, $url->fillFauxDir() );
        $this->assertSame( "/fake/dir", $url->getFauxDir() );
    }

    public function testQueryAccessors ()
    {
        $url = new \cPHP\URL;
        $this->assertFalse( $url->queryExists() );
        $this->assertNull( $url->getQuery() );

        $this->assertSame( $url, $url->setQuery("var=val") );
        $this->assertTrue( $url->queryExists() );
        $this->assertSame( "var=val", $url->getQuery() );

        $this->assertSame( $url, $url->clearQuery() );
        $this->assertFalse( $url->queryExists() );
        $this->assertNull( $url->getQuery() );

        $this->assertSame( $url, $url->setQuery("one=once&two=twice") );
        $this->assertTrue( $url->queryExists() );
        $this->assertSame( "one=once&two=twice", $url->getQuery() );

        $this->assertSame( $url, $url->setQuery("  ") );
        $this->assertTrue( $url->queryExists() );
        $this->assertSame( "  ", $url->getQuery() );

        $this->assertSame( $url, $url->setQuery( null ) );
        $this->assertFalse( $url->queryExists() );
        $this->assertNull( $url->getQuery() );
    }

    public function testSetQuery_array ()
    {
        $this->iniSet("arg_separator.output", "&");

        $url = new \cPHP\URL;

        $this->assertSame(
                $url,
                $url->setQuery(array( "var" => "val", "other" => "something" ))
            );
        $this->assertSame( "var=val&other=something", $url->getQuery() );

        $this->assertSame(
                $url,
                $url->setQuery(array( "var" => "", "other" => "   " ))
            );
        $this->assertSame( "var=&other=+++", $url->getQuery() );
    }

    public function testSetQuery_object ()
    {
        $this->iniSet("arg_separator.output", "&");

        $url = new \cPHP\URL;

        $obj = new stdClass;
        $obj->one = 1;
        $obj->two = "2";

        $this->assertSame(
                $url,
                $url->setQuery(array( "var" => $obj ))
            );
        $this->assertSame( "var%5Bone%5D=1&var%5Btwo%5D=2", $url->getQuery() );
    }

    public function testGetParsedQuery ()
    {
        $url = new \cPHP\URL;
        $this->assertSame( array(), $url->getParsedQuery() );

        $url->setQuery("var=val&other=something");
        $this->assertSame(
                array( "var" => "val", "other" => "something" ),
                $url->getParsedQuery()
            );

        $url->setQuery("var%5Bone%5D=1&var%5Btwo%5D=2");
        $this->assertSame(
                array( "var" => array( "one" => "1", "two" => "2" ) ),
                $url->getParsedQuery()
            );
    }

    public function testFillQuery ()
    {
        $url = $this->getMock("cPHP\\URL", array("getEnv"));
        $url->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    new \cPHP\Env\Request(array())
                ));

        $this->assertSame( $url, $url->fillQuery() );
        $this->assertNull( $url->getQuery() );


        $url = $this->getMock("cPHP\\URL", array("getEnv"));
        $url->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    new \cPHP\Env\Request(array(
                            "QUERY_STRING" => "var=value"
                        ))
                ));

        $this->assertSame( $url, $url->fillQuery() );
        $this->assertSame( "var=value", $url->getQuery() );
    }

    public function testFragmentAccessors ()
    {
        $url = new \cPHP\URL;
        $this->assertFalse( $url->fragmentExists() );
        $this->assertNull( $url->getFragment() );

        $this->assertSame( $url, $url->setFragment("frag") );
        $this->assertTrue( $url->fragmentExists() );
        $this->assertSame( "frag", $url->getFragment() );

        $this->assertSame( $url, $url->clearFragment() );
        $this->assertFalse( $url->fragmentExists() );
        $this->assertNull( $url->getFragment() );

        $this->assertSame( $url, $url->setFragment("  ") );
        $this->assertTrue( $url->fragmentExists() );
        $this->assertSame( "  ", $url->getFragment() );
    }

    public function testGetRelative ()
    {
        $url = new \cPHP\URL;
        $this->assertNull( $url->getRelative() );

        $url->setPath("/path/to/file.php");
        $this->assertSame( "/path/to/file.php", $url->getRelative() );

        $url->setQuery("one=single");
        $this->assertSame( "/path/to/file.php?one=single", $url->getRelative() );

        $url->setFragment("top");
        $this->assertSame( "/path/to/file.php?one=single#top", $url->getRelative() );

        $url->setFauxDir("/faux/Dir");
        $this->assertSame( "/path/to/file.php/faux/Dir?one=single#top", $url->getRelative() );

        $url->clearQuery();
        $this->assertSame( "/path/to/file.php/faux/Dir#top", $url->getRelative() );

        $url->clearPath();
        $this->assertSame( "#top", $url->getRelative() );

        $url->setQuery("one=single");
        $this->assertSame( "?one=single#top", $url->getRelative() );
    }

    public function testGetURL ()
    {
        $url = new \cPHP\URL;
        $this->assertNull( $url->getURL() );

        $url->setBase("http://www.example.com/");
        $this->assertSame( "http://www.example.com", $url->getURL() );

        $url->setPath("/path/to/file.php");
        $this->assertSame( "http://www.example.com/path/to/file.php", $url->getURL() );

        $url->setQuery("one=single")
            ->setFragment("frag");
        $this->assertSame(
                "http://www.example.com/path/to/file.php?one=single#frag",
                $url->getURL()
            );
    }

    public function testSetURL ()
    {
        $url = new \cPHP\URL;


        $this->assertSame(
                $url,
                $url->setURL("http://uname:pwd@www.example.com/path/to/file.php?one=single#frag")
            );
        $this->assertSame( "http", $url->getScheme() );
        $this->assertSame( "uname", $url->getUserName() );
        $this->assertSame( "pwd", $url->getPassword() );
        $this->assertSame( "www.example.com", $url->getHost() );
        $this->assertSame( "/path/to/", $url->getDir() );
        $this->assertSame( "file", $url->getFilename() );
        $this->assertSame( "php", $url->getExt() );
        $this->assertSame( "one=single", $url->getQuery() );
        $this->assertSame( "frag", $url->getFragment() );


        $this->assertSame(
                $url,
                $url->setURL("https://example.net/test.html")
            );
        $this->assertSame( "https", $url->getScheme() );
        $this->assertFalse( $url->userNameExists() );
        $this->assertFalse( $url->passwordExists() );
        $this->assertSame( "example.net", $url->getHost() );
        $this->assertSame( "/", $url->getDir() );
        $this->assertSame( "test", $url->getFilename() );
        $this->assertSame( "html", $url->getExt() );
        $this->assertFalse( $url->queryExists() );
        $this->assertFalse( $url->fragmentExists() );


        $this->assertSame( $url, $url->setURL("") );
        $this->assertFalse( $url->schemeExists() );
        $this->assertFalse( $url->userNameExists() );
        $this->assertFalse( $url->passwordExists() );
        $this->assertFalse( $url->hostExists() );
        $this->assertFalse( $url->dirExists() );
        $this->assertFalse( $url->filenameExists() );
        $this->assertFalse( $url->extExists() );
        $this->assertFalse( $url->queryExists() );
        $this->assertFalse( $url->fragmentExists() );


        $this->assertSame( $url, $url->setURL("/subdir/style.css") );
        $this->assertFalse( $url->schemeExists() );
        $this->assertFalse( $url->userNameExists() );
        $this->assertFalse( $url->passwordExists() );
        $this->assertFalse( $url->hostExists() );
        $this->assertSame( "/subdir/", $url->getDir() );
        $this->assertSame( "style", $url->getFilename() );
        $this->assertSame( "css", $url->getExt() );
        $this->assertFalse( $url->queryExists() );
        $this->assertFalse( $url->fragmentExists() );
    }

    public function testClearURL ()
    {
        $url = new \cPHP\URL;
        $url->setURL("http://uname:pwd@www.example.com/path/to/file.php?one=single#frag");
        $url->setFauxDir("/test/dir");

        $this->assertSame( $url, $url->clearURL() );

        $this->assertFalse( $url->schemeExists() );
        $this->assertFalse( $url->userNameExists() );
        $this->assertFalse( $url->passwordExists() );
        $this->assertFalse( $url->hostExists() );
        $this->assertFalse( $url->dirExists() );
        $this->assertFalse( $url->filenameExists() );
        $this->assertFalse( $url->extExists() );
        $this->assertFalse( $url->fauxDirExists() );
        $this->assertFalse( $url->queryExists() );
        $this->assertFalse( $url->fragmentExists() );
    }

    public function testFillURL ()
    {
        $url = $this->getMock("cPHP\\URL", array("getEnv"));
        $url->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    new \cPHP\Env\Request(array())
                ));

        $this->assertSame( $url, $url->fillURL() );
        $this->assertNull( $url->getURL() );


        $url = $this->getMock("cPHP\\URL", array("getEnv"));
        $url->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    new \cPHP\Env\Request(array(
                        "HTTP_HOST" => "test.example.com",
                        "SCRIPT_NAME" => "/path/to/file.php",
                        "SERVER_PORT" => "40",
                        "PATH_INFO" => "/test/faux/dirs",
                        "QUERY_STRING" => "var=value",
                        "SERVER_PROTOCOL" => "HTTP/1.1"
                    ))
                ));

        $this->assertSame( $url, $url->fillURL() );
        $this->assertSame(
                "http://test.example.com:40/path/to/file.php/test/faux/dirs?var=value",
                $url->getURL()
            );
    }

}

?>