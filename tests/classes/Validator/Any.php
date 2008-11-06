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
class classes_validator_any extends PHPUnit_Framework_TestCase
{

    public function testNoValidators ()
    {
        $any = new ::cPHP::Validator::Any;

        $result = $any->validate("example value");
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertTrue( $result->isValid() );

    }

    public function testInvalidResult ()
    {
        $valid = $this->getMock("cPHP::iface::Validator", array("validate", "isValid"));
        $valid->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue("This is an invalid result") );

        $any = new ::cPHP::Validator::Any( $valid );
        $this->assertEquals( array($valid), $any->getValidators()->get() );

        try {
            $any->validate("example value");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( ::cPHP::Exception::Data $err ) {}
    }

    public function testFirstValid ()
    {

        $valid1 = $this->getMock("cPHP::iface::Validator", array("validate", "isValid"));
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( new ::cPHP::Validator::Result("example value") ) );

        // This should never be called because the first validator should short circuit things
        $valid2 = $this->getMock("cPHP::iface::Validator", array("validate", "isValid"));
        $valid2->expects( $this->never() )
            ->method( "validate" );


        $any = new ::cPHP::Validator::Any( $valid1, $valid2 );

        $result = $any->validate("example value");

        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertTrue( $result->isValid() );

    }

    public function testSecondValid ()
    {
        $result1 = new ::cPHP::Validator::Result("example value");
        $result1->addError("This is an Error");

        $valid1 = $this->getMock("cPHP::iface::Validator", array("validate", "isValid"));
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result1 ) );

        $valid2 = $this->getMock("cPHP::iface::Validator", array("validate", "isValid"));
        $valid2->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( new ::cPHP::Validator::Result("example value") ) );


        $any = new ::cPHP::Validator::Any( $valid1, $valid2 );

        $result = $any->validate("example value");

        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertTrue( $result->isValid() );

    }

    public function testOneInvalid ()
    {

        $result1 = new ::cPHP::Validator::Result("example value");
        $result1->addError("This is an Error");

        $valid1 = $this->getMock("cPHP::iface::Validator", array("validate", "isValid"));
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result1 ) );


        $any = new ::cPHP::Validator::Any( $valid1 );

        $result = $any->validate("example value");

        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("This is an Error"),
                $result->getErrors()->get()
            );

    }

    public function testMultipleInvalid ()
    {

        $result1 = new ::cPHP::Validator::Result("example value");
        $result1->addError("This is an Error");

        $valid1 = $this->getMock("cPHP::iface::Validator", array("validate", "isValid"));
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result1 ) );


        $result2 = new ::cPHP::Validator::Result("example value");
        $result2->addError("This is another Error");

        $valid2 = $this->getMock("cPHP::iface::Validator", array("validate", "isValid"));
        $valid2->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result2 ) );


        $any = new ::cPHP::Validator::Any( $valid1, $valid2 );

        $result = $any->validate("example value");

        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("This is an Error", "This is another Error"),
                $result->getErrors()->get()
            );

    }

    public function testDuplicateErrors ()
    {

        $result1 = new ::cPHP::Validator::Result("example value");
        $result1->addError("This is an Error");

        $valid1 = $this->getMock("cPHP::iface::Validator", array("validate", "isValid"));
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result1 ) );


        $result2 = new ::cPHP::Validator::Result("example value");
        $result2->addError("This is an Error");

        $valid2 = $this->getMock("cPHP::iface::Validator", array("validate", "isValid"));
        $valid2->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result2 ) );


        $any = new ::cPHP::Validator::Any( $valid1, $valid2 );

        $result = $any->validate("example value");

        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("This is an Error"),
                $result->getErrors()->get()
            );

    }

}

?>