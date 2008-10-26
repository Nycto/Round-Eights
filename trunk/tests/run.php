#!/usr/bin/php
<?php
/**
 * Given a file name, this will try to find and run it's unit test
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

$test = $_SERVER['argv'][1];

if ( $test == __FILE__ )
    die("This file can not be run as a unit test");

$test = preg_replace('/\.php$/i', '', $test);
$test = explode("/", $test);

$cutoff = array_search( "src", $test );

if ( $cutoff === FALSE ) {
    
    $cutoff = array_search( "tests", $test );
    
    if ( $cutoff === FALSE )
        die ( "Could not locate 'src' or 'tests' directory in given file: ". $$_SERVER['argv'][1] );
}

$test = array_slice($test, $cutoff + 1);

$file = __DIR__ ."/". implode("/", $test) .".php";

if ( !is_file($file) )
    die("Could not locate unit test file: ". $file);

system( "phpunit ". implode("_", $test) ." ". $file );

?>