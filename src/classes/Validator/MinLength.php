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

namespace cPHP::Validator;

/**
 * Validates that a given value is the same or longer than a given length
 *
 * This will convert Boolean, Integers, Floats and Null to strings before
 * processing them. Anything else that isn't a string will cause validation to
 * return negative
 */
class MinLength extends ::cPHP::Validator
{

    /**
     * The string length the value must be less than or equal to
     */
    protected $length;

    /**
     * Constructor...
     *
     * @param Integer $length The string length the value must be greater than or equal to
     *      This must be greater than or equal to 0. Any negative numbers will be set to 0
     */
    public function __construct( $length )
    {
        $this->length = max( intval($length), 0 );
    }

    /**
     * Validates the given value
     *
     * @param mixed $value The value to validate
     * @return String Any errors encountered
     */
    protected function process ( $value )
    {
        if ( is_bool($value) || is_int($value) || is_float($value) || is_null($value) )
            $value = ::cPHP::strval($value);

        if ( !is_string($value) )
            return "Must be a string";

        if ( strlen($value) < $this->length ) {
            return ::cPHP::str::pluralize(
                    "Must not be shorter than ". $this->length ." character",
                    $this->length
                );
        }
    }

}

?>