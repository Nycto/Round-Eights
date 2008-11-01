<?php
/**
 * Unit Test File
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
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * unit tests
 */
class functions_debug extends PHPUnit_Framework_TestCase
{

    public function testGetDump ()
    {
        $this->assertEquals( "bool(TRUE)", cPHP::getDump( TRUE ) );
        $this->assertEquals( "bool(FALSE)", cPHP::getDump( FALSE ) );

        $this->assertEquals( "null()", cPHP::getDump( null ) );

        $this->assertEquals( "int(1)", cPHP::getDump( 1 ) );

        $this->assertEquals( "float(10.5)", cPHP::getDump( 10.5 ) );

        $this->assertEquals( "string('some string')", cPHP::getDump( "some string" ) );
        $this->assertEquals(
                "string('some string that is goi'...'after fifty characters')",
                cPHP::getDump( "some string that is going to be trimmed after fifty characters" )
            );
        $this->assertEquals( "string('some\\nstring\\twith\\rbreaks')", cPHP::getDump( "some\nstring\twith\rbreaks" ) );

        $this->assertEquals( "array(0)", cPHP::getDump( array() ) );
        $this->assertEquals( "array(1)(int(0) => int(5))", cPHP::getDump( array( 5 ) ) );
        $this->assertEquals(
                "array(2)(int(0) => string('string'), int(20) => float(1.5))",
                cPHP::getDump( array( "string", 20 => 1.5 ) )
            );
        $this->assertEquals(
                "array(5)(int(0) => int(1), int(1) => int(2),...)",
                cPHP::getDump( array( 1, 2, 3, 4, 20 ) )
            );
        $this->assertEquals(
                "array(1)(int(0) => array(2))",
                cPHP::getDump( array( array( 5, 6 ) ) )
            );

        $this->assertEquals( "object(Exception)", cPHP::getDump( new Exception ) );

        $this->assertEquals( "resource(stream)", cPHP::getDump( fopen( __FILE__, "r" ) ) );
    }

}

?>