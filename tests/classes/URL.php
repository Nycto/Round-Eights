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

    public function testGetScheme_fromEnv ()
    {
        $uri = $this->getMock("cPHP::URL", array("getEnv"));
        $uri->expects( $this->once() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    Stub_Env::fromArray(array("SERVER_PROTOCOL" => "HTTT/1.1"))
                ));

        $this->assertSame( "httt", $uri->getScheme() );
    }

    public function testGetScheme_noEnv ()
    {
        $uri = $this->getMock("cPHP::URL", array("getEnv"));
        $uri->expects( $this->once() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    Stub_Env::fromArray(array())
                ));

        $this->assertSame( "http", $uri->getScheme() );
    }

    public function testSchemeAccessors()
    {
        $uri = new cPHP::URL;

        $this->assertFalse( $uri->schemeExists() );

        $this->assertSame( $uri, $uri->setScheme("ftp") );
        $this->assertSame( "ftp", $uri->getScheme() );
        $this->assertTrue( $uri->schemeExists() );

        $this->assertSame( $uri, $uri->setScheme("") );
        $this->assertFalse( $uri->schemeExists() );

        $this->assertSame( $uri, $uri->setScheme("  S F T P !@#$ 1") );
        $this->assertSame( "sftp1", $uri->getScheme() );
        $this->assertTrue( $uri->schemeExists() );

        $this->assertSame( $uri, $uri->clearScheme() );
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
        $this->assertFalse( $uri->domainExists() );

        $this->assertSame( $uri, $uri->setDomain("example") );
        $this->assertFalse( $uri->tldExists() );
        $this->assertTrue( $uri->sldExists() );
        $this->assertFalse( $uri->domainExists() );
        $this->assertSame( "example", $uri->getSld() );

        $this->assertSame( $uri, $uri->setDomain("example.com") );
        $this->assertTrue( $uri->tldExists() );
        $this->assertTrue( $uri->sldExists() );
        $this->assertTrue( $uri->domainExists() );
        $this->assertSame( "example", $uri->getSld() );
        $this->assertSame( "com", $uri->getTld() );

        $this->assertSame( $uri, $uri->setDomain("sub.test.unit.net") );
        $this->assertTrue( $uri->tldExists() );
        $this->assertTrue( $uri->sldExists() );
        $this->assertTrue( $uri->domainExists() );
        $this->assertSame( "unit", $uri->getSld() );
        $this->assertSame( "net", $uri->getTld() );

        $this->assertSame( $uri, $uri->setDomain("..") );
        $this->assertFalse( $uri->tldExists() );
        $this->assertFalse( $uri->sldExists() );
        $this->assertFalse( $uri->domainExists() );
    }

    public function testGetDomain_fromInstance ()
    {
        $uri = $this->getMock("cPHP::URL", array("getEnv"));
        $uri->expects( $this->once() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    Stub_Env::fromArray(array())
                ));

        $uri->setTld("com");
        $uri->setSld("example");
        $this->assertSame( "example.com", $uri->getDomain() );
    }

    public function testGetDomain_fromEnv ()
    {
        $uri = $this->getMock("cPHP::URL", array("getEnv"));
        $uri->expects( $this->once() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    Stub_Env::fromArray(array('HTTP_HOST' => 'example.edu'))
                ));

        $this->assertSame( "example.edu", $uri->getDomain() );
    }

    public function testGetDomain_noEnv ()
    {
        $uri = $this->getMock("cPHP::URL", array("getEnv"));
        $uri->expects( $this->once() )
            ->method("getEnv")
            ->will( $this->returnValue(
                    Stub_Env::fromArray(array())
                ));

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
        $this->assertTrue( $uri->isSameDomain() );

        // sld: null, tld: com
        $uri->setTld('com');
        $this->assertFalse( $uri->isSameDomain() );

        // sld: null, tld: edu
        $uri->setTld('edu');
        $this->assertTrue( $uri->isSameDomain() );

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
        $this->assertTrue( $uri->isSameDomain() );

        // sld: notthedomain, tld: null
        $uri->setSld('notthedomain');
        $this->assertFalse( $uri->isSameDomain() );

        // sld: null, tld: null
        $uri->clearSld();
        $this->assertTrue( $uri->isSameDomain() );
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
        $this->assertTrue( $uri->isSameDomain() );

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
        $this->assertTrue( $uri->isSameDomain() );
    }

}

?>