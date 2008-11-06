<?php
/**
 * Base Class for a collection of validators
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

namespace cPHP::Validator;

/**
 * An interface for grouping a set of validators in to one object
 */
abstract class Collection extends cPHP::Validator
{

    /**
     * The list of validators contained in this instance
     */
    protected $validators = array();

    /**
     * Constructor
     *
     * Allows you to add validators on construction
     *
     * @param object $validators...
     */
    public function __construct ()
    {
        if ( func_num_args() > 0 ) {
            $args = func_get_args();
            $this->addMany( $args );
        }
    }

    /**
     * Adds a validator to this instance
     *
     * @param Object The validator to addd to this instance
     * @return Object Returns a self reference
     */
    public function add( $validator )
    {
        if ( is_object($validator) ) {

            if ( !$validator instanceof cPHP::iface::Validator )
                throw new cPHP::Exception::Argument( 0, "Validator", "Must be an instance of cPHP::iface::Validator" );

        }
        else {
            $validator = ::cPHP::strval( $validator );

            if ( !is_subclass_of($validator, "cPHP::iface::Validator") ) {

                $refl = new ReflectionClass( $validator );
                if ( !$refl->implementsInterface( "cPHP::iface::Validator" ) )
                    throw new cPHP::Exception::Argument( 0, "Validator", "Must be an instance of cPHP::iface::Validator" );

            }

            $validator = new $validator;
        }

        $this->validators[] = $validator;
        return $this;
    }

    /**
     * Returns the list of validators contained in this instance
     *
     * @return object Returns a cPHP::Ary object
     */
    public function getValidators ()
    {
        return new ::cPHP::Ary( $this->validators );
    }

    /**
     * Adds many validators to this instance at once
     *
     * @param mixed $validators... Any arguments passed will be flattened down and filtered
     * @return Object Returns a self reference
     */
    public function addMany ( $validators )
    {
        $validators = func_get_args();
        ::cPHP::Ary::create( $validators )
            ->flatten()
            ->filter(function($validator) {
                return $validator instanceof ::cPHP::iface::Validator;
            })
            ->each(array($this, "add"));
        return $this;
    }

}

?>