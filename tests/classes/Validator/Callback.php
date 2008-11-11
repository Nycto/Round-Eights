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
 * This is a stub function used to test the callback validator
 *
 * @param mixed $value The value being validated
 * @return null|string
 */
function stub_validator_callback_func ( $value )
{
    if ( $value != "cheese" )
        return "Value must be cheese";
}

/**
 * unit tests
 */
class classes_validator_callback extends PHPUnit_Framework_TestCase
{

    static public function staticCallbackTest ( $value )
    {
        if ( $value != "jelly" )
            return "Value must be jelly";
    }

    public function instanceCallbackTest ( $value )
    {
        if ( $value != "milk" )
            return "Value must be milk";
    }

    public function __invoke ( $value )
    {
        if ( $value != "sugar" )
            return "Value must be sugar";
    }

    public function testClosure ()
    {
        $valid = new ::cPHP::Validator::Callback(function ($value) {
            if ( $value != "tonic" )
                return "Value must be tonic";
        });

        $this->assertTrue( $valid->isValid("tonic") );

        $result = $valid->validate("gin");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Value must be tonic"),
                $result->getErrors()->get()
            );
    }

    public function testFunction ()
    {
        $valid = new ::cPHP::Validator::Callback("stub_validator_callback_func");

        $this->assertTrue( $valid->isValid("cheese") );

        $result = $valid->validate("Crackers");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Value must be cheese"),
                $result->getErrors()->get()
            );
    }

    public function testStaticMethod ()
    {
        $valid = new ::cPHP::Validator::Callback(array(__CLASS__, "staticCallbackTest"));

        $this->assertTrue( $valid->isValid("jelly") );

        $result = $valid->validate("peanut butter");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Value must be jelly"),
                $result->getErrors()->get()
            );
    }

    public function testInstanceMethod ()
    {
        $valid = new ::cPHP::Validator::Callback(array($this, "instanceCallbackTest"));

        $this->assertTrue( $valid->isValid("milk") );

        $result = $valid->validate("cookies");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Value must be milk"),
                $result->getErrors()->get()
            );
    }

    public function testInvokableObject ()
    {
        $valid = new ::cPHP::Validator::Callback($this);

        $this->assertTrue( $valid->isValid("sugar") );

        $result = $valid->validate("cream");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Value must be sugar"),
                $result->getErrors()->get()
            );
    }

    public function testArrayResult ()
    {
        $valid = new ::cPHP::Validator::Callback(function ($value) {
            if ( $value > 10 )
                return array("Must be <= 10", array("Greater than 10"));
            return array("", NULL);
        });

        $this->assertTrue( $valid->isValid(5) );

        $result = $valid->validate(20);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must be <= 10", "Greater than 10"),
                $result->getErrors()->get()
            );
    }

    public function testAryResult ()
    {
        $valid = new ::cPHP::Validator::Callback(function ($value) {
            if ( $value > 10 )
                return new ::cPHP::Ary(array("Must be <= 10", "Greater than 10"));
            return new ::cPHP::Ary;
        });

        $this->assertTrue( $valid->isValid(5) );

        $result = $valid->validate(20);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must be <= 10", "Greater than 10"),
                $result->getErrors()->get()
            );
    }

    public function testTraversableResult ()
    {
        $valid = new ::cPHP::Validator::Callback(function ($value) {
            if ( $value > 10 )
                return new ArrayIterator(array("Must be <= 10", "Greater than 10"));
            return new ArrayIterator;
        });

        $this->assertTrue( $valid->isValid(5) );

        $result = $valid->validate(20);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must be <= 10", "Greater than 10"),
                $result->getErrors()->get()
            );
    }

    public function testResultObjectResult ()
    {
        $valid = new ::cPHP::Validator::Callback(function ($value) {
            $result = new ::cPHP::Validator::Result($value);
            if ( $value > 10 )
                $result->addError("Error one")->addError("error two");
            return $result;
        });

        $this->assertTrue( $valid->isValid(5) );

        $result = $valid->validate(20);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Error one", "error two"),
                $result->getErrors()->get()
            );
    }

    public function testStringResult ()
    {
        $valid = new ::cPHP::Validator::Callback(function ($value) {
            if ( $value > 10 )
                return "Error";
            return "   ";
        });

        $this->assertTrue( $valid->isValid(5) );

        $result = $valid->validate(20);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Error"),
                $result->getErrors()->get()
            );
    }

    public function testFloatResult ()
    {
        $valid = new ::cPHP::Validator::Callback(function ($value) {
            if ( $value > 10 )
                return 1.505;
            return 0.0;
        });

        $this->assertTrue( $valid->isValid(5) );

        $result = $valid->validate(20);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("1.505"),
                $result->getErrors()->get()
            );
    }

    public function testIntegerResult ()
    {
        $valid = new ::cPHP::Validator::Callback(function ($value) {
            if ( $value > 10 )
                return 99;
            return 0;
        });

        $this->assertTrue( $valid->isValid(5) );

        $result = $valid->validate(20);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("99"),
                $result->getErrors()->get()
            );
    }

    public function testNullResult ()
    {
        $valid = new ::cPHP::Validator::Callback(function ($value) {
            return null;
        });

        $this->assertTrue( $valid->isValid(5) );
    }

    public function testBoolResult ()
    {
        $valid = new ::cPHP::Validator::Callback(function ($value) {
            if ( $value > 10 )
                return TRUE;
            return FALSE;
        });

        $this->assertTrue( $valid->isValid(5) );
        $this->assertTrue( $valid->isValid(20) );
    }

}

?>