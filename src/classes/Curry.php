<?php
/**
 * Function Currying
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
 * @package Curry
 */

namespace cPHP;

/**
 * Base class for Argument Currying classes
 */
abstract class Curry implements \cPHP\iface\Filter
{

    /**
     * Any arguments to pass to curry to the left
     */
    protected $leftArgs = array();

    /**
     * Any arguments to pass to curry to the right
     */
    protected $rightArgs = array();

    /**
     * For slicing the input arguments, this is the offset.
     *
     * See array_slice for details
     */
    protected $offset = 0;

    /**
     * For slicing the input arguments, this is the length of the array to allow
     *
     * See array_slice for details
     */
    protected $length;

    /**
     * Static method for in-line create
     *
     * @param mixed $action The callable action being curried
     * @param mixed $args... any arguments to pass to the constructor
     * @return Returns a new instance
     */
    static public function create ( $action )
    {
        $instance = new static( $action );

        if ( func_num_args() > 1 ) {

            $args = func_get_args();
            array_shift( $args );
            $instance->setRightByArray( $args );

        }

        return $instance;
    }

    /**
     * Sets the leftward arguments
     *
     * @param mixed $args... Any arguments to curry to the left
     * @return object Returns a self reference
     */
    public function setLeft ()
    {
        $args = func_get_args();
        $this->leftArgs = array_values( $args );
        return $this;
    }

    /**
     * Sets the rightward arguments from an array
     *
     * @param mixed $args Any arguments to curry to the right
     * @return object Returns a self reference
     */
    public function setLeftByArray ( array $args = array() )
    {
        $this->leftArgs = array_values( $args );
        return $this;
    }

    /**
     * Returns the leftward argument list
     *
     * @return Array
     */
    public function getLeft ()
    {
        return $this->leftArgs;
    }

    /**
     * Removes any rightward arguments
     *
     * @return object Returns a self reference
     */
    public function clearLeft ()
    {
        $this->leftArgs = array();
        return $this;
    }

    /**
     * Sets the rightward arguments
     *
     * @param mixed $args... Any arguments to curry to the right
     * @return object Returns a self reference
     */
    public function setRight ()
    {
        $args = func_get_args();
        $this->rightArgs = array_values( $args );
        return $this;
    }

    /**
     * Sets the rightward arguments from an array
     *
     * @param mixed $args Any arguments to curry to the right
     * @return object Returns a self reference
     */
    public function setRightByArray ( array $args = array() )
    {
        $this->rightArgs = array_values( $args );
        return $this;
    }

    /**
     * Returns the rightward argument list
     *
     * @return Array
     */
    public function getRight ()
    {
        return $this->rightArgs;
    }

    /**
     * Removes any rightward arguments
     *
     * @return object Returns a self reference
     */
    public function clearRight ()
    {
        $this->rightArgs = array();
        return $this;
    }

    /**
     * Clears both the left and right arguments
     *
     * @return object Returns a self reference
     */
    public function clearArgs ()
    {
        return $this->clearRight()->clearLeft();
    }

    /**
     * Set the start offset used to slice up the call arguments
     *
     * @param Integer $offset
     * @return object Returns a self reference
     */
    public function setOffset ( $offset )
    {
        $this->offset = intval($offset);
        return $this;
    }

    /**
     * Returns the argument slicing offset
     *
     * @return Integer
     */
    public function getOffset ()
    {
        return $this->offset;
    }

    /**
     * Returns the argument slicing offset
     *
     * @return object Returns a self reference
     */
    public function clearOffset ()
    {
        $this->offset = 0;
        return $this;
    }

    /**
     * Set the length limit for slicing up the call arguments
     *
     * @param Integer $limit
     * @return object Returns a self reference
     */
    public function setLimit ( $limit )
    {
        $this->limit = intval($limit);
        return $this;
    }

    /**
     * Returns whether the argument slicing limit is set
     *
     * @return Boolean
     */
    public function issetLimit ()
    {
        return isset($this->limit);
    }

    /**
     * Returns the argument slicing limit
     *
     * @return FALSE|Integer Returns FALSE if no limit is set
     */
    public function getLimit ()
    {
        if ( !$this->issetLimit() )
            return FALSE;
        else
            return $this->limit;
    }

    /**
     * Clears the argument slicing limit
     *
     * @return object Returns a self reference
     */
    public function clearLimit ()
    {
        unset( $this->limit );
        return $this;
    }

    /**
     * Clears both the argument slicing limit and the offset
     *
     * @return object Returns a self reference
     */
    public function clearSlicing ()
    {
        return $this->clearLimit()->clearOffset();
    }

    /**
     * Clears all the settings from this instance
     *
     * @return object Returns a self reference
     */
    public function clear ()
    {
        return $this->clearArgs()->clearSlicing();
    }

    /**
     * Applies the slicing and combines the given arguments with the left args and right args
     *
     * @param array $args The arguments to curry
     * @return Returns the arguments to pass to the function
     */
    public function collectArgs ( array $args )
    {

        // Slicing is only needed if the offset is not 0, or they have inflicted a length limit
        if ( $this->offset != 0 || isset($this->limit) ) {

            if ( isset($this->limit) )
                $args = array_slice( $args, $this->offset, $this->limit );
            else
                $args = array_slice( $args, $this->offset );
        }

        return array_merge( $this->leftArgs, $args, $this->rightArgs );
    }

    /**
     * Internal function that actually executs the currying.
     *
     * It is given an array of arguments. It should call the method and return the results
     *
     * @param array $args The list of arguments to apply to this function
     * @return mixed Returns the results of the function call
     */
    abstract protected function rawExec ( array $args = array() );

    /**
     * Calls the method using the contents of an array as the arguments
     *
     * @param array $args The list of arguments to apply to this function
     * @return mixed Returns the results of the function call
     */
    public function apply ( array $args = array() )
    {
        return $this->rawExec( $this->collectArgs($args) );
    }

    /**
     * Calls the contained function with the given arguments
     *
     * @param mixed $args... The arguments to pass to the callback
     * @return mixed Returns the result of the invokation
     */
    public function exec ()
    {
        $args = func_get_args();
        return $this->rawExec( $this->collectArgs($args) );
    }

    /**
     * Calls the contained function with the given arguments
     *
     * @param mixed $args... Any arguments to apply to the function
     * @return mixed Returns the results of the invokation
     */
    public function __invoke ()
    {
        $args = func_get_args();
        return $this->rawExec( $this->collectArgs($args) );
    }

    /**
     * Method for use with the filtering objects. Invokes the contained method with the given value
     *
     * @param $value mixed The value to be filtered
     * @return mixed The result of the filtering
     */
    public function filter ( $value )
    {
        return $this->rawExec( $this->collectArgs( array($value) ) );
    }

}

?>