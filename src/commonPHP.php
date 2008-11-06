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
define("cPHP_included", TRUE);

// mark the location of the commonPHP library
if ( !defined("cPHP_dir") ) {
    $commonPHPdir = str_replace("\\", "/", __DIR__);
    $commonPHPdir = rtrim( $commonPHPdir, "/" ) ."/";
    define("cPHP_dir", $commonPHPdir);
    unset($commonPHPdir);
}

if (!defined("cPHP_dir_functions"))
    define("cPHP_dir_functions", cPHP_dir ."functions/");

if (!defined("dir_classes"))
    define("cPHP_dir_classes", cPHP_dir ."classes/");

if (!defined("dir_interfaces"))
    define("cPHP_dir_interfaces", cPHP_dir ."interfaces/");

/**
 * Include the function files
 */
require_once cPHP_dir_functions ."general.php";
require_once cPHP_dir_functions ."numbers.php";
require_once cPHP_dir_functions ."strings.php";
require_once cPHP_dir_functions ."debug.php";

/**
 * This is temporary,... auto loader
 */
function __autoload ( $class ) {

    $class = explode("::", $class);
    array_shift( $class );

    $first = reset( $class );

    if ( $first == "iface" )
        $class = cPHP_dir_interfaces . implode( "/", array_slice( $class, 1 ) ) .".php";

    else
        $class = cPHP_dir_classes . implode( "/", $class ) .".php";

    if ( file_exists( $class ) )
        require_once $class;

}

/**
 * Set up custom exception handling
 */
set_exception_handler(function ( $exception ) {

    // If we are running in script mode, we don't need HTML
    if ( ::cPHP::Env::get()->local ) {
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
 * Function flags
 */

// Used by isEmpty to define what is allowed
define ("ALLOW_NULL", 1);
define ("ALLOW_FALSE", 2);
define ("ALLOW_ZERO", 4);
define ("ALLOW_BLANK", 8);
define ("ALLOW_SPACES", 16);
define ("ALLOW_EMPTY_ARRAYS", 32);

// Used by stripW to define what to keep
define ("ALLOW_UNDERSCORES", 64);
define ("ALLOW_NEWLINES", 128);
define ("ALLOW_TABS", 256);
define ("ALLOW_DASHES", 512);

/**
 * Take a snapshot of the environment
 */
::cPHP::Env::get();

?>