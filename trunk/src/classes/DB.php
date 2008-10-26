<?php
/**
 * Database Registry
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

namespace cPHP;

/**
 * Database Registry class
 *
 * This provides an interface for registering database Links in a global
 * repository and later retrieving them in a different scope.
 */
class DB
{
    
    /**
     * This list of registered connections indexed by a shortcut
     */
    static protected $links = array();
    
    /**
     * The default database connection
     */
    static protected $default;
    
    /**
     * Returns the full list of database connections
     *
     * @return Array
     */
    static public function getLinks ()
    {
        return self::$links;
    }
    
    /**
     * Returns the default connection
     *
     * @return Object The default connection
     */
    static public function getDefault ()
    {
        return self::$default;
    }
    
    /**
     * Registers a new link
     *
     * If no default link has been set, the link passed to this instance
     * will be set as the default
     *
     * @param String $label The reference string used to index the connection
     * @param Object $link The actual database connection
     * @return Null
     */
    static public function setLink( $label, ::cPHP::iface::DB::Link $link )
    {
        $label = ::cPHP::strval( $label );
        
        if ( ::cPHP::is_empty($label) )
            throw new ::cPHP::Exception::Argument( 0, "Connection Label", "Must not be empty" );
        
        self::$links[ $label ] = $link;
        
        if ( !isset(self::$default) )
            self::setDefault( $label );
    }
    
    /**
     * Returns a registered link by it's label
     *
     * @param String $label The connection to return
     *      If no label is given, the default connection will be returned
     * @return Object The default connection
     */
    static public function get ( $label = NULL )
    {
        if ( !is_string($label) && ::cPHP::is_vague($label) )
            return self::getDefault();
        
        $label = ::cPHP::strval( $label );
        
        if ( ::cPHP::is_empty($label) )
            throw new ::cPHP::Exception::Argument( 0, "Connection Label", "Must not be empty" );
        
        if ( !array_key_exists($label, self::$links) )
            throw new ::cPHP::Exception::Index("Connection Label", $label, "Connection does not exist");
        
        return self::$links[$label];
    }
    
    /**
     * Sets the default connection based on an already registered label
     *
     * @param String $label The name of the connection to make the default
     * @return NULL
     */
    static public function setDefault ( $label )
    {
        $label = ::cPHP::strval( $label );
        
        if ( ::cPHP::is_empty($label) )
            throw new ::cPHP::Exception::Argument( 0, "Connection Label", "Must not be empty" );
        
        self::$default = self::get( $label );
    }
    
}

?>