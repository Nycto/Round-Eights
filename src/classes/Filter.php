<?php
/**
 * Core filter interface
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
 * @package Filters
 */

namespace cPHP;

/**
 * Base Filtering class
 */
abstract class Filter implements ::cPHP::iface::Filter
{
    
    /**
     * Static method for creating a new filtering instance
     *
     * This takes the called function and looks for a class under
     * the cPHP::Filter namespace.
     *
     * @throws cPHP::Exception::Argument Thrown if the filter class can't be found
     * @param String $filter The filter class to create
     * @param array $args Any constructor args to use during instantiation
     * @return Object Returns a new cPHP::Filter subclass
     */
    static public function __callStatic ( $filter, $args )
    {
        $filter = "cPHP::Filter::". trim( ::cPHP::strval($filter) );
        
        if ( !class_exists($filter, true) ) {
            throw new ::cPHP::Exception::Argument(
                    0,
                    "Filter Class Name",
                    "Filter could not be found in cPHP::Filter namespace"
                );
        }
        
        if ( count($args) <= 0 ) {
            return new $filter;
        }
        else if ( count($args) == 1 ) {
            return new $filter( reset($args) );
        }
        else {
            $refl = new ReflectionClass( $filter );
            return $refl->newInstanceArgs( $args );
        }
        
    }

    /**
     * Magic method to allow this instance to be invoked like a function.
     *
     * Causes the filtering to happen as if the filter method was invoked
     *
     * @param mixed $value The value to filter
     * @return mixed The result of the filtering
     */
    public function __invoke( $value )
    {
        return $this->filter( $value );
    }

}

?>