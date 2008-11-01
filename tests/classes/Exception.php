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
class classes_exception extends PHPUnit_Framework_TestCase
{

    public function testMessage ()
    {
        $err = new cPHP::Exception();
        $this->assertFalse( $err->issetMessage() );

        $err = new cPHP::Exception("This is a message");
        $this->assertTrue( $err->issetMessage() );
        $this->assertEquals( "This is a message", $err->getMessage() );
    }

    public function testCode ()
    {
        $err = new cPHP::Exception();
        $this->assertFalse( $err->issetCode() );

        $err = new cPHP::Exception("This is a message", 543);
        $this->assertTrue( $err->issetCode() );
        $this->assertEquals( 543, $err->getCode() );
    }

    public function testGetTraceByOffset ()
    {
        $err = new cPHP::Exception();

        $this->assertThat( $err->getTraceByOffset(0), $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals(
                __FUNCTION__,
                $err->getTraceByOffset(0)->offsetGet("function")
            );
    }

    public function testGetTraceCount ()
    {
        $err = new cPHP::Exception();

        $this->assertThat( $err->getTraceCount(0), $this->isType("int") );
        $this->assertThat( $err->getTraceCount(0), $this->greaterThan(0) );
    }

    public function testFault ()
    {
        $err = new cPHP::Exception();

        // test whether setFault and issetFault work
        $this->assertFalse( $err->issetFault() );
        $this->assertSame( $err, $err->setFault(0) );
        $this->assertTrue( $err->issetFault() );

        $this->assertEquals( 0, $err->getFaultOffset() );

        // Now reset the fault and test shiftFault without any arguments
        $this->assertSame( $err, $err->shiftFault() );
        $this->assertEquals( 1, $err->getFaultOffset() );

        // Make sure getFault returns an array
        $this->assertThat( $err->getFault(), $this->isInstanceOf("cPHP::Ary") );

        // test unsetFault
        $this->assertSame( $err, $err->unsetFault() );
        $this->assertFalse( $err->issetFault() );


        // Test shift Fault when no fault is currently set
        $err->shiftFault();
        $this->assertEquals(0, $err->getFaultOffset());

    }

    public function testData ()
    {
        $err = new cPHP::Exception;

        $this->assertSame( $err, $err->addData("Data Label", 20) );
        $this->assertThat( $err->getData(), $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals( array("Data Label" => 20), $err->getData()->get() );
        $this->assertEquals( 20, $err->getDataValue("Data Label") );

    }

    public function testThrowing ()
    {
        $this->setExpectedException('cPHP::Exception');
        throw new cPHP::Exception;
    }

}

?>