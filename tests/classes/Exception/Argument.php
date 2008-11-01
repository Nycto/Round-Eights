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

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_exception_argument extends PHPUnit_Framework_TestCase
{

    // Returns an thrown exception
    public function getThrown ()
    {
        try {
            $throw = function ( $arg1, $arg2 ) {
                throw new ::cPHP::Exception::Argument(0, "test", "From our sponsors", 505, 0);
            };
            $throw("arg value", "other arg");
        }
        catch ( ::cPHP::Exception::Argument $err ) {
            return $err;
        }
    }

    public function testConstruct ()
    {
        $err = $this->getThrown();

        $this->assertEquals( 0, $err->getArgOffset() );
        $this->assertEquals( "From our sponsors", $err->getMessage() );
        $this->assertEquals( 505, $err->getCode() );

        $this->assertThat( $err->getData(), $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals( array("Arg Label" => "test"), $err->getData()->get() );

        $this->assertEquals( 0, $err->getFaultOffset() );

    }

    public function testArg ()
    {
        $err = $this->getThrown();

        $this->assertTrue( $err->issetArg() );
        $this->assertEquals( 0, $err->getArgOffset() );
        $this->assertEquals( "arg value", $err->getArgData() );

        $this->assertSame( $err, $err->setArg(1) );
        $this->assertEquals( "other arg", $err->getArgData() );

        $this->assertSame( $err, $err->unsetArg() );
    }

    public function testUnsetArg ()
    {
        $err = $this->getThrown();

        $this->assertTrue( $err->issetArg() );

        $this->assertSame( $err, $err->unsetArg() );

        $this->assertFalse( $err->issetArg() );
        $this->assertFalse( $err->getArgOffset() );
        $this->assertNull( $err->getArgData() );
    }

    public function testFaultChange ()
    {
        $err = $this->getThrown("garbage arg", "arg v2", "yet another");

        $this->assertEquals( "arg value", $err->getArgData() );

        $err->shiftFault();

        $this->assertTrue( $err->issetArg() );
        $this->assertEquals( "garbage arg", $err->getArgData() );
    }

    public function testNoFault ()
    {
        $err = $this->getThrown();

        $this->assertTrue( $err->issetFault() );
        $this->assertTrue( $err->issetArg() );

        $this->assertSame( $err, $err->unsetFault() );

        $this->assertFalse( $err->issetFault() );
        $this->assertFalse( $err->issetArg() );


        $this->assertSame( $err, $err->setArg(1) );

        $this->assertTrue( $err->issetFault() );
        $this->assertTrue( $err->issetArg() );

        $this->assertEquals( 0, $err->getFaultOffset() );
        $this->assertEquals( 1, $err->getArgOffset() );
    }

    public function testNoArgs ()
    {
        $err = $this->getThrown();

        // Shift the fault to a function call that doesn't have any arguments
        $err->shiftFault();

        $this->assertFalse( $err->issetArg() );
        $this->assertFalse( $err->getArgOffset() );
        $this->assertNull( $err->getArgData() );
    }

    public function testDetailsString ()
    {
        $err = $this->getThrown();

        $this->assertContains("Arg Offset: 0", $err->getDetailsString());
        $this->assertContains("Arg Value: string('arg value')", $err->getDetailsString());
        $this->assertContains("Arg Label: test", $err->getDetailsString());
    }

    public function testDetailsHTML ()
    {
        $err = $this->getThrown();

        $this->assertContains("<dt>Arg Offset</dt><dd>0</dd>", $err->getDetailsHTML());
        $this->assertContains("<dt>Arg Value</dt><dd>string('arg value')</dd>", $err->getDetailsHTML());
        $this->assertContains("<dt>Arg Label</dt><dd>test</dd>", $err->getDetailsHTML());
    }

}

?>