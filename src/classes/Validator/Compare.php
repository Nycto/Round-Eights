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
 * Takes a comparison operator and a value and validates the given value against it
 */
class Compare extends ::cPHP::Validator
{

    /**
     * The operator to use for comparison
     */
    protected $operator;

    /**
     * The value to compare against
     */
    protected $versus;

    /**
     * Constructor...
     *
     * @param String $operator The operator to use for comparison
     * @param mixed $versus The value to compare against
     */
    public function __construct( $operator, $versus )
    {

        $operator = trim( ::cPHP::strval($operator) );

        if ( !preg_match( '/^(?:<=?|>=?|={1,3}|<>|!={1,2})$/', $operator ) )
            throw new ::cPHP::Exception::Argument( 0, "Comparison Operator", "Unsupported comparison operator" );

        $this->operator = $operator;

        $this->versus = $versus;
    }

    /**
     * Validates the given value
     *
     * @param mixed $value The value to validate
     * @return String Any errors encountered
     */
    protected function process ( $value )
    {

        switch( $this->operator ) {

            case "<":
                if ($value >= $this->versus)
                    return "Must be less than ". $this->versus;
                break;

            case ">":
                if ($value <= $this->versus)
                    return "Must be greater than ". $this->versus;
                break;

            case "<=":
                if ($value > $this->versus)
                    return "Must be less than or equal to ". $this->versus;
                break;

            case ">=":
                if ($value < $this->versus)
                    return "Must be greater than or equal to ". $this->versus;
                break;

            case "===":
                if ($value !== $this->versus)
                    return "Must be exactly equal to ". $this->versus;
                break;

            case "==":
            case "=":
                if ($value != $this->versus)
                    return "Must be equal to ". $this->versus;
                break;

            case "!==":
                if ($value === $this->versus)
                    return "Must not be exactly equal to ". $this->versus;
                break;

            case "!=":
            case "<>":
                if ($value == $this->versus)
                    return "Must not be equal to ". $this->versus;
                break;

        }

    }

}

?>