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
 * Allows you to register filters that will be run against specific array offsets
 *
 * This will ignore any offsets that do not have registered filters and return
 * them as they are. It will also ignore registered filters that don't have
 * corresponding offsets in the array being filtered.
 */
class AryOffset extends \cPHP\Filter
{

    /**
     * The filters to apply indexed by their matching offset
     */
    private $filters = array();

    /**
     * Constructor...
     *
     * @param Array|Object A list of filters to apply indexed by the offsets to apply each filter to
     */
    public function __construct( $filters = array() )
    {
        $this->import( $filters );
    }

    /**
     * Returns the list of filters loaded in this instance
     *
     * @return Object Returns a \cPHP\Ary object
     */
    public function getFilters ()
    {
        return new \cPHP\Ary( $this->filters );
    }

    /**
     * Sets an index/filter pair in this instance
     *
     * This will overwrite any previous filters for the given index
     *
     * @param mixed $index The index this filter will be applied to
     * @param Object $filter The filter to apply to the given index
     * @return Object Returns a self reference
     */
    public function setFilter ( $index, \cPHP\iface\Filter $filter )
    {
        $index = \cPHP\reduce( $index );
        $this->filters[ $index ] = $filter;
        return $this;
    }

    /**
     * Imports a list of filters in to this instance
     *
     * @param Array|ObjectThe list of filters to import indexed by the offsets to apply each filter to
     * @return Object Returns a self reference
     */
    public function import ( $filters )
    {
        if ( !\cPHP\Ary::is( $filters) )
            throw new \cPHP\Exception\Argument( 0, "Filter List", "Must be an array or a traversable object" );

        $filters = new \cPHP\Ary( $filters );
        foreach ( $filters AS $key => $value ) {
            if ( $value instanceof \cPHP\iface\Filter )
                $this->setFilter( $key, $value );
        }

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
        if ( !\cPHP\Ary::is( $value ) )
            return $value;

        else if ( is_object($value) && ( !( $value instanceof \ArrayAccess ) || !( $value instanceof \Traversable ) ) )
            return $value;

        foreach ( $this->filters AS $key => $filter ) {
            if ( isset($value[$key]) )
                $value[$key] = $filter->filter( $value[$key] );
        }

        return $value;
    }

}

?>