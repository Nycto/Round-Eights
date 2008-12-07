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
 * @package curry
 */

namespace cPHP\Curry;

/**
 * The most basic curry class. Invokes a defined callback
 */
class Call extends \cPHP\Curry
{

    /**
     * The callback to be invoked
     */
    protected $callback;

    /**
     * Constructor...
     *
     * @param mixed $callback The callback to invoke
     * @param mixed $args... Any rightward arguments
     */
    public function __construct ( $callback )
    {
        if ( !is_callable($callback) )
            throw new \cPHP\Exception\Argument( 0, "Callback", "Must be Callable" );

        $this->callback = $callback;
    }

    /**
     * Invokes the current callback with the given array of arguments and returns the results
     *
     * @param $args Array The arguments to apply to the callback
     * @return mixed
     */
    protected function rawExec ( array $args = array() )
    {

        return call_user_func_array(

                // For object, skip the shortcuts and just jump straight to the invoke method
                is_object($this->callback) ?
                    array( $this->callback, "__invoke") : $this->callback,

                $this->collectArgs( $args )

            );

    }

}

?>