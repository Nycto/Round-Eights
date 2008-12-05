<?php
/**
 * Validation class
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
 * @package Validators
 */

namespace cPHP\Validator;

/**
 * Returns whether the validated value is in a preset list
 */
class In extends \cPHP\Validator
{

    /**
     * The list of valid values
     */
    protected $list;

    /**
     * Constructor...
     *
     * @param mixed $list The list of valid values
     */
    public function __construct ( $list = array() )
    {
        $this->setList( $list );
    }

    /**
     * Sets the list of valid values
     *
     * @param mixed $list The list of valid values
     * @return Object Returns a self reference
     */
    public function setList ( $list )
    {
        if ( !\cPHP\Ary::is( $list ) )
            throw new \cPHP\Exception\Argument( 0, "Valid Value List", "Must be an array or a traversable object" );

        $this->list = \cPHP\Ary::create( $list )->unique();

        return $this;
    }

    /**
     * Returns the list of valid objects
     *
     * @return Object Returns a \cPHP\Ary object of the valid values
     */
    public function getList ()
    {
        return clone $this->list;
    }

    /**
     * Tests whether a value is in the list of valid options
     *
     * @param mixed $value The value to test
     * @return Boolean Returns whether a given value is in the list
     */
    public function exists ( $value )
    {
        return $this->list->contains($value);
    }

    /**
     * Adds a value to the list of valid values
     *
     * @param mixed $value The value to add
     * @return Object Returns a self reference
     */
    public function add ( $value )
    {
        if ( !$this->exists($value) )
            $this->list[] = $value;

        return $this;
    }

    /**
     * Removes a value to the list of valid options
     *
     * @param mixed $value The value to remove
     * @return Object Returns a self reference
     */
    public function remove ( $value )
    {
        $this->list = $this->list->without( $value )->values();
        return $this;
    }

    /**
     * Validates that the given value is in a given list
     *
     * @param mixed $value The value to validate
     * @return String Any errors encountered
     */
    protected function process ( $value )
    {
        if ( !$this->list->contains($value) )
            return "Invalid option";
    }

}

?>