<?php
/**
 * Base Database Decorator
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
 * Base wrapper for increasing the functionality of a database Link
 */
abstract class LinkWrap implements ::cPHP::iface::DB::Link
{
    
    /**
     * The Link this decorator wraps around
     */
    private $link;    
   
    /**
     * Constructor...
     *
     * @param Object $link The database Link this instance wraps around
     */
    public function __construct ( ::cPHP::iface::DB::Link $link )
    {
        $this->link = $link;
    }
    
    /**
     * Returns the Link this instance wraps
     *
     * @return Object
     */
    public function getLink ()
    {
        return $this->link;
    }
    
    /**
     * Runs a query and returns the result
     * 
     * Wraps the equivilent function in the Link
     *
     * @param String $query The query to run
     * @param Integer $flags Any boolean flags to set
     * @result Object Returns a result object
     */
    public function query ( $query, $flags = 0 )
    {
        return $this->link->query( $query );
    }
    
    /**
     * Quotes a variable to be used in a query
     *
     * Wraps the equivilent function in the Link
     *
     * @param mixed $value The value to quote
     * @param Boolean $allowNull Whether to allow 
     * @return String|Array
     */
    public function quote ( $value, $allowNull = TRUE )
    {
        return $this->link->quote( $value, $allowNull );
    }
    
    /**
     * Escapes a variable to be used in a query
     *
     * Wraps the equivilent function in the Link
     *
     * @param mixed $value The value to quote
     * @param Boolean $allowNull Whether to allow 
     * @return String|Array
     */
    public function escape ( $value, $allowNull = TRUE )
    {
        return $this->link->escape( $value, $allowNull );
    }
   
}

?>