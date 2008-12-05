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
 * Uses a given callback to validate a value
 *
 * The callback is invoked with one argument, which is the value being validated.
 * The result of the callback will be used as the error message for the validator.
 * Different types of return values will be treated different ways:
 *
 * Arrays and traversable objects will be converted to arrays, flattened, stringized
 * and compacted. Anything left will be considered an error message. If it turns
 * out empty, validation will pass
 *
 * Strings, Boolean, Null, False, Integers and Floats will be converted to strings.
 * If they are considered empty according to the "isEmpty" standards, the value
 * will pass validation. Otherwise, the string value will be used as the error message.
 */
class Callback extends \cPHP\Validator
{

    /**
     * The callback that will be invoked
     */
    protected $callback;

    /**
     * Constructor...
     *
     * @param mixed $callback The callback to use for validation
     */
    public function __construct( $callback )
    {

        if ( !is_callable($callback) )
            throw new \cPHP\Exception\Argument(0, "Callback", "Must be callable");

        $this->callback = $callback;
    }

    /**
     * Validates the given value
     *
     * @param mixed $value The value to validate
     * @return String Any errors encountered
     */
    protected function process ( $value )
    {
        return call_user_func( $this->callback, $value );
    }

}

?>