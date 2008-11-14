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

    public function testSubdomainAccessors ()
    {
        $uri = new cPHP::URL;
        $this->assertFalse( $uri->subdomainExists() );
        $this->assertNull( $uri->getSubdomain() );

        $this->assertSame( $uri, $uri->setSubdomain("sub") );
        $this->assertTrue( $uri->subdomainExists() );
        $this->assertSame( "sub", $uri->getSubdomain() );

        $this->assertSame( $uri, $uri->clearSubdomain() );
        $this->assertFalse( $uri->subdomainExists() );
        $this->assertNull( $uri->getSubdomain() );

        $this->assertSame( $uri, $uri->setSubdomain("!@#sub-12  ") );
        $this->assertTrue( $uri->subdomainExists() );
        $this->assertSame( "sub-12", $uri->getSubdomain() );

        $this->assertSame( $uri, $uri->setSubdomain("  ") );
        $this->assertFalse( $uri->subdomainExists() );
        $this->assertNull( $uri->getSubdomain() );

        $this->assertSame( $uri, $uri->setSubdomain("..sub...sub..") );
        $this->assertTrue( $uri->subdomainExists() );
        $this->assertSame( "sub.sub", $uri->getSubdomain() );
    }

    public function testSldAccessors ()
    {
        $uri = new cPHP::URL;
        $this->assertFalse( $uri->sldExists() );
        $this->assertNull( $uri->getSld() );

        $this->assertSame( $uri, $uri->setSld("domain") );
        $this->assertTrue( $uri->sldExists() );
        $this->assertSame( "domain", $uri->getSld() );

        $this->assertSame( $uri, $uri->clearSld() );
        $this->assertFalse( $uri->sldExists() );
        $this->assertNull( $uri->getSld() );

        $this->assertSame( $uri, $uri->setSld("  example  ") );
        $this->assertTrue( $uri->sldExists() );
        $this->assertSame( "example", $uri->getSld() );

        $this->assertSame( $uri, $uri->setSld("  ") );
        $this->assertFalse( $uri->sldExists() );
        $this->assertNull( $uri->getSld() );

        $this->assertSame( $uri, $uri->setSld("!@#exam<>?ple..-123%^&*") );
        $this->assertTrue( $uri->sldExists() );
        $this->assertSame( "example-123", $uri->getSld() );
    }

    public function testTldAccessors ()
    {
        $uri = new cPHP::URL;
        $this->assertFalse( $uri->tldExists() );
        $this->assertNull( $uri->getTld() );

        $this->assertSame( $uri, $uri->setTld("com") );
        $this->assertTrue( $uri->tldExists() );
        $this->assertSame( "com", $uri->getTld() );

        $this->assertSame( $uri, $uri->clearTld() );
        $this->assertFalse( $uri->tldExists() );
        $this->assertNull( $uri->getTld() );

        $this->assertSame( $uri, $uri->setTld("  net  ") );
        $this->assertTrue( $uri->tldExists() );
        $this->assertSame( "net", $uri->getTld() );

        $this->assertSame( $uri, $uri->setTld("  ") );
        $this->assertFalse( $uri->tldExists() );
        $this->assertNull( $uri->getTld() );

        $this->assertSame( $uri, $uri->setTld("!@#ed<>?u-..%^&*") );
        $this->assertTrue( $uri->tldExists() );
        $this->assertSame( "edu", $uri->getTld() );
    }

    public function testDomainAccessors ()
    {
        $uri = new cPHP::URL;

        $this->assertNull( $uri->getDomain() );
        $this->assertFalse( $uri->domainExists() );


        $this->assertSame( $uri, $uri->setDomain("example") );
        $this->assertFalse( $uri->tldExists() );
        $this->assertTrue( $uri->sldExists() );
        $this->assertFalse( $uri->domainExists() );
        $this->assertFalse( $uri->subdomainExists() );
        $this->assertNull( $uri->getSubdomain() );
        $this->assertSame( "example", $uri->getSld() );
        $this->assertNull( $uri->getTld() );
        $this->assertSame( "example", $uri->getDomain() );


        $this->assertSame( $uri, $uri->setDomain("example.com") );
        $this->assertTrue( $uri->tldExists() );
        $this->assertTrue( $uri->sldExists() );
        $this->assertTrue( $uri->domainExists() );
        $this->assertFalse( $uri->subdomainExists() );
        $this->assertNull( $uri->getSubdomain() );
        $this->assertSame( "example", $uri->getSld() );
        $this->assertSame( "com", $uri->getTld() );
        $this->assertSame( "example.com", $uri->getDomain() );


        $this->assertSame( $uri, $uri->setDomain("sub.test.unit.net") );
        $this->assertTrue( $uri->tldExists() );
        $this->assertTrue( $uri->sldExists() );
        $this->assertTrue( $uri->domainExists() );
        $this->assertFalse( $uri->subdomainExists() );
        $this->assertNull( $uri->getSubdomain() );
        $this->assertSame( "unit", $uri->getSld() );
        $this->assertSame( "net", $uri->getTld() );
        $this->assertSame( "unit.net", $uri->getDomain() );


        $this->assertSame( $uri, $uri->setDomain("..") );
        $this->assertFalse( $uri->tldExists() );
        $this->assertFalse( $uri->sldExists() );
        $this->assertFalse( $uri->subdomainExists() );
        $this->assertFalse( $uri->domainExists() );
        $this->assertNull( $uri->getSubdomain() );
        $this->assertNull( $uri->getSld() );
        $this->assertNull( $uri->getTld() );
        $this->assertNull( $uri->getDomain() );


        $uri->setTld( "com" );
        $this->assertTrue( $uri->tldExists() );
        $this->assertFalse( $uri->sldExists() );
        $this->assertFalse( $uri->subdomainExists() );
        $this->assertFalse( $uri->domainExists() );
        $this->assertNull( $uri->getSubdomain() );
        $this->assertNull( $uri->getSld() );
        $this->assertSame( "com", $uri->getTld() );
        $this->assertNull( $uri->getDomain() );
    }

    public function testIsSameDomain_withEnv ()
    {
        $uri = $this->getMock("cPHP::URL", array("getEnv"));
        $uri->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    Stub_Env::fromArray(array('HTTP_HOST' => 'sub.example.edu'))
                ));

        // Since neither the SLD or TLD are set, this defaults to the current domain
        $this->assertFalse( $uri->isSameDomain() );

        // sld: null, tld: com
        $uri->setTld('com');
        $this->assertFalse( $uri->isSameDomain() );

        // sld: null, tld: edu
        $uri->setTld('edu');
        $this->assertFalse( $uri->isSameDomain() );

        // sld: notthedomain, tld: edu
        $uri->setSld('notthedomain');
        $this->assertFalse( $uri->isSameDomain() );

        // sld: example, tld: edu
        $uri->setSld('example');
        $this->assertTrue( $uri->isSameDomain() );

        // sld: example, tld: com
        $uri->setTld('com');
        $this->assertFalse( $uri->isSameDomain() );

        // sld: example, tld: null
        $uri->clearTld();
        $this->assertFalse( $uri->isSameDomain() );

        // sld: notthedomain, tld: null
        $uri->setSld('notthedomain');
        $this->assertFalse( $uri->isSameDomain() );

        // sld: null, tld: null
        $uri->clearSld();
        $this->assertFalse( $uri->isSameDomain() );
    }

    public function testIsSameDomain_noEnv ()
    {
        $uri = $this->getMock("cPHP::URL", array("getEnv"));
        $uri->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    Stub_Env::fromArray(array())
                ));

        // Since neither the SLD or TLD are set, this defaults to the current domain
        $this->assertFalse( $uri->isSameDomain() );

        // sld: null, tld: com
        $uri->setTld('com');
        $this->assertFalse( $uri->isSameDomain() );

        // sld: example, tld: com
        $uri->setSld('example');
        $this->assertFalse( $uri->isSameDomain() );

        // sld: example, tld: null
        $uri->clearTld();
        $this->assertFalse( $uri->isSameDomain() );

        // sld: null, tld: null
        $uri->clearSld();
        $this->assertFalse( $uri->isSameDomain() );
    }

    public function testHostAccessors ()
    {
        $uri = new cPHP::URL;

        $this->assertNull( $uri->getHost() );
        $this->assertFalse( $uri->hostExists() );


        $uri->setTld( "com" );

        $this->assertTrue( $uri->tldExists() );
        $this->assertFalse( $uri->sldExists() );
        $this->assertFalse( $uri->subdomainExists() );
        $this->assertFalse( $uri->hostExists() );

        $this->assertNull( $uri->getSubdomain() );
        $this->assertNull( $uri->getSld() );
        $this->assertSame( "com", $uri->getTld() );
        $this->assertNull( $uri->getHost() );


        $uri->clearTld();
        $uri->setSld( "test" );

        $this->assertFalse( $uri->tldExists() );
        $this->assertTrue( $uri->sldExists() );
        $this->assertFalse( $uri->subdomainExists() );
        $this->assertFalse( $uri->hostExists() );

        $this->assertNull( $uri->getSubdomain() );
        $this->assertSame( "test", $uri->getSld() );
        $this->assertNull( $uri->getTld() );
        $this->assertSame( "test", $uri->getHost() );


        $this->assertSame( $uri, $uri->setHost("example.com") );

        $this->assertTrue( $uri->tldExists() );
        $this->assertTrue( $uri->sldExists() );
        $this->assertFalse( $uri->subdomainExists() );
        $this->assertTrue( $uri->hostExists() );

        $this->assertNull( $uri->getSubdomain() );
        $this->assertSame( "example", $uri->getSld() );
        $this->assertSame( "com", $uri->getTld() );
        $this->assertSame( "example.com", $uri->getHost() );


        $this->assertSame( $uri, $uri->setHost("sub.sub.example.com") );

        $this->assertTrue( $uri->tldExists() );
        $this->assertTrue( $uri->sldExists() );
        $this->assertTrue( $uri->subdomainExists() );
        $this->assertTrue( $uri->hostExists() );

        $this->assertSame( "sub.sub", $uri->getSubdomain() );
        $this->assertSame( "example", $uri->getSld() );
        $this->assertSame( "com", $uri->getTld() );
        $this->assertSame( "sub.sub.example.com", $uri->getHost() );
    }

    public function testIsSameHost_withSub ()
    {
        $uri = $this->getMock("cPHP::URL", array("getEnv"));
        $uri->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    Stub_Env::fromArray(array('HTTP_HOST' => 'sub.example.edu'))
                ));

        // Since neither the SLD or TLD are set, this defaults to the current domain
        $this->assertFalse( $uri->isSameHost() );

        $uri->setDomain("notTheDomain.com");
        $this->assertFalse( $uri->isSameHost() );

        $uri->setDomain("example.edu");
        $this->assertFalse( $uri->isSameHost() );

        $uri->setSubdomain("sub");
        $this->assertTrue( $uri->isSameHost() );

        $uri->setSubdomain("other");
        $this->assertFalse( $uri->isSameHost() );
    }

    public function testIsSameHost_wwwSub ()
    {
        $uri = $this->getMock("cPHP::URL", array("getEnv"));
        $uri->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    Stub_Env::fromArray(array('HTTP_HOST' => 'www.example.edu'))
                ));

        // Since neither the SLD or TLD are set, this defaults to the current domain
        $this->assertFalse( $uri->isSameHost() );

        $uri->setDomain("example.edu");
        $this->assertTrue( $uri->isSameHost() );

        $uri->setSubdomain("sub");
        $this->assertFalse( $uri->isSameHost() );

        $uri->setSubdomain("www");
        $this->assertTrue( $uri->isSameHost() );
    }

    public function testIsSameHost_noSub ()
    {
        $uri = $this->getMock("cPHP::URL", array("getEnv"));
        $uri->expects( $this->any() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    Stub_Env::fromArray(array('HTTP_HOST' => 'example.edu'))
                ));

        // Since neither the SLD or TLD are set, this defaults to the current domain
        $this->assertFalse( $uri->isSameHost() );

        $uri->setDomain("example.edu");
        $this->assertTrue( $uri->isSameHost() );

        $uri->setSubdomain("sub");
        $this->assertFalse( $uri->isSameHost() );

        $uri->setSubdomain("www");
        $this->assertTrue( $uri->isSameHost() );
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

}

?>