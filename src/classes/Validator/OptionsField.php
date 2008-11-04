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
 * Given a cPHP::Form::Options object, this determines whether the given value
 * is a valid selection
 */
class OptionsField extends ::cPHP::Validator
{

    /**
     * The field that is being validated
     */
    protected $field;

    /**
     * Constructor...
     *
     * @param Object $field The cPHP::Form::Options field to compare the value to
     */
    public function __construct ( ::cPHP::Form::Options $field )
    {
        $this->field = $field;
    }

    /**
     * Validates a URL
     *
     * This will always fail for anything that isn't a basic value. That is, boolean,
     * null, integers, floats, or strings.
     *
     * @param mixed $value The value to validate
     * @return String Any errors encountered
     */
    protected function process ( $value )
    {
        if ( !::cPHP::is_basic($value) || !$this->field->getOptions()->keyExists($value) )
            return "Value is not a valid selection";
    }

}

?>