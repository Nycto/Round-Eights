<?php
/**
 * Array filtering class
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

namespace cPHP\Filter;

/**
 * Applies a given filter to every value in an array, non-recursively
 */
class Ary extends \cPHP\Filter
{

    /**
     * The filter that will be applied to each value
     */
    private $filter;

    /**
     * Constructor...
     *
     * @param Object The filter to apply to each value in the array
     */
    public function __construct( \cPHP\iface\Filter $filter )
    {
        $this->setFilter( $filter );
    }

    /**
     * Returns the filter loaded in this instance
     *
     * @return Object
     */
    public function getFilter ()
    {
        return $this->filter;
    }

    /**
     * Sets the filter that will be applied to each value of a filtered array
     *
     * @param Object The filter to load in to this instance
     * @return Object Returns a self reference
     */
    public function setFilter ( \cPHP\iface\Filter $filter )
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * Apply this filter to an array value
     *
     * @param Array $value The value to filter
     * @return Array Returns the filtered version
     */
    public function filter ( $value )
    {
        if ( !\cPHP\Ary::is($value) || ( is_object($value) && !( $value instanceof \ArrayAccess) ) )
            $value = array($value);

        foreach( $value AS $key => $val ) {
            $value[ $key ] = $this->filter->filter( $val );
        }

        return $value;
    }

}

?>