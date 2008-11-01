<?php
/**
 * HTML Form Helper
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
 * @package Forms
 */

namespace cPHP;

/**
 * Collects information about the current environment and allows readonly access to it
 */
class Env
{
    
    /**
     * Whether this script is being run locally, or was requested remotely
     *
     * @public
     */
    protected $local;
    
    /**
     * The current IP
     *
     * @public
     */
    protected $ip;
    
    /**
     * The raw URL query used to load this page
     *
     * @public
     */
    protected $query;
    
    /**
     * The port this page was requested over
     *
     * @public
     */
    protected $port;
    
    /**
     * The scheme used to request this page, usually "http"
     *
     * @public
     */
    protected $scheme;
    
    /**
     * The filesystem path of the requested file
     *
     * @public public
     */
    protected $path;
    
    /**
     * The filesystem directory of the requested file
     *
     * @public public
     */
    protected $dir;
    
    /**
     * The basename of the request file
     *
     * This includes the filename and extension
     *
     * @public
     */
    protected $basename;
    
    /**
     * The requested filename
     *
     * This does not include the file extension
     *
     * @public
     */
    protected $filename;
    
    /**
     * The extension of the requested file
     *
     * This is NULL if there is no exception
     *
     * @public
     */
    protected $extension;
   
    /**
     * The current working directory
     *
     * @public
     */
    protected $cwd;

    /**
     * The full requested host
     *
     * The host is the subdomain, SLD and TLD all in one. For example, "test.example.com"
     * 
     * This is NULL if no host was set
     *
     * @public
     */
    protected $host;
    
    /**
     * The host name with the port attached.
     *
     * This will only attach the port if it isn't port 80, and if it is set
     */
    protected $hostWithPort;

    /**
     * The top level domain of the requested URI
     *
     * In the URL "test.example.com", the TLD is "example.com"
     * 
     * This is NULL if no TLD exists
     *
     * @public
     */
    protected $domain;

    /**
     * The top level domain of the requested URI
     *
     * In the URL "test.example.com", the TLD is "com"
     * 
     * This is NULL if no TLD exists
     *
     * @public
     */
    protected $tld;

    /**
     * The second level domain of the requested URI
     *
     * In the URL "test.example.com", the SLD is "example"
     * 
     * This is NULL if no SLD exists
     *
     * @public
     */
    protected $sld;

    /**
     * The subdomain of the requested URI
     *
     * In the URL "test.test.example.com", the SLD is "test.test"
     *
     * This is NULL if no subdomain was set
     *
     * @public
     */
    protected $subdomain;
    
    /**
     * The relative path of the requested URI
     *
     * @public
     */
    protected $uriPath;
    
    /**
     * The relative directory of the requested URI
     *
     * @public
     */
    protected $uriDir;
    
    /**
     * The absolute path of the requested URI
     *
     * @public
     */
    protected $absUriPath;
    
    /**
     * The absolute directory of the requested URI
     *
     * @public
     */
    protected $absUriDir;
    
    /**
     * The relative URI
     *
     * @public
     */
    protected $uri;
    
    /**
     * The absolute URI
     *
     * @public
     */
    protected $absUri;
    
    /**
     * Also known as the path info, this represents any directories listed
     * after the filename of the path... for example, the following uri:
     *
     * http://www.example.com/file.php/faux/dir
     *
     * will execute the script "file.php" and "/faux/dir" will be stored in
     * this property.
     *
     * @public
     */
    protected $fauxDirs;
    
    /**
     * Returns whether a given array has key with a non-empty value
     *
     * @param Array $array The array to test
     * @param String $key The key to test
     * @return Boolean
     */
    static public function hasKey( array &$array, $key )
    {
        if ( !array_key_exists($key, $array) )
            return FALSE;
        
        if ( ::cPHP::is_empty($array[$key]) )
            return FALSE;
        
        return TRUE;
    }
    
    /**
     * Protected to force the use of the static constructors
     *
     * @param Array $server The $_SERVER array to parse in to this instance
     */
    protected function __construct( array $server )
    {
        $this->setLocal( $server );
        $this->setCWD();
        $this->setFileInfo( $server );
        
        if ( !$this->local ) {
            $this->setIP( $server );
            $this->setQuery( $server );
            $this->setPort( $server );
            $this->setScheme( $server );
            $this->setHostInfo( $server );
            $this->setUriInfo( $server );
        }
    }
    
    /**
     * Provides read only access to the protected variables in this instance
     *
     * @param String $variable The variable to fetch
     * @return mixed Returns the value of the requested property
     */
    public function __get ($variable)
    {
        $variable = ::cPHP::stripW( $variable );
        
        if ( !property_exists($this, $variable) )
            throw new ::cPHP::Exception::Argument(0, "Variable Name", "Variable does not exist");
        
        return $this->$variable;
    }
    
    /**
     * Provides read only access to detect whether a protected variable is set
     *
     * @param String $variable The variable to test
     * @return Boolean Whether the requested variable has a value
     */
    public function __isset ($variable)
    {
        $variable = ::cPHP::stripW( $variable );
        
        if ( !property_exists($this, $variable) )
            throw new ::cPHP::Exception::Argument(0, "Variable Name", "Variable does not exist");
        
        return isset( $this->$variable );
    }
    
    /**
     * Sets whether this script is being executed via command line or not
     *
     * @param Array $server The server info array
     * @return null
     */
    protected function setLocal ( array &$server )
    {
        $this->local = self::hasKey($server, "SHELL");
    }
    
    /**
     * Fills in the IP property
     *
     * @param Array $server The server info array
     * @return null
     */
    protected function setIP ( array &$server )
    {
        if ( self::hasKey($server, "SERVER_ADDR") )
            $this->ip = $server['SERVER_ADDR'];
    }
    
    /**
     * Fills in the URL Query property
     *
     * @param Array $server The server info array
     * @return null
     */
    protected function setQuery ( array &$server )
    {
        if ( self::hasKey($server, "QUERY_STRING") )
            $this->query = $server['QUERY_STRING'];
    }
    
    /**
     * Fills in the request port property
     *
     * @param Array $server The server info array
     * @return null
     */
    protected function setPort ( array &$server )
    {
        if ( self::hasKey($server, "SERVER_PORT") )
            $this->port = intval( $server['SERVER_PORT'] );
    }
    
    /**
     * Fills in the protocol property
     *
     * @param Array $server The server info array
     * @return null
     */
    protected function setScheme ( array &$server )
    {
        if ( self::hasKey($server, "SERVER_PROTOCOL") )
            $this->scheme = strtolower( strstr( $server['SERVER_PROTOCOL'], "/", TRUE ) );
    }
    
    /**
     * This sets the file name, basename, path and extension of the executed file
     *
     * @param Array $server The server info array
     * @return null
     */
    protected function setFileInfo ( array &$server )
    {
        if ( !self::hasKey($server, "SCRIPT_FILENAME") )
            return;
        
        $this->path = $server['SCRIPT_FILENAME'];
        
        $this->basename = basename( $this->path );
        $this->dir = dirname( $this->path );
        
        $info = pathinfo( $this->path );
        
        $this->filename = $info['filename'];
        
        if ( self::hasKey($info, "extension") )
            $this->extension = $info['extension'];
            
    }
    
    /**
     * Sets the current working directory
     * 
     * @return null
     */
    public function setCWD ()
    {
        $this->cwd = getcwd();
    }
    
    /**
     * Sets the host, domain, told, sld and subdomain
     * 
     * @param Array $server The server info array
     * @return null
     */
    protected function setHostInfo ( array &$server )
    {
        if ( !self::hasKey($server, 'HTTP_HOST') )
            return;
        
        // Confirm that it isn't equal to the IP
        if ( $this->ip == $server['HTTP_HOST'] )
            return;
        
        $regex = "/^(?:(.*)\\.)?([^\\.\\:]+)\\.([^\\.\\:]+)(?:\\:([0-9]*))?$/";
        
        if ( !preg_match($regex, $server['HTTP_HOST'], $domain) )
            return;
        
        if ( self::hasKey($domain, 1) )
            $this->subdomain = $domain[1];
        else
            $this->subdomain = 'www';
        
        $this->sld = $domain[2];
        $this->tld = $domain[3];
        
        $this->domain = $this->sld .".". $this->tld;
        
        if ( ::cPHP::is_empty($this->subdomain) )
            $this->host = $this->domain;
        else
            $this->host = $this->subdomain .".". $this->domain;
            
        if ( !::cPHP::is_empty($this->port) && $this->port != 80 )
            $this->hostWithPort = $this->host .":". $this->port;
        else
            $this->hostWithPort = $this->host;
    }
    
    /**
     * Sets the relative and absolute URI properties
     * 
     * @param Array $server The server info array
     * @return null
     */
    protected function setUriInfo ( array &$server )
    {
        if ( !self::hasKey($server, 'SCRIPT_NAME') )
            return;
        
        // Replace an windows forward slashes
        $this->uriPath = str_replace("\\", "/", $server['SCRIPT_NAME']);
        
        // Ensure it starts with a forward slash
        $this->uriPath = ::cPHP::strHead($this->uriPath, "/");
        
        $this->absUriPath = ::cPHP::strWeld( $this->hostWithPort, $this->uriPath, "/");
        
        $this->uriDir = strTail( dirname( $this->uriPath), "/" );
        
        $this->absUriDir = ::cPHP::strWeld( $this->hostWithPort, $this->uriDir, "/");
    }
    
}

?>