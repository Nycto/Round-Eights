<?php
/**
 * Database Connection
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
 * @package Database
 */

namespace cPHP::DB;

/**
 * Core Database Connection
 *
 * The base class for database connections. Provides an interface for
 * setting up the link, performing actions against the resource and
 * automatically disconnecting
 */
abstract class Link implements ::cPHP::iface::DB::Link
{

    /**
     * To be overridden, this is the PHP extension required for this interface to work
     */
    const PHP_EXTENSION = FALSE;

    /**
     * Whether to open a persistent Link
     */
    private $persistent = FALSE;

    /**
     * Whether to force a new Link
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
     * Once connected, this is the Link resource
     */
    private $resource;

    /**
     * Returns whether a query is a SELECT query
     *
     * @param String $query The query being tested
     * @return Boolean
     */
    static public function isSelect ( $query )
    {
        $query = ::cPHP::strval($query);
        $query = ::cPHP::str::stripQuoted($query, array("'", '"', "`"));
        $query = trim($query);

        return preg_match("/^\s*[\(?\s*]*(?:EXPLAIN\s+)?SELECT/i", $query) ? TRUE : FALSE;
    }

    /**
     * Constructor...
     *
     * @param mixed $input This can be either a URI or an associative array
     */
    public function __construct ( $input = null )
    {
        // Ensure that the required extension is loaded
        if ( static::PHP_EXTENSION != false && !extension_loaded( static::PHP_EXTENSION ) ) {
            throw new ::cPHP::Exception::Extension(
                    static::PHP_EXTENSION,
                    "Extension is not loaded"
                );
        }

        if ( is_string($input) )
            $this->fromURI( $input );

        else if ( is_array( $input ) )
            $this->fromArray( $input );
    }

    /**
     * Destructor...
     *
     * Automatically closes the database Link when the object is cleaned up
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
     * Disconnect from the server
     *
     * @return null
     */
    abstract protected function rawDisconnect ();

    /**
     * Returns whether a given resource is still connected
     *
     * @param Resource|Object $connection The connection being tested
     * @return Boolean
     */
    abstract protected function rawIsConnected ( $connection );

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
     * @return Object Returns a self reference
     */
    public function setPersistent ( $setting )
    {
        $this->persistent = ::cPHP::Filter::Boolean()->filter($setting);
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
        $this->password = ::cPHP::isEmpty($password) ? null : $password;
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
        $this->host = ::cPHP::isEmpty( $host ) ? null : $host;
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
     * @return Object Returns a self reference
     */
    public function setDatabase ( $database )
    {
        $database = ::cPHP::strval( $database );
        $this->database = ::cPHP::isEmpty( $database ) ? null : $database;
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

            $key = "set". strtolower( ::cPHP::str::stripW( $key ) );
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
     * @throws cPHP::Exception::DB::Link
     *      This will be thrown if any of the required credentials are not set
     * @return Object Returns a self reference
     */
    public function validateCredentials ()
    {
        if ( !$this->userNameExists() )
            throw new ::cPHP::Exception::DB::Link("UserName must be set", 0, $this);

        if ( !$this->hostExists() )
            throw new ::cPHP::Exception::DB::Link("Host must be set", 0, $this);

        if ( !$this->databaseExists() )
            throw new ::cPHP::Exception::DB::Link("Database name must be set", 0, $this);

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
        $result =
            isset($this->resource)
            && ( is_resource($this->resource) || is_object($this->resource) )
            && $this->rawIsConnected( $this->resource );

        if ( !$result )
            $this->resource = null;

        return $result ? TRUE : FALSE;
    }

    /**
     * Returns the connection resource
     *
     * If this instance is not already connected, this will attempt to make the connection
     *
     * @return Resource Returns a database connection resource
     */
    public function getLink ()
    {
        if ( !$this->isConnected() ){

            $this->validateCredentials();

            $result = $this->rawConnect();

            if ( !is_resource($result) && !is_object($result) ) {
                throw new cPHP::Exception::DB::Link(
                        "Database connector did not return a resource or an object",
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
     * @param Integer $flags Any boolean flags to set
     * @result Object Returns a result object
     */
    public function query ( $query, $flags = 0 )
    {
        $query = ::cPHP::strval($query);

        if ( ::cPHP::isEmpty($query) )
            throw new ::cPHP::Exception::Argument(0, "Query", "Must not be empty");

        try {
            $result = $this->rawQuery( $query );
        }
        catch (::cPHP::Exception::DB::Query $err) {
            $err->shiftFault();
            throw $err;
        }

        if ( !( $result instanceof ::cPHP::DB::Result ) ) {
            throw new ::cPHP::Exception::DB::Query(
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
            $this->rawDisconnect();
        $this->link = null;
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
     * @return String|Object Returns the escaped string, or a cPHP::Ary object
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
     * @return String|Object Returns the escaped string, or a cPHP::Ary object
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