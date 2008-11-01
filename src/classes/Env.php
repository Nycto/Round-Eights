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
     * The basename of the request URL
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
     */
    protected $path;
    
    /**
     * The relative directory of the requested URI
     */
    protected $dir;
    
    /**
     * The absolute path of the requested URI
     */
    protected $absolutePath;
    
    /**
     * The absolute directory of the requested URI
     */
    protected $absoluteDir;
    
    /**
     * Protected to force the use of the static constructors
     *
     * @param Array $server The $_SERVER array to parse in to this instance
     */
    protected function __construct( array $server )
    {
        $this->setLocal( $server );
        
        $this->setIP( $server );
        $this->setQuery( $server );
        $this->setPort( $server );
        $this->setScheme( $server );
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
     */
    protected function setLocal ( array $server )
    {
        $this->local = isset($server['SHELL']) ? TRUE : FALSE;
    }
    
    /**
     * Fills in the IP property
     *
     * @param Array $server The server info array
     */
    protected function setIP ( array $server )
    {
        if ( array_key_exists("SERVER_ADDR", $server) )
            $this->ip = $server['SERVER_ADDR'];
    }
    
    /**
     * Fills in the URL Query property
     *
     * @param Array $server The server info array
     */
    protected function setQuery ( array $server )
    {
        if ( array_key_exists("QUERY_STRING", $server) && !::cPHP::is_empty($server['QUERY_STRING']) )
            $this->query = $server['QUERY_STRING'];
    }
    
    /**
     * Fills in the request port property
     *
     * @param Array $server The server info array
     */
    protected function setPort ( array $server )
    {
        if ( array_key_exists("SERVER_PORT", $server) && !::cPHP::is_empty($server['SERVER_PORT']) )
            $this->port = intval( $server['SERVER_PORT'] );
    }
    
    /**
     * Fills in the protocol property
     *
     * @param Array $server The server info array
     */
    protected function setScheme ( array $server )
    {
        if ( array_key_exists("SERVER_PROTOCOL", $server) && !::cPHP::is_empty($server['SERVER_PROTOCOL']) )
            $this->scheme = strtolower( strstr( $server['SERVER_PROTOCOL'], "/", TRUE ) );
    }
    
}

?>