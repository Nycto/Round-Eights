<?php
/**
 * Base Validator class
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

namespace cPHP;

/**
 * This provides an interface for comparing a value to a set of parameters
 */
abstract class Validator extends \cPHP\Validator\ErrorList implements \cPHP\iface\Validator
{

    /**
     * Static method for creating a new validator instance
     *
     * This takes the called function and looks for a class under
     * the \cPHP\Validator namespace.
     *
     * @throws \cPHP\Exception\Argument Thrown if the validator class can't be found
     * @param String $validator The validator class to create
     * @param array $args Any constructor args to use during instantiation
     * @return Object Returns a new \cPHP\Validator subclass
     */
    static public function __callStatic ( $validator, $args )
    {
        $validator = "\\cPHP\\Validator\\". trim( \cPHP\strval($validator) );

        if ( !class_exists($validator, true) ) {
            throw new \cPHP\Exception\Argument(
                    0,
                    "Validator Class Name",
                    "Validator could not be found in \cPHP\Validator namespace"
                );
        }

        if ( !\cPHP\kindOf( $validator, "\cPHP\iface\Validator") ) {
            throw new \cPHP\Exception\Argument(
                    0,
                    "Validator Class Name",
                    "Class does not implement \cPHP\iface\Validator"
                );
        }

        if ( count($args) <= 0 ) {
            return new $validator;
        }
        else if ( count($args) == 1 ) {
            return new $validator( reset($args) );
        }
        else {
            $refl = new ReflectionClass( $validator );
            return $refl->newInstanceArgs( $args );
        }
    }

    /**
     * Performs the validation and returns the result
     *
     * @param mixed $value The value to validate
     * @return Object Returns an instance of \cPHP\Validator\Result
     */
    public function validate ( $value )
    {
        // Invoke the internal validator
        $result = $this->process( $value );

        // Normalize the results
        if ( \cPHP\Ary::is($result) )
            $result = \cPHP\Ary::create( $result )->flatten()->collect("cPHP\\strval")->compact()->get();

        elseif ( $result instanceof \cPHP\Validator\Result )
            $result = $result->getErrors();

        elseif ( is_null($result) || is_bool($result) || $result === 0 || $result === 0.0 )
            $result = null;

        else
            $result = \cPHP\strval( $result );

        // Boot up the results of the validation process
        $output = new \cPHP\Validator\Result( $value );

        // If the internal validator returned a non-empty value
        // (either an array with values or a non-blank string)
        if ( !\cPHP\isEmpty($result) ) {

            // If this validator is hooked up with a set of custom error messages,
            // use them instead of what the result returned
            if ( $this->hasErrors() )
                $output->addErrors( $this->getErrors() );
            else
                $output->addErrors( $result );

        }

        return $output;
    }

    /**
     * Runs the validation and returns whether the value passes or not
     *
     * @param mixed $value The value to validate
     * @return Boolean
     */
    public function isValid ( $value )
    {
        return $this->validate( $value )->isValid();
    }

    /**
     * The function that actually performs the validation
     *
     * @param mixed $value It will be given the value to validate
     * @return mixed Should return any errors that are encountered.
     *      This can be an array, a string, a \cPHP\Validator\Result instance
     */
    abstract protected function process ($value);

}

?>