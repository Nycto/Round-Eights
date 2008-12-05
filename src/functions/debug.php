<?php
/**
 * Debug related functions
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
 * @package debug
 */

namespace cPHP;

/**
 * Dumps the content of a variable to the output buffer
 *
 * This works exactly like var_dump, except it detects if it needs to wrap the output in <pre> tags
 *
 * @param mixed $value The value to dump
 */
function dump ( $value )
{
    if ( isset($_SERVER['SHELL']) ) {
        var_dump( $value );
    }
    else {
        echo "<pre>";
        var_dump( $value );
        echo "</pre>";
    }
}

/**
 * Returns a string containing information about this value
 *
 * @param mixed $value The value to return information about
 * @return String A shoft string describing the input
 */
function getDump ($value)
{

    if (is_bool($value))
        return "bool(". ($value?"TRUE":"FALSE") .")";

    else if (is_null($value))
        return "null()";

    else if (is_int($value))
        return "int(". $value .")";

    else if (is_float($value))
        return "float(". $value .")";

    else if (is_string($value)) {
        return "string('"
            .str_replace(
                    Array("\n", "\r", "\t"),
                    Array('\n', '\r', '\t'),
                    \cPHP\str\truncate( addslashes($value), 50, "'...'")
                )
            ."')";
    }

    else if (is_array($value)) {

        if ( count($value) == 0 )
            return "array(0)";

        $output = array();

        $i = 0;
        foreach( $value AS $key => $val ) {

            $i++;

            $output[] =
                getDump($key)
                ." => "
                . ( is_array($val) ? "array(". count($val) .")" : getDump($val) );

            if ( $i == 2 )
                break;
        }

        return "array(". count($value) .")("
            .implode(", ", $output)
            .( count($value) > 2 ? ",..." : "" )
            .")";


    }

    else if (is_object($value))
        return "object(". get_class($value) .")";

    else if (is_resource($value))
        return "resource(". get_resource_type($value) .")";

    else
        return "unknown(". gettype($value) .")";

}

?>