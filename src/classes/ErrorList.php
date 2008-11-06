<?php
/**
 * Class used to collect a list of errors
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
 * @package Validator
 */

namespace cPHP;

/**
 * Helper class for managing a list of errors
 */
class ErrorList
{

    /**
     * The list of errors in this instance
     */
    private $errors = array();

    /**
     * Adds a new error to this instance
     *
     * @param String $message The error message to add
     * @return object Returns a self reference
     */
    public function addError ( $message )
    {
        $message = ::cPHP::strval($message);

        if ( ::cPHP::isEmpty($message) )
            throw new cPHP::Exception::Argument( 0, "Error Message", "Must Not Be Empty" );

        if ( !in_array($message, $this->errors) )
            $this->errors[] = $message;

        return $this;
    }

    /**
     * Adds multiple errors at once
     *
     * This method accepts any number of arguments. They will be flattened down,
     * converted to strings and added as errors
     *
     * @param String|Array $errors... Errors to add to this instance
     * @return Object Returns a self reference
     */
    public function addErrors ( $errors )
    {
        $errors = func_get_args();
        cPHP::Ary::create( $errors )
            ->flatten()
            ->compact()
            ->unique()
            ->each(array($this, "addError"));
        return $this;
    }

    /**
     * Returns the errors contained in this instance
     *
     * @return array
     */
    public function getErrors ()
    {
        return new ::cPHP::Ary( $this->errors );
    }

    /**
     * Clears all the errors from
     *
     * @return object Returns a self reference for chaining
     */
    public function clearErrors ()
    {
        $this->errors = array();
        return $this;
    }

    /**
     * Clears all other errors and sets
     *
     * @param String $message The error message to add
     * @return object Returns a self reference for chaining
     */
    public function setError ( $message )
    {
        return $this->clearErrors()->addError( $message );
    }

    /**
     * Returns whether or not this instance has any errors contained in it
     *
     * @return Boolean
     */
    public function hasErrors ()
    {
        return count( $this->errors ) > 0 ? TRUE : FALSE;
    }

    /**
     * Returns the first error contained in this instance
     *
     * @return String|Null Returns NULL if there aren't any errors in this instance
     */
    public function getFirstError ()
    {
        if ( count($this->errors) == 0 )
            return NULL;

        return reset( $this->errors );
    }

}

?>