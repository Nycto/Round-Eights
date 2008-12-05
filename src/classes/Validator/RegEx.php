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
 * Validates that a value matches a given regular expression
 *
 * This will convert Boolean, Integers, Floats and Null to strings before
 * processing them. Anything else that isn't a string will cause validation to
 * return negative.
 *
 * This uses preg_match to execute the regular expression and does not add the
 * wrap characters. You must include those on instantiation
 */
class RegEx extends \cPHP\Validator
{

    /**
     * The Regular Expression to compare the value to
     */
    protected $regex;

    /**
     * Constructor...
     *
     * @param String $regex The Regular Expression to compare the value to
     */
    public function __construct( $regex )
    {
        $regex = \cPHP\strVal( $regex );
        if ( \cPHP\isEmpty($regex) )
            throw new \cPHP\Exception\Argument(0, "Regular Expression", "Must not be empty");
        $this->regex = $regex;
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
            $value = \cPHP\strval($value);

        if ( !is_string($value) )
            return "Must be a string";

        if ( !preg_match($this->regex, $value) )
            return "Must match the following regular expression: ". $this->regex;
    }

}

?>