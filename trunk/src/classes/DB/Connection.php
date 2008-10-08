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
abstract class Connection implements ::cPHP::iface::DB::Connection
{
    
    /**
     * To be overridden, this is the PHP extension required for this interface to work
     */
    const PHP_EXTENSION = FALSE;
    
    /**
     * Whether to open a persistent connection
     */
    private $persistent = FALSE;
    
    /**
     * Whether to force a new connection
     */
    private $forceNew = FALSE;

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
     * Constructor...
     */
    public function __construct ()
    {
        // Ensure that the required extension is loaded
        if ( static::PHP_EXTENSION != false && !extension_loaded( static::PHP_EXTENSION ) ) {
            throw new ::cPHP::Exception::Extension(
                    static::PHP_EXTENSION,
                    "Extension is not loaded"
                );
        }
    }
    
    /**
     * Destructor...
     *
     * Automatically closes the database connection when the object is cleaned up
     */
    public function __destruct ()
    {
        $this->disconnect();
    }
    
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
    abstract protected function rawDisconnect ( $resource );

    /**
     * Used to escape a string for use in a query.
     *
     * @param String $value The string to escape
     * @return String An escaped version of the string
     */
    abstract protected function rawEscape ( $value );

    /**
     * Execute a query and return a result object
     *
     * @param String $query The query to execute
     * @return Object Returns a cPHP::DB::Result object
     */
    abstract protected function rawQuery ( $query );
    
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
     * Returns whether an existing connection should be re-used if it already exists
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
     * @return Object Returns a self reference
     */
    public function setForceNew ( $setting )
    {
        $this->forceNew = ::cPHP::Filter::Boolean()->filter($setting);
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
     * Imports the settings in this instance from an array
     *
     * @param mixed $array The array of settings to import
     * @return Object Returns a self reference
     */
    public function fromArray ( $array )
    {
        $array = new ::cPHP::Ary( $array );
        
        foreach ( $array AS $key => $value ) {
            
            $key = "set". strtolower( ::cPHP::stripW( $key ) );
            $value = ::cPHP::strval( $value );
            
            if ( method_exists( $this, $key ) )
                $this->$key( $value );
            
        }
        
        return $this;
    }
    
    /**
     * Imports the settings from a URI
     *
     * @param String $uri The settings to import
     * @return Object Returns a self reference
     */
    public function fromURI ( $uri )
    {
        $uri = ::cPHP::strval( $uri );
        $result = ::cPHP::Validator::URL()->validate( $uri );
        
        if ( !$result->isValid() )
            throw new ::cPHP::Exception::Argument( 0, "Settings URI", $result->getFirstError() );
        
        $uri = new ::cPHP::Ary( parse_url( $uri ) );
        $uri = $uri->translateKeys(array(
                "user" => "username",
                "pass" => "password"
            ));
        
        $this->fromArray( $uri );
        
        if ( $uri->keyExists("path") )
            $this->setDatabase( ltrim( $uri['path'], "/" ) );
        
        if ( $uri->keyExists("query") ) {
            $query = array();
            parse_str( $uri['query'], $query );
            $this->fromArray( $query );
        }
        
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
    
    /**
     * Returns whether this instance is currently connected
     *
     * @return Boolean
     */
    public function isConnected ()
    {
        return isset($this->resource) && is_resource($this->resource);
    }
    
    /**
     * Returns the connection resource
     *
     * If this instance is not already connected, this will attempt to make the connection
     *
     * @return Resource Returns a database connection resource
     */
    public function getConnection ()
    {
        if ( !$this->isConnected() ){
            
            $this->validateCredentials();
            
            $result = $this->rawConnect();
            
            if ( !is_resource($result) ) {
                throw new cPHP::Exception::Database::Connection(
                        "Database connector did not return a resource",
                        0,
                        $this
                    );
            }
            
            $this->resource = $result;
        }
        
        return $this->resource;
    }
    
    /**
     * Runs a query and returns the result
     *
     * @param String $query The query to run
     * @result Object Returns a result object
     */
    public function query ( $query )
    {
        try {
            $result = $this->rawQuery( ::cPHP::strval($query) );
        }
        catch (::cPHP::Exception::Database::Query $err) {
            $err->shiftFault();
            throw $err;
        }
        
        if ( !( $result instanceof ::cPHP::DB::Result ) ) {
            throw new ::cPHP::Exception::Database::Query(
                    $query,
                    "Query did not return a cPHP::DB::Result object",
                    0,
                    $this
                );
        }
        
        return $result;
    }
    
    /**
     * If there is currently a cunnection, this will break it
     *
     * @return Object Returns a self reference
     */
    public function disconnect ()
    {
        if ( $this->isConnected() )
            $this->rawDisconnect( $this->getConnection() );
        return $this;
    }
    
    /**
     * Quotes a variable to be used in a query
     *
     * When given a string, it escapes the string and puts quotes around it. When
     * given a number, it returns the number as is. When given a boolean value,
     * it returns 0 or 1. When given a NULL value, it returns the word NULL as a string.
     *
     * If this function is given an array, it will apply itself to every value
     * in the array and return the array.
     *
     * @param mixed $value The value to quote
     * @param Boolean $allowNull Whether to allow 
     * @return String|Array
     */
    public function quote ( $value, $allowNull = TRUE )
    {

        if ( ::cPHP::Ary::is( $value ) )
            return ::cPHP::Ary::create( $value )->collect( array($this, "quote") );

        $value = ::cPHP::reduce($value);
        
        if (is_bool($value))
            return $value ? "1" : "0";

        else if ( is_int($value) || is_float($value) )
            return ::strval( $value );
        
        else if ( is_null($value) )
            return $allowNull ? "NULL" : "''";
        
        else if ( is_numeric($value) && !preg_match('/[^0-9\.]/', $value) )
            return $value;
        
        else
            return "'". $this->rawEscape($value) ."'";
        
    }

    /**
     * Escapes a variable to be used in a query
     *
     * This function works almost exactly like cDB::quote except that it does
     * not add quotation marks to strings. It just escapes each value.
     *
     * If this function is given an array, it will apply itself to every value
     * in the array and return that array.
     *
     * @param mixed $value The value to quote
     * @param Boolean $allowNull Whether to allow 
     * @return String|Array
     */
    public function escape ( $value, $allowNull = TRUE )
    {

        if ( ::cPHP::Ary::is( $value ) )
            return ::cPHP::Ary::create( $value )->collect( array($this, "escape") );

        $value = ::cPHP::reduce($value);
        
        if (is_bool($value))
            return $value ? "1" : "0";

        else if ( is_int($value) || is_float($value) )
            return ::strval( $value );
        
        else if ( is_null($value) )
            return $allowNull ? "NULL" : "";
        
        else
            return $this->rawEscape($value);
        
    }
    
}

?>