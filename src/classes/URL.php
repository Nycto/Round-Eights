<?php
/**
 * URL object
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
 * @package Tag
 */

namespace cPHP;

/**
 * Class for parsing, manipulating and outputting URLs
 */
class URL
{

    /**
     * The protocol for this link
     */
    private $scheme;

    /**
     * The username for this URL
     */
    private $username;

    /**
     * The password in this URL
     */
    private $password;

    /**
     * The subdomain for a link
     */
    private $subdomain;

    /**
     * The second level domain name for a link.
     *
     * This is the chunk just to the left of the TLD. For example, in
     * "www.example.com", "example" is the sld.
     */
    private $sld;

    /**
     * The TLD (top level domain) for a link. For example: com, net, org, or gov.
     */
    private $tld;

    /**
     * The port for a link.
     *
     * If it isn't set as an integer, it won't be used
     */
    private $port;

    /**
     * The directory path for a link
     */
    private $directory;

    /**
     * The filename for a link
     */
    private $filename;

    /**
     * The extension of the filename for this path
     */
    private $extension;

    /**
     * Query variables for this link
     */
    private $query = Array();

    /**
     * Fragment for this link
     */
    private $fragment;

    /**
     * Returns the singelton Env instance
     *
     * This method exists strictly for unit testing purposes. By mocking this
     * method you can feed a spoof environment to the rest of the instance
     *
     * @return Object Returns a ::cPHP::Env instance
     */
    protected function getEnv ()
    {
        return Env::get();
    }

    /**
     * Returns the scheme for this instance
     *
     * If no specific scheme has been set, it will return the scheme useed to
     * fetch the current page. Failing that (for example, running via the command
     * line), the default is "http"
     *
     * @return String Returns the scheme for this link
     */
    public function getScheme ()
    {
        return $this->scheme;
    }

    /**
     * Sets the scheme for this instance
     *
     * @param String $scheme
     * @return Object Returns a self reference
     */
    public function setScheme ( $scheme )
    {
        $scheme = strtolower( ::cPHP::str::stripW($scheme) );
        $this->scheme = empty( $scheme ) ? null : $scheme;
        return $this;
    }

    /**
     * Removes the explicitly set scheme, causing the scheme to revert to the default
     *
     * @return Object Returns a self reference
     */
    public function clearScheme ()
    {
        $this->scheme = null;
        return $this;
    }

    /**
     * Returns whether a scheme has been explicitly set
     *
     * @return Boolean
     */
    public function schemeExists ()
    {
        return isset($this->scheme);
    }

    /**
     * Returns the value of the username
     *
     * @return String|Null Returns null if the username isn't set
     */
    public function getUserName ()
    {
        return $this->username;
    }

    /**
     * Sets the username credential
     *
     * @param String $username The username to set
     * @return Object Returns a self reference
     */
    public function setUserName ( $username )
    {
        $username = ::cPHP::strval( $username );
        $this->username = ::cPHP::isEmpty( $username ) ? null : $username;
        return $this;
    }

    /**
     * Returns whether the username has been set
     *
     * @return Boolean
     */
    public function userNameExists ()
    {
        return isset( $this->username );
    }

    /**
     * Unsets the currently set username
     *
     * @return Object Returns a self reference
     */
    public function clearUserName ()
    {
        $this->username = null;
        return $this;
    }

    /**
     * Returns the value of the password
     *
     * @return String|Null Returns null if the password isn't set
     */
    public function getPassword ()
    {
        return $this->password;
    }

    /**
     * Sets the password credential
     *
     * @param String $password The password to set
     * @return Object Returns a self reference
     */
    public function setPassword ( $password )
    {
        $password = ::cPHP::strval( $password );
        $this->password = ::cPHP::isEmpty( $password ) ? null : $password;
        return $this;
    }

    /**
     * Returns whether the password has been set
     *
     * @return Boolean
     */
    public function passwordExists ()
    {
        return isset( $this->password );
    }

    /**
     * Unsets the currently set password
     *
     * @return Object Returns a self reference
     */
    public function clearPassword ()
    {
        $this->password = null;
        return $this;
    }

    /**
     * Returns the UserInfo for this link
     *
     * The UserInfo is the username and password combined with a semicolon in between.
     * If only the username is set, it will be returned. If only the password is
     * set, nothing will be returned.
     *
     * The value this returns is urlencoded
     *
     * @return String|Null Returns null if the neither the username isnt set
     */
    public function getUserInfo ()
    {
        if ( !$this->userNameExists() )
            return null;

        if ( $this->passwordExists() )
            return urlencode( $this->getUsername() ) .":". urlencode( $this->getPassword() );

        return urlencode( $this->getUsername() );
    }

    /**
     * Sets both the username and password in one swoop
     *
     * @param String $userInfo The credentials being set
     * @return Object Returns a self reference
     */
    public function setUserInfo ( $userInfo )
    {
        $userInfo = ::cPHP::strVal( $userInfo );

        if ( ::cPHP::str::contains("@", $userInfo))
            $userInfo = strstr( $userInfo, "@", TRUE );

        $userInfo = explode(":", $userInfo, 2);

        $this->setUserName( urldecode($userInfo[0]) );

        if ( isset($userInfo[1]) )
            $this->setPassword( urldecode($userInfo[1]) );
        else
            $this->clearPassword();

        return $this;
    }

    /**
     * Returns whether the userinfo has been set
     *
     * This will always return true if the username has been set
     *
     * @return Boolean
     */
    public function userInfoExists ()
    {
        return $this->userNameExists();
    }

    /**
     * Unsets both the password and the username
     *
     * @return Object Returns a self reference
     */
    public function clearUserInfo ()
    {
        $this->username = null;
        $this->password = null;
        return $this;
    }

    /**
     * Returns the value of the subdomain
     *
     * @return String|Null Returns null if the subdomain isn't set
     */
    public function getSubdomain ()
    {
        return $this->subdomain;
    }

    /**
     * Sets the subdomain credential
     *
     * @param String $subdomain The subdomain to set
     * @return Object Returns a self reference
     */
    public function setSubdomain ( $subdomain )
    {
        $subdomain = preg_replace('/[^a-z0-9\.\-]/i', '', $subdomain);
        $subdomain = trim($subdomain, ".");
        $subdomain = ::cPHP::str::stripRepeats($subdomain, ".");

        $this->subdomain = empty( $subdomain ) ? null : $subdomain;
        return $this;
    }

    /**
     * Returns whether the subdomain has been set
     *
     * @return Boolean
     */
    public function subdomainExists ()
    {
        return isset( $this->subdomain );
    }

    /**
     * Unsets the currently set subdomain
     *
     * @return Object Returns a self reference
     */
    public function clearSubdomain ()
    {
        $this->subdomain = null;
        return $this;
    }

    /**
     * Returns the value of the second level domain
     *
     * @return String|Null Returns null if the sld isn't set
     */
    public function getSld ()
    {
        return $this->sld;
    }

    /**
     * Sets the second level domain
     *
     * @param String $sld The sld to set
     * @return Object Returns a self reference
     */
    public function setSld ( $sld )
    {
        $sld = ::cPHP::str::stripW( $sld, ::cPHP::str::ALLOW_DASHES );
        $this->sld = empty( $sld ) ? null : $sld;
        return $this;
    }

    /**
     * Returns whether the second level domain has been set
     *
     * @return Boolean
     */
    public function sldExists ()
    {
        return isset( $this->sld );
    }

    /**
     * Unsets the current second level domain
     *
     * @return Object Returns a self reference
     */
    public function clearSld ()
    {
        $this->sld = null;
        return $this;
    }

    /**
     * Returns the value of the top level domain
     *
     * @return String|Null Returns null if the tld isn't set
     */
    public function getTld ()
    {
        return $this->tld;
    }

    /**
     * Sets the top level domain
     *
     * @param String $tld The tld to set
     * @return Object Returns a self reference
     */
    public function setTld ( $tld )
    {
        $tld = ::cPHP::str::stripW( $tld );
        $this->tld = empty( $tld ) ? null : $tld;
        return $this;
    }

    /**
     * Returns whether the top level domain has been set
     *
     * @return Boolean
     */
    public function tldExists ()
    {
        return isset( $this->tld );
    }

    /**
     * Unsets the current top level domain
     *
     * @return Object Returns a self reference
     */
    public function clearTld ()
    {
        $this->tld = null;
        return $this;
    }

    /**
     * Returns the Domain for this link
     *
     * This is the top level domain combined with the second level domain. If no
     * SLD is set, it will pull the SLD from the current environment. If there is
     * no SLD in the environment (command line mode, for example), then the function
     * will return null.
     *
     * The tld will also be pulled from the environment if one has not been
     * explicitly set, and will then fall back on being ".com"
     *
     * @return String|Null
     */
    public function getDomain ()
    {
        $env = $this->getEnv();

        if ( isset($this->sld) )
            $sld = $this->sld;
        else if ( isset($env->sld) )
            $sld = $env->sld;
        else
            return null;

        if ( isset($this->tld) )
            $tld = $this->tld;
        else if ( isset($env->tld) )
            $tld = $env->tld;
        else
            $tld = "com";

        return $sld .".". $tld;
    }

    /**
     * Sets both the tld and sld in one swoop
     *
     * @param String $domain The domain being set
     * @return Object Returns a self reference
     */
    public function setDomain ( $domain )
    {
        $domain = preg_replace('/[^a-z0-9\.\-]/i', '', $domain);
        $domain = trim($domain, ".");
        $domain = ::cPHP::str::stripRepeats($domain, ".");

        if ( empty($domain) ) {
            $this->tld = NULL;
            $this->sld = NULL;
        }
        else if ( !::cPHP::str::contains(".", $domain) ) {
            $this->tld = NULL;
            $this->sld = $domain;
        }
        else {
            $domain = explode(".", $domain);
            $this->tld = array_pop($domain);
            $this->sld = array_pop($domain);
        }

        return $this;
    }

    /**
     * Returns whether the domain has been set
     *
     * This returns true if both the sld and tld are set
     *
     * @return Boolean
     */
    public function domainExists ()
    {
        return isset($this->sld) && isset($this->tld);
    }

    /**
     * Unsets both the sld and the tld
     *
     * @return Object Returns a self reference
     */
    public function clearDomain ()
    {
        $this->sld = null;
        $this->tld = null;
        return $this;
    }

    /**
     * Returns whether the domain information in this instance is the same as
     * the domain info in the environment
     *
     * Remember that if the TLD and SLD are not set for this instance, the default
     * value is the current domain
     *
     * @return Boolean
     */
    public function isSameDomain ()
    {
        $env = $this->getEnv();

        // If the tld is different
        if ( isset($this->tld) && strcasecmp( $env->tld, $this->tld ) != 0 )
            return FALSE;

        // If the sld is different
        if ( isset($this->sld) && strcasecmp( $env->sld, $this->sld ) != 0 )
            return FALSE;

        return TRUE;
    }

    /**
     * Returns the Host for this link
     *
     * This is a combination of the subdomain, sld and tld
     *
     * @return String|Null
     */
    public function getHost ()
    {
    }

    /**
     * Sets the subdomain, sld and tld
     *
     * @param String $host The host being set
     * @return Object Returns a self reference
     */
    public function setHost ( $host )
    {
        return $this;
    }

    /**
     * Returns whether the userinfo has been set
     *
     * This will always return true if the username has been set
     *
     * @return Boolean
     */
    public function hostExists ()
    {
        return isset($this->sld) && isset($this->tld);
    }

    /**
     * Unsets the tld, sld and subdomain
     *
     * @return Object Returns a self reference
     */
    public function clearHost ()
    {
        $this->sld = null;
        $this->tld = null;
        return $this;
    }

    /**
     * Returns whether the host information in this instance is the same as
     * the host info in the environment
     *
     * @return Boolean
     */
    public function isSameHost ()
    {
        $env = $this->getEnv();

        return TRUE;
    }

}

?>