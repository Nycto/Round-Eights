<?php
/**
 * Database Link
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

namespace cPHP::DB::MySQLi;

/**
 * MySQL Database Link
 */
class Link extends ::cPHP::DB::Link
{
    
    /**
     * This is the PHP extension required for this interface to work
     */
    const PHP_EXTENSION = "mysqli";
    
    /**
     * Connect to the server
     *
     * @return Resource|Object Returns a database connection resource
     */
    protected function rawConnect ()
    {
        $link = @mysqli_connect(
                $this->getHost(),
                $this->getUserName(),
                $this->getPassword(),
                $this->getDatabase(),
                $this->getPort()
            );

        if ( !$link ) {
            
            throw new ::cPHP::Exception::DB::Link(
                    mysqli_connect_error(),
                    mysqli_connect_errno(),
                    $this
                );
            
        }
        
        return $link;

    }
    
    /**
     * Returns whether a given resource is still connected
     *
     * @param Resource|Object $connection The connection being tested
     * @return Boolean
     */
    protected function rawIsConnected ( $connection )
    {
        if ( !($connection instanceof mysqli) )
            return FALSE;
        
        if ( @$connection->ping() !== TRUE )
            return FALSE;
        
        return TRUE;
    }

    /**
     * Used to escape a string for use in a query.
     *
     * @param String $value The string to escape
     * @return String An escaped version of the string
     */
    protected function rawEscape ( $value )
    {
        // Don't force a connection just to escape a string
        if ( $this->isConnected() )
            return $this->getLink()->real_escape_string( $value );
        else
            return addslashes( $value );
    }

    /**
     * Execute a query and return a result object
     *
     * @param String $query The query to execute
     * @return Object Returns a cPHP::DB::Result object
     */
    protected function rawQuery ( $query )
    {
        $link = $this->getLink();
        
        $result = $link->query( $query );
        
        if ( $result === FALSE )
            throw new ::cPHP::Exception::DB::Query( $query, $link->error, $link->errno );
        
        if ( self::isSelect($query) )
            return new ::cPHP::DB::MySQLi::Read( $result, $query );
        else
            return new ::cPHP::DB::Result::Write( $link->affected_rows, $link->insert_id, $query );
    }

    /**
     * Disconnect from the server
     *
     * @return null
     */
    protected function rawDisconnect ()
    {
        $link = $this->getLink();
        $link->close();
    }
    
}

?>