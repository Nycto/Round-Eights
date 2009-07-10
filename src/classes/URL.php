<?php
/**
 * URL object
 *
 * @license Artistic License 2.0
 *
 * This file is part of RaindropPHP.
 *
 * RaindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * RaindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with RaindropPHP. If not, see <http://www.RaindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@Raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Tag
 */

namespace h2o;

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
     * The faux directories of this link
     */
    private $fauxDir;

    /**
     * Query variables for this link
     */
    private $query;

    /**
     * Fragment for this link
     */
    private $fragment;

    /**
     * Returns a new URL instance with the base pulled from the environment
     *
     * @return Object Returns a \h2o\URL object
     */
    static public function fromBase ()
    {
        $url = new self;
        $url->fillBase();
        return $url;
    }

    /**
     * Returns a new URL instance with the entire URL pulled from the environment
     *
     * @return Object Returns a \h2o\URL object
     */
    static public function fromURL ()
    {
        $url = new self;
        $url->fillURL();
        return $url;
    }

    /**
     * Constructor...
     *
     * @param String $url The initial URL for this instance
     */
    public function __construct ( $url = null )
    {
        if ( $url instanceof self ) {
            $this->setScheme( $url->getScheme() );
            $this->setUsername( $url->getUserName() );
            $this->setPassword( $url->getPassword() );
            $this->setHost( $url->getHost() );
            $this->setDir( $url->getDir() );
            $this->setFilename( $url->getFileName() );
            $this->setExt( $url->getExt() );
            $this->setQuery( $url->getQuery() );
            $this->setFragment( $url->getFragment() );
        }
        else if ( !\h2o\isVague($url) ) {
            $this->setURL( \h2o\strval($url) );
        }
    }

    /**
     * Returns a string representation of this URL
     *
     * @return String
     */
    public function __toString ()
    {
        return strval( $this->getURL() );
    }

    /**
     * Returns the singelton Env instance
     *
     * This method exists strictly for unit testing purposes. By mocking this
     * method you can feed a spoof environment to the rest of the instance
     *
     * @return Object Returns a \h2o\Env instance
     */
    protected function getEnv ()
    {
        return \h2o\Env::Request();
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
        $scheme = strtolower( \h2o\str\stripW($scheme) );
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

        $link = $this->getEnv()->getURL();

        if ( !$link->schemeExists() )
            return FALSE;

        return strcasecmp( $link->getScheme(), $this->scheme ) == 0 ? TRUE : FALSE;
    }

    /**
     * Sets the scheme in this instance from the environment
     *
     * @return Object Returns a self reference
     */
    public function fillScheme ()
    {
        $env = $this->getEnv();
        return $this->setScheme( $env->getURL()->getScheme() );
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
        $username = \h2o\strval( $username );
        $this->username = \h2o\isEmpty( $username ) ? null : $username;
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
        $password = \h2o\strval( $password );
        $this->password = \h2o\isEmpty( $password ) ? null : $password;
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
        $userInfo = \h2o\strVal( $userInfo );

        if ( \h2o\str\contains("@", $userInfo))
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
        $host = \h2o\strval($host);

        $host = preg_replace("/[^a-z0-9\.\-]/i", "", $host);

        $host = \h2o\str\stripRepeats($host, ".");
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

        $link = $this->getEnv()->getURL();

        if ( !$link->hostExists() )
            return FALSE;

        $localHost = \h2o\str\stripHead( $this->getHost(), "www." );
        $envHost = \h2o\str\stripHead( $link->getHost(), "www." );

        if ( strcasecmp($localHost, $envHost) == 0 )
            return TRUE;
        else
            return FALSE;
    }

    /**
     * Sets the host in this instance from the environment
     *
     * @return Object Returns a self reference
     */
    public function fillHost ()
    {
        $env = $this->getEnv();
        return $this->setHost( $env->getURL()->getHost() );
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

        $link = $this->getEnv()->getURL();

        return $link->getPort() == $port ? TRUE : FALSE;
    }

    /**
     * Returns whether the current port is the default for the scheme
     *
     * If no scheme is set, this will return FALSE. If no port is set, this will
     * return TRUE. For unknown ports, FALSE will be returned
     *
     * @return Boolean
     */
    public function isDefaultPort ()
    {
        if ( !$this->schemeExists() )
            return FALSE;

        if ( !$this->portExists() )
            return TRUE;

        switch ( $this->getScheme() ) {

            case 'http':
                $port = 80;
                break;

            case 'https':
                $port = 443;
                break;

            case 'ftp':
                $port = 21;
                break;

            case 'ftps':
                $port = 990;
                break;

            case 'sftp':
                $port = 115;
                break;

            case 'ldap':
                $port = 389;
                break;

            default:
                return FALSE;
        }

        return $this->port == $port ? TRUE : FALSE;
    }

    /**
     * Sets the port in this instance from the environment
     *
     * @return Object Returns a self reference
     */
    public function fillPort ()
    {
        $env = $this->getEnv();
        return $this->setPort( $env->getURL()->getPort() );
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
        $hostAndPort = \h2o\strval( $hostAndPort );

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
     * If the port is the default port for this scheme, it will not be included.
     *
     * @return String|NULL Returns the base of the URL. This will return null
     *      if the host isn't set.
     */
    public function getBase ()
    {
        if ( !$this->hostExists() )
            return null;

        if ( $this->isDefaultPort() )
            $result = $this->getHost();
        else
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
        $base = \h2o\strval( $base );

        if ( \h2o\str\contains("://", $base) ) {
            $this->setScheme( strstr($base, "://", TRUE) );
            $base = substr( strstr($base, "://", FALSE), 3 );
        }
        else {
            $this->clearScheme();
        }

        if ( \h2o\str\contains("@", $base) ) {
            $this->setUserInfo( strstr($base, "@", TRUE) );
            $base = substr( strstr($base, "@", FALSE), 1 );
        }
        else {
            $this->clearUserInfo();
        }

        $this->setHostAndPort( $base );

        return $this;
    }

    /**
     * Returns whether the Scheme, host and port are the same as the current environment
     *
     * Note that this method ignores the userinfo.
     *
     * @return Boolean
     */
    public function isSameBase ()
    {
        return $this->isSameHost() && $this->isSameScheme() && $this->isSamePort();
    }

    /**
     * Sets the scheme, host and port in this instance from the environment
     *
     * @return Object Returns a self reference
     */
    public function fillBase ()
    {
        return $this->fillScheme()->fillHost()->fillPort();
    }

    /**
     * Returns the value of the directory
     *
     * @return String|Null Returns null if the directory isn't set
     */
    public function getDir ()
    {
        return $this->directory;
    }

    /**
     * Sets the directory
     *
     * @param String $directory The directory to set
     * @return Object Returns a self reference
     */
    public function setDir ( $directory )
    {
        $directory = \h2o\strval( $directory );

        if ( \h2o\isEmpty( $directory ) ) {
            $this->directory = null;
        }
        else {
            $directory = \h2o\FileSys::resolvePath( $directory );
            $directory = \h2o\str\enclose( $directory, "/" );
            $this->directory = $directory;
        }

        return $this;
    }

    /**
     * Returns whether the directory has been set
     *
     * @return Boolean
     */
    public function dirExists ()
    {
        return isset( $this->directory );
    }

    /**
     * Unsets the currently set directory
     *
     * @return Object Returns a self reference
     */
    public function clearDir ()
    {
        $this->directory = null;
        return $this;
    }

    /**
     * Returns the filename, if there is one
     *
     * @return String|Null Returns null if no filename has been set
     */
    public function getFilename ()
    {
        return $this->filename;
    }

    /**
     * Sets the filename
     *
     * @param String $filename The new filename
     * @return Object Returns a self reference
     */
    public function setFilename ( $filename )
    {
        $filename = trim(\h2o\strval( $filename ));
        $filename = rtrim( $filename, "." );
        $this->filename = \h2o\isEmpty( $filename ) ? null : $filename;
        return $this;
    }

    /**
     * Returns whether a filename has been set
     *
     * @return Boolean
     */
    public function filenameExists ()
    {
        return isset( $this->filename );
    }

    /**
     * Clears the filename
     *
     * @return Object Returns a self reference
     */
    public function clearFilename ()
    {
        $this->filename = null;
        return $this;
    }

    /**
     * Returns the extension, if there is one,
     *
     * The extension will be returned without a leading period
     *
     * @return String|Null Returns null if no extension has been set
     */
    public function getExt ()
    {
        return $this->extension;
    }

    /**
     * Sets the extension
     *
     * @param String $extension The new extension
     * @return Object Returns a self reference
     */
    public function setExt ( $extension )
    {
        $extension = trim(\h2o\strval( $extension ));
        $extension = ltrim( $extension, "." );
        $this->extension = \h2o\isEmpty( $extension ) ? null : $extension;
        return $this;
    }

    /**
     * Returns whether an extension has been set
     *
     * @return Boolean
     */
    public function extExists ()
    {
        return isset( $this->extension );
    }

    /**
     * Clears the extension
     *
     * @return Object Returns a self reference
     */
    public function clearExt ()
    {
        $this->extension = null;
        return $this;
    }

    /**
     * Returns the basename
     *
     * The basename is the combined filename and extension. If no filename
     * has been set, this will always return null.
     *
     * @return String|Null Returns null if no filename has been set
     */
    public function getBasename ()
    {
        if ( !$this->filenameExists() )
            return null;

        if ( !$this->extExists() )
            return $this->getFilename();

        return \h2o\str\weld(
                $this->getFilename(),
                $this->getExt(),
                "."
            );
    }

    /**
     * Sets the basename, which is the filename and extension
     *
     * @param String $basename The new basename
     * @return Object Returns a self reference
     */
    public function setBasename ( $basename )
    {
        $basename = trim(\h2o\strval( $basename ));
        $basename = pathinfo( $basename );

        if ( isset($basename['filename']) )
            $this->setFilename($basename['filename']);
        else
            $this->clearFilename();

        if ( isset($basename['extension']) )
            $this->setExt($basename['extension']);
        else
            $this->clearExt();

        return $this;
    }

    /**
     * Returns the path represented by this instance
     *
     * @return String The full path
     */
    public function getPath ()
    {
        if ( !$this->dirExists() && !$this->filenameExists() )
            return null;

        return
            ( $this->dirExists() ? $this->getDir() : "" )
            .$this->getBasename();
    }

    /**
     * Sets the path that this instance represents
     *
     * @param String $path The new path
     * @return Object Returns a self reference
     */
    public function setPath ( $path )
    {
        $path = trim(\h2o\strval( $path ));
        $path = pathinfo( $path );

        if ( isset($path['dirname']) )
            $this->setDir($path['dirname']);
        else
            $this->clearDir();

        if ( isset($path['filename']) )
            $this->setFilename($path['filename']);
        else
            $this->clearFilename();

        if ( isset($path['extension']) )
            $this->setExt($path['extension']);
        else
            $this->clearExt();

        return $this;
    }

    /**
     * Clears the directory, filename and extension at once
     *
     * @return Object Returns a self reference
     */
    public function clearPath ()
    {
        $this->directory = null;
        $this->filename = null;
        $this->extension = null;
        return $this;
    }

    /**
     * Returns whether a path exists in this instance
     *
     * This requires a directory or a filename
     *
     * @return Boolean
     */
    public function pathExists ()
    {
        return $this->dirExists() || $this->filenameExists();
    }

    /**
     * Fills the path from the environment
     *
     * @return Object Returns a self reference
     */
    public function fillPath ()
    {
        $env = $this->getEnv();
        $this->setPath( $env->getURL()->getPath() );
        return $this;
    }

    /**
     * Returns the value of the fauxDir
     *
     * @return String|Null Returns null if the fauxDir isn't set
     */
    public function getFauxDir ()
    {
        return $this->fauxDir;
    }

    /**
     * Sets the fauxDir
     *
     * @param String $fauxDir The fauxDir to set
     * @return Object Returns a self reference
     */
    public function setFauxDir ( $fauxDir )
    {
        $fauxDir = \h2o\strval( $fauxDir );
        $this->fauxDir = \h2o\isEmpty( $fauxDir )
            ? null : \h2o\str\head($fauxDir, "/");
        return $this;
    }

    /**
     * Returns whether the fauxDir has been set
     *
     * @return Boolean
     */
    public function fauxDirExists ()
    {
        return isset( $this->fauxDir );
    }

    /**
     * Unsets the currently set fauxDir
     *
     * @return Object Returns a self reference
     */
    public function clearFauxDir ()
    {
        $this->fauxDir = null;
        return $this;
    }

    /**
     * Fills the faux directories from the environment
     *
     * @return Object Returns a self reference
     */
    public function fillFauxDir ()
    {
        $env = $this->getEnv();
        $this->setFauxDir( $env->getURL()->getFauxDir() );
        return $this;
    }

    /**
     * Returns the query, if there is one
     *
     * @return String|Null Returns null if no query has been set
     */
    public function getQuery ()
    {
        return $this->query;
    }

    /**
     * Returns the query parsed in to an array
     *
     * @return array
     */
    public function getParsedQuery ()
    {
        $result = null;
        parse_str( $this->query, $result );
        return $result;
    }

    /**
     * Sets the query
     *
     * @param mixed $query The new query. If given a string, this will set the
     *      query to that string without encoding it or changing it in any way. If
     *      given an array or an iterable object, it will be collapsed in to a query
     *      string.
     * @return Object Returns a self reference
     */
    public function setQuery ( $query )
    {
        if ( is_array($query) )
            $query = http_build_query( $query );

        $query = \h2o\strval( $query );

        if ( \h2o\isEmpty($query, \h2o\ALLOW_SPACES) )
            $this->query = null;
        else
            $this->query = $query;

        return $this;
    }

    /**
     * Returns whether an query has been set
     *
     * @return Boolean
     */
    public function queryExists ()
    {
        return isset( $this->query );
    }

    /**
     * Clears the query
     *
     * @return Object Returns a self reference
     */
    public function clearQuery ()
    {
        $this->query = null;
        return $this;
    }

    /**
     * Fills the faux directories from the environment
     *
     * @return Object Returns a self reference
     */
    public function fillQuery ()
    {
        $env = $this->getEnv();
        $this->setQuery( $env->getURL()->getQuery() );
        return $this;
    }

    /**
     * Returns the fragment, if there is one
     *
     * @return String|Null Returns null if no fragment has been set
     */
    public function getFragment ()
    {
        return $this->fragment;
    }

    /**
     * Sets the fragment
     *
     * @param String $fragment The new fragment
     * @return Object Returns a self reference
     */
    public function setFragment ( $fragment )
    {
        $fragment = \h2o\strval( $fragment );
        $this->fragment = \h2o\isEmpty( $fragment, \h2o\ALLOW_SPACES ) ? null : $fragment;
        return $this;
    }

    /**
     * Returns whether a fragment has been set
     *
     * @return Boolean
     */
    public function fragmentExists ()
    {
        return isset( $this->fragment );
    }

    /**
     * Clears the fragment
     *
     * @return Object Returns a self reference
     */
    public function clearFragment ()
    {
        $this->fragment = null;
        return $this;
    }

    /**
     * Returns a relative URL
     *
     * This is a combination of the path, query and fragment
     *
     * @return String|Null This will return NULL if no path, query or fragment has been set
     */
    public function getRelative ()
    {
        if ( $this->pathExists() ) {

            $result = $this->getPath();

            // Only add the faux directories if there is a path
            if ( $this->fauxDirExists() )
                $result .= $this->getFauxDir();

        }
        else {
            $result = "";
        }

        if ( $this->queryExists() )
            $result .= "?". $this->getQuery();

        if ( $this->fragmentExists() )
            $result .= "#". $this->getFragment();

        return \h2o\isEmpty( $result, \h2o\ALLOW_SPACES ) ? null : $result;
    }

    /**
     * Returns the full URL contained in this instance
     *
     * @return String
     */
    public function getURL ()
    {
        $url = $this->getBase() . $this->getRelative();

        return \h2o\isEmpty( $url, \h2o\ALLOW_SPACES ) ? null : $url;
    }

    /**
     * Sets the entire URL at once
     *
     * @param String $url The URL string
     * @return Object Returns a self reference
     */
    public function setURL ( $url )
    {
        $url = \h2o\strval($url);

        $parsed = parse_url( $url );

        if ( isset($parsed["scheme"]) )
            $this->setScheme( $parsed['scheme'] );
        else
            $this->clearScheme();

        if ( isset($parsed["user"]) )
            $this->setUserName( $parsed['user'] );
        else
            $this->clearUserName();

        if ( isset($parsed["pass"]) )
            $this->setPassword( $parsed['pass'] );
        else
            $this->clearPassword();

        if ( isset($parsed["host"]) )
            $this->setHost( $parsed['host'] );
        else
            $this->clearHost();

        if ( isset($parsed["port"]) )
            $this->setPort( $parsed['port'] );
        else
            $this->clearPort();

        if ( isset($parsed["path"]) )
            $this->setPath( $parsed['path'] );
        else
            $this->clearPath();

        if ( isset($parsed["query"] ) )
            $this->setQuery( $parsed['query'] );
        else
            $this->clearQuery();

        if ( isset($parsed["fragment"] ) )
            $this->setFragment( $parsed['fragment'] );
        else
            $this->clearFragment();

        return $this;
    }

    /**
     * Clears all the values from this instance
     *
     * @return Object Returns a self reference
     */
    public function clearURL ()
    {
        $this->scheme = null;
        $this->username = null;
        $this->password = null;
        $this->host = null;
        $this->directory = null;
        $this->filename = null;
        $this->extension = null;
        $this->fauxDir = null;
        $this->query = null;
        $this->fragment = null;

        return $this;
    }

    /**
     * Sets the entire URL from the source
     *
     * @return Object Returns a self reference
     */
    public function fillURL ()
    {
        return $this->fillBase()
            ->fillPath()
            ->fillFauxDir()
            ->fillQuery();
    }

}

?>