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
 *
 * Because this is a global registry, the order in which these tests is important.
 * Each test depends upon the previous
 */
class classes_db extends PHPUnit_Framework_TestCase
{

    public function getMockLinks ()
    {
        static $linkOne, $linkTwo;

        if ( !isset($linkOne) ) {
            $linkOne = $this->getMock(
                    "\cPHP\iface\DB\Link",
                    array("query", "quote", "escape")
                );

            $linkTwo = $this->getMock(
                    "\cPHP\iface\DB\Link",
                    array("query", "quote", "escape")
                );
        }

        return array( $linkOne, $linkTwo );
    }

    public function testInitialState ()
    {
        // Test the initial state
        $this->assertNull( \cPHP\DB::getDefault() );
        $this->assertSame( array(), \cPHP\DB::getLinks() );
    }

    public function testAddLinks ()
    {
        list( $linkOne, $linkTwo ) = $this->getMockLinks();

        // Add the mock connections
        $this->assertNull( \cPHP\DB::setLink( 'linkOne', $linkOne ) );
        $this->assertNull( \cPHP\DB::setLink( 'linkTwo', $linkTwo ) );

        // Make sure the were loaded in correctly
        $this->assertSame(
                array( 'linkOne' => $linkOne, 'linkTwo' => $linkTwo ),
                \cPHP\DB::getLinks()
            );

        // Ensure that the first connection was automatically made default
        $this->assertSame( $linkOne, \cPHP\DB::getDefault() );

        // We shouldn't be able to add with a blank label
        try {
            \cPHP\DB::setLink( '', $linkTwo );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }

    }

    public function testGet ()
    {
        list( $linkOne, $linkTwo ) = $this->getMockLinks();

        // Ensure that the default connection is returned
        $this->assertSame( $linkOne, \cPHP\DB::get() );

        // Make sure we can pull specific connections
        $this->assertSame( $linkOne, \cPHP\DB::get('linkOne') );
        $this->assertSame( $linkTwo, \cPHP\DB::get('linkTwo') );


        // A blank string should cause an error
        try {
            \cPHP\DB::get( '' );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }

        // thrown when a connection label doesn't exist
        try {
            \cPHP\DB::get( 'this doesnt exist' );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Index $err ) {
            $this->assertSame("Connection does not exist", $err->getMessage());
        }

    }

    public function testSetDefault ()
    {
        list( $linkOne, $linkTwo ) = $this->getMockLinks();

        // Change the default connection
        $this->assertNull( \cPHP\DB::setDefault('linkTwo') );
        $this->assertSame( $linkTwo, \cPHP\DB::getDefault() );

        try {
            \cPHP\DB::setDefault( NULL );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }

        try {
            \cPHP\DB::setDefault( '' );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }

        try {
            \cPHP\DB::setDefault( FALSE );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }

        // thrown when a connection label doesn't exist
        try {
            \cPHP\DB::get( 'this doesnt exist' );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Index $err ) {
            $this->assertSame("Connection does not exist", $err->getMessage());
        }

    }

}

?>