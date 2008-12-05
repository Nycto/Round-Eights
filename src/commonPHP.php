<?php
/**
 * Primary commonPHP include file
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
 */

// Mark that commonPHP has been included
define("cPHP_INCLUDED", TRUE);

// mark the location of the commonPHP library
if ( !defined("cPHP_DIR") ) {
    $commonPHPdir = str_replace("\\", "/", __DIR__);
    $commonPHPdir = rtrim( $commonPHPdir, "/" ) ."/";
    define("cPHP_DIR", $commonPHPdir);
    unset($commonPHPdir);
}

if (!defined("cPHP_DIR_FUNCTIONS"))
    define("cPHP_DIR_FUNCTIONS", cPHP_DIR ."functions/");

if (!defined("cPHP_DIR_CLASSES"))
    define("cPHP_DIR_CLASSES", cPHP_DIR ."classes/");

if (!defined("cPHP_DIR_INTERFACES"))
    define("cPHP_DIR_INTERFACES", cPHP_DIR ."interfaces/");

/**
 * Include the function files
 */
require_once cPHP_DIR_FUNCTIONS ."general.php";
require_once cPHP_DIR_FUNCTIONS ."numbers.php";
require_once cPHP_DIR_FUNCTIONS ."strings.php";
require_once cPHP_DIR_FUNCTIONS ."debug.php";

/**
 * This is temporary,... auto loader
 */
function __autoload ( $class ) {

    $class = explode("\\", $class);
    $class = array_filter( $class );
    array_shift( $class );

    $first = reset( $class );

    if ( $first == "iface" )
        $class = cPHP_DIR_INTERFACES . implode( "/", array_slice( $class, 1 ) ) .".php";

    else
        $class = cPHP_DIR_CLASSES . implode( "/", $class ) .".php";

    if ( file_exists( $class ) )
        require_once $class;

}

/**
 * Set up custom exception handling
 */
set_exception_handler(function ( $exception ) {

    // If we are running in script mode, we don't need HTML
    if ( \cPHP\Env::get()->local ) {
        echo "FATAL ERROR: Uncaught Exception Thrown:\n" .$exception;
    }
    else {

        echo "<div class='phpException'>\n"
            ."<h3>Fatal Error: Uncaught Exception Thrown</h3>\n";

        if ( $exception instanceof GeneralError )
            echo $exception->getVerboseHTML();
        else
            echo "<pre>". $exception ."</pre>";

        echo "</div>";

    }
});

/**
 * Take a snapshot of the environment
 */
\cPHP\Env::get();

?>