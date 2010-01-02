<?php
/**
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
 * @package URL
 */

namespace r8;

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
     * Constructor...
     *
     * @param String $url The initial URL for this instance
     */
    public function __construct ( $url = null )
    {
        if ( $url instanceof self )
            $this->copyURL( $url );
        else if ( !\r8\isVague($url) )
            $this->setURL( (string) $url );
    }

    /**
     * Returns a string representation of this URL
     *
     * @return String
     */
    public function __toString ()
    {
        return (string) $this->getURL();
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
     * @return \r8\URL Returns a self reference
     */
    public function setScheme ( $scheme )
    {
        $scheme = strtolower( \r8\str\stripW($scheme) );
        $this->scheme = empty( $scheme ) ? null : $scheme;
        return $this;
    }

    /**
     * Removes the explicitly set scheme, causing the scheme to revert to the default
     *
     * @return \r8\URL Returns a self reference
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
     * Returns whether the scheme in this instance is the same as
     * that of another URL
     *
     * This will return false if the scheme hasn't been set in either
     * this or the other URL
     *
     * @param \r8\URL $versus The URL to compare to
     * @return Boolean
     */
    public function isSameScheme ( \r8\URL $versus )
    {
        if ( !isset($this->scheme) || !$versus->schemeExists() )
            return FALSE;

        return strcasecmp( $versus->getScheme(), $this->scheme ) == 0 ? TRUE : FALSE;
    }

    /**
     * Copies the scheme from another URL into this URL
     *
     * @param \r8\URL $source The source to copy from
     * @return \r8\URL Returns a self reference
     */
    public function copyScheme ( \r8\URL $source )
    {
        return $this->setScheme( $source->getScheme() );
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
     * @return \r8\URL Returns a self reference
     */
    public function setUserName ( $username )
    {
        $username = (string) $username;
        $this->username = \r8\isEmpty( $username ) ? null : $username;
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
     * @return \r8\URL Returns a self reference
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
     * @return \r8\URL Returns a self reference
     */
    public function setPassword ( $password )
    {
        $password = (string) $password;
        $this->password = \r8\isEmpty( $password ) ? null : $password;
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
     * @return \r8\URL Returns a self reference
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
     * @return \r8\URL Returns a self reference
     */
    public function setUserInfo ( $userInfo )
    {
        $userInfo = (string) $userInfo;

        if ( \r8\str\contains("@", $userInfo))
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
     * @return \r8\URL Returns a self reference
     */
    public function clearUserInfo ()
    {
        $this->username = null;
        $this->password = null;
        return $this;
    }

    /**
     * Sets the user info using another URL as a source
     *
     * @param \r8\URL $source The source to copy from
     * @return \r8\URL Returns a self reference
     */
    public function copyUserInfo ( \r8\URL $source )
    {
        $this->setUserName( $source->getUserName() );
        $this->setPassword( $source->getPassword() );
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
     * @return \r8\URL Returns a self reference
     */
    public function setHost ( $host )
    {
        $host = (string) $host;

        $host = preg_replace("/[^a-z0-9\.\-]/i", "", $host);

        $host = \r8\str\stripRepeats($host, ".");
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
     * @return \r8\URL Returns a self reference
     */
    public function clearHost ()
    {
        $this->host = null;
        return $this;
    }

    /**
     * Returns whether the host information in this instance is the same as
     * the host info in another URL
     *
     * A null value in the subdomain will be treated as the same as "www"
     *
     * @param \r8\URL $versus The URL to compare to
     * @return Boolean
     */
    public function isSameHost ( \r8\URL $versus )
    {
        if ( !$this->hostExists() || !$versus->hostExists() )
            return FALSE;

        $localHost = \r8\str\stripHead( $this->getHost(), "www." );
        $vsHost = \r8\str\stripHead( $versus->getHost(), "www." );

        if ( strcasecmp($localHost, $vsHost) == 0 )
            return TRUE;
        else
            return FALSE;
    }

    /**
     * Sets the host in this instance from another URL
     *
     * @param \r8\URL $source The source to copy from
     * @return \r8\URL Returns a self reference
     */
    public function copyHost ( \r8\URL $source )
    {
        return $this->setHost( $source->getHost() );
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
     * @return \r8\URL Returns a self reference
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
     * @return \r8\URL Returns a self reference
     */
    public function clearPort ()
    {
        $this->port = null;
        return $this;
    }

    /**
     * Returns whether the port in this instance is the same as the port
     * in another URL
     *
     * If the port in the instance isn't set, it will be treated as port 80
     *
     * @param \r8\URL $versus The URL to compare to
     * @return Boolean
     */
    public function isSamePort ( \r8\URL $versus )
    {
        $port = isset($this->port) ? $this->port : 80;

        return $versus->getPort() == $port ? TRUE : FALSE;
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

        $map = array(
            'http' => 80,
        	'https' => 443,
            'ftp' => 21,
            'ftps' => 990,
            'sftp' => 115,
            'ldap' => 389
        );

        if ( !isset( $map[ $this->getScheme() ] ) )
            return FALSE;

        return $this->port == $map[ $this->getScheme() ] ? TRUE : FALSE;
    }

    /**
     * Sets the port in this instance from another URL
     *
     * @param \r8\URL $source The source to copy from
     * @return \r8\URL Returns a self reference
     */
    public function copyPort ( \r8\URL $source )
    {
        return $this->setPort( $source->getPort() );
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
     * @return \r8\URL Returns a self reference
     */
    public function setHostAndPort ( $hostAndPort )
    {
        $hostAndPort = (string) $hostAndPort;

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

        $result = "//". $result;

        if ( $this->schemeExists() )
            $result = $this->getScheme() .":". $result;

        return $result;
    }

    /**
     * Parses a string into the scheme, userinfo, host and port
     *
     * @param String $base The base of the url
     * @return \r8\URL Returns a self reference
     */
    public function setBase ( $base )
    {
        $base = (string) $base;

        if ( \r8\str\contains("://", $base) ) {
            $this->setScheme( strstr($base, "://", TRUE) );
            $base = substr( strstr($base, "://", FALSE), 3 );
        }
        else {
            $this->clearScheme();
        }

        if ( \r8\str\contains("@", $base) ) {
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
     * Returns whether the Scheme, host and port in this instance are
     * the same as those in another URL
     *
     * Note that this method ignores the userinfo.
     *
     * @param \r8\URL $versus The URL to compare to
     * @return Boolean
     */
    public function isSameBase ( \r8\URL $versus )
    {
        return $this->isSameHost( $versus )
            && $this->isSameScheme( $versus )
            && $this->isSamePort( $versus );
    }

    /**
     * Sets the scheme, host and port in this instance another URL
     *
     * @param \r8\URL $source The source to copy from
     * @return \r8\URL Returns a self reference
     */
    public function copyBase ( \r8\URL $source )
    {
        return $this->copyScheme( $source )
            ->copyHost( $source )
            ->copyPort( $source );
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
     * @return \r8\URL Returns a self reference
     */
    public function setDir ( $directory )
    {
        $directory = (string) $directory;

        if ( \r8\isEmpty( $directory ) ) {
            $this->directory = null;
        }
        else {
            $directory = \r8\FileSys::resolvePath( $directory );
            $directory = \r8\str\enclose( $directory, "/" );
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
     * @return \r8\URL Returns a self reference
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
     * @return \r8\URL Returns a self reference
     */
    public function setFilename ( $filename )
    {
        $filename = trim((string) $filename);
        $filename = rtrim( $filename, "." );
        $this->filename = \r8\isEmpty( $filename ) ? null : $filename;
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
     * @return \r8\URL Returns a self reference
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
     * @return \r8\URL Returns a self reference
     */
    public function setExt ( $extension )
    {
        $extension = trim((string) $extension);
        $extension = ltrim( $extension, "." );
        $this->extension = \r8\isEmpty( $extension ) ? null : $extension;
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
     * @return \r8\URL Returns a self reference
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

        return \r8\str\weld(
                $this->getFilename(),
                $this->getExt(),
                "."
            );
    }

    /**
     * Sets the basename, which is the filename and extension
     *
     * @param String $basename The new basename
     * @return \r8\URL Returns a self reference
     */
    public function setBasename ( $basename )
    {
        $basename = trim((string) $basename);
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
     * @return \r8\URL Returns a self reference
     */
    public function setPath ( $path )
    {
        $path = trim((string) $path);
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
     * @return \r8\URL Returns a self reference
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
     * Fills the path from another URL
     *
     * @param \r8\URL $source The source to copy from
     * @return \r8\URL Returns a self reference
     */
    public function copyPath ( \r8\URL $source )
    {
        $this->setPath( $source->getPath() );
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
     * @return \r8\URL Returns a self reference
     */
    public function setFauxDir ( $fauxDir )
    {
        $fauxDir = (string) $fauxDir;
        $this->fauxDir = \r8\isEmpty( $fauxDir )
            ? null : \r8\str\head($fauxDir, "/");
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
     * @return \r8\URL Returns a self reference
     */
    public function clearFauxDir ()
    {
        $this->fauxDir = null;
        return $this;
    }

    /**
     * Fills the faux directories from another URL
     *
     * @param \r8\URL $source The source to copy from
     * @return \r8\URL Returns a self reference
     */
    public function copyFauxDir ( \r8\URL $source )
    {
        $this->setFauxDir( $source->getFauxDir() );
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
     * @return \r8\URL Returns a self reference
     */
    public function setQuery ( $query )
    {
        if ( is_array($query) )
            $query = http_build_query( $query );

        $query = (string) $query;

        if ( \r8\isEmpty($query, \r8\ALLOW_SPACES) )
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
     * @return \r8\URL Returns a self reference
     */
    public function clearQuery ()
    {
        $this->query = null;
        return $this;
    }

    /**
     * Fills the faux directories from another URL
     *
     * @param \r8\URL $source The source to copy from
     * @return \r8\URL Returns a self reference
     */
    public function copyQuery ( \r8\URL $source )
    {
        $this->setQuery( $source->getQuery() );
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
     * @return \r8\URL Returns a self reference
     */
    public function setFragment ( $fragment )
    {
        $fragment = (string) $fragment;
        $this->fragment = \r8\isEmpty( $fragment, \r8\ALLOW_SPACES ) ? null : $fragment;
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
     * @return \r8\URL Returns a self reference
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

        return \r8\isEmpty( $result, \r8\ALLOW_SPACES ) ? null : $result;
    }

    /**
     * Returns the full URL contained in this instance
     *
     * @return String
     */
    public function getURL ()
    {
        $url = $this->getBase() . $this->getRelative();

        return \r8\isEmpty( $url, \r8\ALLOW_SPACES ) ? null : $url;
    }

    /**
     * Sets the entire URL at once
     *
     * @param String $url The URL string
     * @return \r8\URL Returns a self reference
     */
    public function setURL ( $url )
    {
        $url = (string) $url;

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
     * @return \r8\URL Returns a self reference
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
     * @param \r8\URL $source The source to copy from
     * @return \r8\URL Returns a self reference
     */
    public function copyURL ( \r8\URL $source )
    {
        $this->copyBase( $source )
            ->copyUserInfo( $source )
            ->copyPath( $source )
            ->copyFauxDir( $source )
            ->copyQuery( $source );

        $this->setFragment( $source->getFragment() );
        return $this;
    }

}

?>