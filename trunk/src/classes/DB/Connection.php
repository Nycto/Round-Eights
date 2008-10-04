<?php
/**
 * Database Connection
 *
 * @package Database
 */

namespace cPHP::DB;

/**
 * Database Connection
 *
 * The base class for database connections. Provides an interface for
 * setting up the connection, performing actions against the connection and
 * automatically disconnecting
 */
abstract class Connection
{
    
    /**
     * Whether to open a persistent connection
     */
    private $persistent = FALSE;

    /**
     * Log-in username
     */
    private $username;

    /**
     * Log-in password
     */
    private $password;

    /**
     * Database server to connect to
     */
    private $host = "localhost";

    /**
     * The server port to connect to
     */
    private $port;

    /**
     * The database to select
     */
    private $database;
    
    /**
     * Once connected, this is the connection resource
     */
    private $resource;
    
    /**
     * Connect to the server
     *
     * @return Resource Returns a database connection resource
     */
    abstract protected function rawConnect ();

    /**
     * Disconnect from the server
     *
     * @return null
     */
    abstract protected function rawDisconnect ();

    /**
     * Used to escape a string for use in a query.
     *
     * @param String $value The string to escape
     * @return String An escaped version of the string
     */
    abstract protected function rawEscape ($value);

    /**
     * Execute a query and return a result object
     *
     * @param String $query The query string to execute
     * @return Object Returns a cPHP::DB::Result object
     */
    abstract protected function rawQuery ($query);
    
    /**
     * Returns whether the connection should use a persistent connection
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
     * @param Boolean $setting Whether the connection should be a persistent one
     * @return Object Returns a self reference
     */
    public function setPersistent ( $setting )
    {
        $this->persistent = ::cPHP::Filter::Boolean()->filter($setting);
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
     * @return Object Returns a self reference
     */
    public function setUserName ( $username )
    {
        $username = ::cPHP::strval( $username );
        $this->username = ::cPHP::is_empty( $username ) ? null : $username;
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
     * @return Object Returns a self reference
     */
    public function setPassword ( $password )
    {
        $password = ::cPHP::strval( $password );
        $this->password = ::cPHP::is_empty($password) ? null : $password;
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
     * @return Object Returns a self reference
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
     * @return Object Returns a self reference
     */
    public function setHost ( $host )
    {
        $host = ::cPHP::strval( $host );
        $this->host = ::cPHP::is_empty( $host ) ? null : $host;
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
     * @return Object Returns a self reference
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
     * @return Object Returns a self reference
     */
    public function setPort ( $port )
    {
        $port = intval( ::cPHP::reduce( $port ) );
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
     * @return Object Returns a self reference
     */
    public function clearPort ()
    {
        $this->port = null;
        return $this;
    }
    
    /**
     * Returns the value of the Database to select after connection
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
     * @return Object Returns a self reference
     */
    public function setDatabase ( $database )
    {
        $database = ::cPHP::strval( $database );
        $this->database = ::cPHP::is_empty( $database ) ? null : $database;
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
     * @return Object Returns a self reference
     */
    public function clearDatabase ()
    {
        $this->database = null;
        return $this;
    }
    
    /**
     * Validates the log-in credentials in preparation to connect
     *
     * @throws cPHP::Exception::Database::Connection
     *      This will be thrown if any of the required credentials are not set
     * @return Object Returns a self reference
     */
    public function validateCredentials ()
    {
        if ( !$this->userNameExists() )
            throw new ::cPHP::Exception::Database::Connection("UserName must be set", 0, $this);

        if ( !$this->hostExists() )
            throw new ::cPHP::Exception::Database::Connection("Host must be set", 0, $this);

        if ( !$this->databaseExists() )
            throw new ::cPHP::Exception::Database::Connection("Database name must be set", 0, $this);
        
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
            throw new ::cPHP::Exception::Interaction("Host must be set");
        
        if ( $this->portExists() )
            return $this->host .":". $this->port;
        else
            return $this->host;
    }
    
}

?>