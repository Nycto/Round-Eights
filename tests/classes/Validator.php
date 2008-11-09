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
class classes_validator extends PHPUnit_Framework_TestCase
{

    public function getMockValidator ( $return )
    {
        $mock = $this->getMock("cPHP::Validator", array("process"));
        $mock->expects( $this->once() )
            ->method( "process" )
            ->with( $this->equalTo("To Validate") )
            ->will( $this->returnValue( $return ) );

        return $mock;
    }

    public function testCallStatic ()
    {
        $validator = cPHP::Validator::Email();
        $this->assertThat( $validator, $this->isInstanceOf("cPHP::Validator::Email") );

        try {
            cPHP::Validator::ThisIsNotAValidator();
            $this->fail("An expected exception was not thrown");
        }
        catch ( cPHP::Exception::Argument $err ) {
            $this->assertSame( "Validator could not be found in cPHP::Validator namespace", $err->getMessage() );
        }

        try {
            cPHP::Validator::Result();
            $this->fail("An expected exception was not thrown");
        }
        catch ( cPHP::Exception::Argument $err ) {
            $this->assertSame( "Class does not implement cPHP::iface::Validator", $err->getMessage() );
        }
    }

    public function testNullResult ()
    {
        $mock = $this->getMockValidator ( NULL );

        $result = $mock->validate("To Validate");

        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertTrue( $result->isValid() );
    }

    public function testFloatResult ()
    {
        $mock = $this->getMockValidator ( 278.09 );

        $result = $mock->validate("To Validate");

        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals( array("278.09"), $result->getErrors()->get() );


        $mock = $this->getMockValidator ( 0.0 );
        $this->assertTrue( $mock->isValid("To Validate") );
    }

    public function testIntegerResult ()
    {
        $mock = $this->getMockValidator ( 278 );

        $result = $mock->validate("To Validate");

        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals( array("278"), $result->getErrors()->get() );


        $mock = $this->getMockValidator ( 0 );
        $this->assertTrue( $mock->isValid("To Validate") );
    }

    public function testBooleanResult ()
    {
        $mock = $this->getMockValidator ( TRUE );
        $this->assertTrue( $mock->isValid("To Validate") );

        $mock = $this->getMockValidator ( FALSE );
        $this->assertTrue( $mock->isValid("To Validate") );
    }

    public function testStringError ()
    {
        $mock = $this->getMockValidator ("This is an Error");

        $result = $mock->validate("To Validate");

        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals( array("This is an Error"), $result->getErrors()->get() );
    }

    public function testArrayError ()
    {
        $mock = $this->getMockValidator( array("First Error", "Second Error") );

        $result = $mock->validate("To Validate");

        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals( array("First Error", "Second Error"), $result->getErrors()->get() );



        $mock = $this->getMockValidator( array( array("First Error"), "", "Second Error") );

        $result = $mock->validate("To Validate");

        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals( array("First Error", "Second Error"), $result->getErrors()->get() );
    }

    public function testEmptyArrayError ()
    {
        $mock = $this->getMockValidator( array() );

        $result = $mock->validate("To Validate");

        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertTrue( $result->isValid() );



        $mock = $this->getMockValidator( array( "", FALSE, "  " ) );

        $result = $mock->validate("To Validate");

        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertTrue( $result->isValid() );
    }

    public function testResultError ()
    {

        $return = new ::cPHP::Validator::Result("To Validate");
        $return->addErrors("First Error", "Second Error");
        $mock = $this->getMockValidator( $return );

        $result = $mock->validate("To Validate");

        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals( array("First Error", "Second Error"), $result->getErrors()->get() );

    }

    public function testEmptyResultError ()
    {

        $return = new ::cPHP::Validator::Result("To Validate");
        $mock = $this->getMockValidator( $return );

        $result = $mock->validate("To Validate");

        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertTrue( $result->isValid() );

    }

    public function testCustomErrors ()
    {

        $mock = $this->getMockValidator( "Default Error" );
        $mock->addError("Custom Error Message");

        $result = $mock->validate("To Validate");

        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals( array("Custom Error Message"), $result->getErrors()->get() );

    }

    public function testIsValid ()
    {
        $passes = $this->getMockValidator( NULL );
        $this->assertTrue( $passes->isValid("To Validate") );

        $fails = $this->getMockValidator( "Default Error" );
        $this->assertFalse( $fails->isValid("To Validate") );
    }
}

?>