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
     * The host of this url
     */
    private $host;

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
     * Returns whether the current scheme is the same as the current environment
     *
     * This will return false if the scheme hasn't been set in either the environment
     * or this instance
     *
     * @return Boolean
     */
    public function isSameScheme ()
    {
        if ( !isset($this->scheme) )
            return FALSE;

        $env = $this->getEnv();

        if ( !isset($env->scheme) )
            return FALSE;

        return strcasecmp( $env->scheme, $this->scheme ) == 0 ? TRUE : FALSE;
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
     * Returns the Host for this link
     *
     * @return String|Null Null will be returned if the domain has not been set
     */
    public function getHost ()
    {
        return $this->host;
    }

    /**
     * Sets the Host
     *
     * @param String $host The host being set
     * @return Object Returns a self reference
     */
    public function setHost ( $host )
    {
        $host = ::cPHP::strval($host);

        $host = preg_replace("/[^a-z0-9\.\-]/i", "", $host);

        $host = ::cPHP::str::stripRepeats($host, ".");
        $host = trim($host, ".");

        $this->host = empty($host) ? null : $host;

        return $this;
    }

    /**
     * Returns whether the host has been set
     *
     * This only requires that the tld and sld be set
     *
     * @return Boolean
     */
    public function hostExists ()
    {
        return isset( $this->host );
    }

    /**
     * Unsets the tld, sld and subdomain
     *
     * @return Object Returns a self reference
     */
    public function clearHost ()
    {
        $this->host = null;
        return $this;
    }

    /**
     * Returns whether the host information in this instance is the same as
     * the host info in the environment
     *
     * A null value in the subdomain will be treated as the same as "www"
     *
     * @return Boolean
     */
    public function isSameHost ()
    {
        if ( !$this->hostExists() )
            return FALSE;

        $env = $this->getEnv();

        if ( !isset($env->host) )
            return FALSE;

        $localHost = ::cPHP::str::stripHead( $this->getHost(), "www." );
        $envHost = ::cPHP::str::stripHead( $env->host, "www." );

        if ( strcasecmp($localHost, $envHost) == 0 )
            return TRUE;
        else
            return FALSE;
    }

    /**
     * Returns the value of the port
     *
     * @return String|Null Returns null if the port isn't set
     */
    public function getPort ()
    {
        return $this->port;
    }

    /**
     * Sets the Port
     *
     * @param String $port The port to set
     * @return Object Returns a self reference
     */
    public function setPort ( $port )
    {
        $port = intval($port);
        $this->port = $port <= 0 ? null : $port;
        return $this;
    }

    /**
     * Returns whether the port has been set
     *
     * @return Boolean
     */
    public function portExists ()
    {
        return isset( $this->port );
    }

    /**
     * Unsets the current port
     *
     * @return Object Returns a self reference
     */
    public function clearPort ()
    {
        $this->port = null;
        return $this;
    }

    /**
     * Returns whether the port in this instance is the same as the port in the environment
     *
     * If the port in the instance isn't set, it will be treated as port 80
     *
     * @return Boolean
     */
    public function isSamePort ()
    {
        $port = isset($this->port) ? $this->port : 80;

        $env = $this->getEnv();

        return $env->port == $port ? TRUE : FALSE;
    }

    /**
     * Returns the host and the port in one string
     *
     * @return String|Null This will return null the host isn't set
     */
    public function getHostAndPort ()
    {
        if ( !$this->hostExists() )
            return null;

        if ( $this->portExists() )
            return $this->host .":". $this->port;
        else
            return $this->host;
    }

    /**
     * Sets the host and the port in one swoop
     *
     * @param String $hostAndPort The host and port string
     * @return Object Returns a self reference
     */
    public function setHostAndPort ( $hostAndPort )
    {
        $hostAndPort = ::cPHP::strval( $hostAndPort );

        if ( preg_match('/(.+)\:([0-9]+)$/', $hostAndPort, $matches) ) {
            $this->setHost( $matches[1] );
            $this->setPort( $matches[2] );
        }
        else {
            $this->setHost( $hostAndPort );
            $this->clearPort();
        }

        return $this;
    }

    /**
     * Returns the scheme, the userinfo, the host and the port in one formatted string
     *
     * @return String|NULL Returns the base of the URL. This will return null
     *      if the host isn't set.
     */
    public function getBase ()
    {
        if ( !$this->hostExists() )
            return null;

        $result = $this->getHostAndPort();

        if ( $this->userInfoExists() )
            $result = $this->getUserInfo() ."@". $result;

        if ( $this->schemeExists() )
            $result = $this->getScheme() ."://". $result;

        return $result;
    }

    /**
     * Parses a string into the scheme, userinfo, host and port
     *
     * @param String $base The base of the url
     * @return Object Returns a self reference
     */
    public function setBase ( $base )
    {
        $base = ::cPHP::strval( $base );

        if ( cPHP::str::contains("://", $base) ) {
            $this->setScheme( strstr($base, "://", TRUE) );
            $base = substr( strstr($base, "://", FALSE), 3 );
        }
        else {
            $this->clearScheme();
        }

        if ( cPHP::str::contains("@", $base) ) {
            $this->setUserInfo( strstr($base, "@", TRUE) );
            $base = substr( strstr($base, "@", FALSE), 1 );
        }
        else {
            $this->clearUserInfo();
        }

        $this->setHostAndPort( $base );

        return $this;
    }

}

?>