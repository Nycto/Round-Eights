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
 * @package Database
 */

namespace r8\DB;

/**
 * Database configuration information
 */
class Config
{

    /**
     * Whether to open a persistent Link
     *
     * @var Boolean
     */
    private $persistent = FALSE;

    /**
     * Whether to force a new Link
     *
     * @var Boolean
     */
    private $forceNew = FALSE;

    /**
     * Log-in username
     *
     * @var String
     */
    private $username;

    /**
     * Log-in password
     *
     * @var String
     */
    private $password;

    /**
     * Database server to connect to
     *
     * @var String
     */
    private $host = "localhost";

    /**
     * The server port to connect to
     *
     * @var String
     */
    private $port;

    /**
     * The database to select
     *
     * @var String
     */
    private $database;

    /**
     * Constructor...
     *
     * @param mixed $input This can be either a URI or an associative array
     */
    public function __construct ( $input = null )
    {
        if ( is_string($input) )
            $this->fromURI( $input );

        else if ( is_array( $input ) )
            $this->fromArray( $input );
    }

    /**
     * Returns whether the link should use a persistent connection
     *
     * @return Boolean
     */
    public function getPersistent ()
    {
        return $this->persistent;
    }

    /**
     * Sets whether a persistent connection should be used
     *
     * @param Boolean $setting Whether the Link should be a persistent one
     * @return \r8\DB\Config Returns a self reference
     */
    public function setPersistent ( $setting )
    {
        $this->persistent = r8(new \r8\Filter\Boolean)->filter($setting);
        return $this;
    }

    /**
     * Returns whether an existing Link should be re-used if it already exists
     * or if a new database connection should be forced
     *
     * @return Boolean
     */
    public function getForceNew ()
    {
        return $this->forceNew;
    }

    /**
     * Sets whether an existing connection should be re-used if it already exists
     * or if a new database connection should be forced
     *
     * @param Boolean $setting Whether a new connection should be forced
     * @return \r8\DB\Config Returns a self reference
     */
    public function setForceNew ( $setting )
    {
        $this->forceNew = r8(new \r8\Filter\Boolean)->filter($setting);
        return $this;
    }

    /**
     * Returns the value of the username in this instance
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
     * @return \r8\DB\Config Returns a self reference
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
     * @return \r8\DB\Config Returns a self reference
     */
    public function clearUserName ()
    {
        $this->username = null;
        return $this;
    }

    /**
     * Returns the value of the Password in this instance
     *
     * @return String|Null Returns null if the Password isn't set
     */
    public function getPassword ()
    {
        return $this->password;
    }

    /**
     * Sets the Password credential
     *
     * @param String $password The Password to set
     * @return \r8\DB\Config Returns a self reference
     */
    public function setPassword ( $password )
    {
        $password = (string) $password;
        $this->password = \r8\isEmpty($password) ? null : $password;
        return $this;
    }

    /**
     * Returns whether the Password has been set
     *
     * @return Boolean
     */
    public function passwordExists ()
    {
        return isset( $this->password );
    }

    /**
     * Unsets the currently set Password
     *
     * @return \r8\DB\Config Returns a self reference
     */
    public function clearPassword ()
    {
        $this->password = null;
        return $this;
    }

    /**
     * Returns the value of the Host that will be connected to
     *
     * @return String|Null Returns null if the Host isn't set
     */
    public function getHost ()
    {
        return $this->host;
    }

    /**
     * Sets the Host that will be connected to
     *
     * @param String $host The Host to set
     * @return \r8\DB\Config Returns a self reference
     */
    public function setHost ( $host )
    {
        $host = (string) $host;
        $this->host = \r8\isEmpty( $host ) ? null : $host;
        return $this;
    }

    /**
     * Returns whether the Host has been set
     *
     * @return Boolean
     */
    public function hostExists ()
    {
        return isset( $this->host );
    }

    /**
     * Clears the currently set Host
     *
     * @return \r8\DB\Config Returns a self reference
     */
    public function clearHost ()
    {
        $this->host = null;
        return $this;
    }

    /**
     * Returns the value of the Port that will be connected on
     *
     * @return String|Null Returns null if the Port isn't set
     */
    public function getPort ()
    {
        return $this->port;
    }

    /**
     * Sets the Port that will be connected on
     *
     * @param String $port The Port to set
     * @return \r8\DB\Config Returns a self reference
     */
    public function setPort ( $port )
    {
        $port = (int) $port;
        $this->port = $port <= 0 ? null : $port;
        return $this;
    }

    /**
     * Returns whether the Port has been set
     *
     * @return Boolean
     */
    public function portExists ()
    {
        return isset( $this->port );
    }

    /**
     * Clears the currently set Port
     *
     * @return \r8\DB\Config Returns a self reference
     */
    public function clearPort ()
    {
        $this->port = null;
        return $this;
    }

    /**
     * Returns the value of the Database to select after Link
     *
     * @return String|Null Returns null if the Database isn't set
     */
    public function getDatabase ()
    {
        return $this->database;
    }

    /**
     * Sets the Database to select after connecting to the host
     *
     * @param String $database The Database to set
     * @return \r8\DB\Config Returns a self reference
     */
    public function setDatabase ( $database )
    {
        $database = (string) $database;
        $this->database = \r8\isEmpty( $database ) ? null : $database;
        return $this;
    }

    /**
     * Returns whether the Database property has been set
     *
     * @return Boolean
     */
    public function databaseExists ()
    {
        return isset( $this->database );
    }

    /**
     * Clears the currently set Database
     *
     * @return \r8\DB\Config Returns a self reference
     */
    public function clearDatabase ()
    {
        $this->database = null;
        return $this;
    }

    /**
     * Imports the settings in this instance from an array
     *
     * @param array $array The array of settings to import
     * @return \r8\DB\Config Returns a self reference
     */
    public function fromArray ( array $array )
    {
        foreach ( $array AS $key => $value ) {

            $key = "set". strtolower( \r8\str\stripW( $key ) );
            $value = (string) $value;

            if ( method_exists( $this, $key ) )
                $this->$key( $value );

        }

        return $this;
    }

    /**
     * Imports the settings from a URI
     *
     * @param String $uri The settings to import
     * @return \r8\DB\Config Returns a self reference
     */
    public function fromURI ( $uri )
    {
        $uri = (string) $uri;
        $result = r8(new \r8\Validator\URL)->validate( $uri );

        if ( !$result->isValid() )
            throw new \r8\Exception\Argument( 0, "Settings URI", $result->getFirstError() );

        $uri = parse_url( $uri );
        $uri = \r8\ary\translateKeys( $uri, array(
                "user" => "username",
                "pass" => "password"
            ));

        $this->fromArray( $uri );

        if ( isset($uri["path"]) )
            $this->setDatabase( ltrim( $uri['path'], "/" ) );

        if ( isset($uri["query"]) )
            $this->fromArray( r8( new \r8\QueryParser )->parse( $uri['query'] ) );

        return $this;
    }

    /**
     * Validates the log-in credentials in preparation to connect
     *
     * @throws \r8\Exception\DB\Link This will be thrown if any of the
     *      required credentials are not set
     * @return \r8\DB\Config Returns a self reference
     */
    public function requireCredentials ()
    {
        if ( !$this->userNameExists() )
            throw new \r8\Exception\DB\Link("UserName must be set", 0, $this);

        if ( !$this->hostExists() )
            throw new \r8\Exception\DB\Link("Host must be set", 0, $this);

        if ( !$this->databaseExists() )
            throw new \r8\Exception\DB\Link("Database name must be set", 0, $this);

        return $this;
    }

    /**
     * Returns the host name with the port attached, if one has been specified
     *
     * @return String
     */
    public function getHostWithPort ()
    {
        if ( !$this->hostExists() )
            throw new \r8\Exception\Interaction("Host must be set");

        if ( $this->portExists() )
            return $this->host .":". $this->port;
        else
            return $this->host;
    }

    /**
     * Returns a URI string describing this connection
     *
     * @return String
     */
    public function getURI ()
    {
        $uri = new \r8\URL;
        $uri->setScheme("db")
            ->setUserName( $this->username )
            ->setPassword( $this->password )
            ->setHost( $this->host )
            ->setPort( $this->port )
            ->setPath( $this->database )
            ->setQuery(array_filter(array(
                "persistent" => $this->persistent ? "t" : NULL,
                "forceNew" => $this->forceNew ? "t" : NULL
            )));
        return $uri->__toString();
    }

    /**
     * Generates an identifying string based on the data in this configuration
     *
     * @param String $protocol The protocol type to prepend to the identifier
     * @return String
     */
    public function getIdentifier ( $protocol )
    {
        $ident = $protocol;

        if ( !$this->hostExists() )
            return $ident;

        $ident .= "://";

        if ( $this->userNameExists() )
            $ident .= $this->getUserName() ."@";

        $ident .= $this->getHostWithPort();

        return $ident;
    }

}

?>