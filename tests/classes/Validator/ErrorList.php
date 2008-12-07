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
class classes_validator_errorlist extends PHPUnit_Framework_TestCase
{

    public function testAddError ()
    {
        $result = new \cPHP\Validator\ErrorList;

        $this->assertSame( $result, $result->addError("This is an error message") );


        $errors = $result->getErrors();

        $this->assertThat( $errors, $this->isInstanceOf("cPHP\Ary") );

        $this->assertEquals(
                array("This is an error message"),
                $result->getErrors()->get()
            );

        $this->assertSame( $result, $result->addError("This is another error message") );

        $this->assertEquals(
                array("This is an error message", "This is another error message"),
                $result->getErrors()->get()
            );


        try {
            $result->addError("");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \cPHP\Exception\Argument $err ) {}
    }

    public function testAddErrors ()
    {
        $result = new \cPHP\Validator\ErrorList;

        $this->assertSame( $result, $result->addErrors("Error Message") );
        $this->assertEquals(
                array("Error Message"),
                $result->getErrors()->get()
            );

        $result->clearErrors();


        $this->assertSame(
                $result,
                $result->addErrors( array(("Error Message"), "more"), "Another", "", array("more", "then some") )
            );
        $this->assertEquals(
                array("Error Message", "more", "Another", "then some"),
                $result->getErrors()->get()
            );
    }

    public function testAddDuplicateError ()
    {
        $result = new \cPHP\Validator\ErrorList;

        $this->assertSame( $result, $result->addError("This is an error message") );
        $this->assertSame( $result, $result->addError("This is an error message") );

        $this->assertEquals(
                array("This is an error message"),
                $result->getErrors()->get()
            );
    }

    public function testClearErrors ()
    {
        $result = new \cPHP\Validator\ErrorList;

        $result->addError("This is an error message");

        $this->assertEquals(
                array("This is an error message"),
                $result->getErrors()->get()
            );

        $this->assertSame( $result, $result->clearErrors() );

        $this->assertEquals( array(), $result->getErrors()->get() );
    }

    public function testSetErrors ()
    {
        $result = new \cPHP\Validator\ErrorList;

        $result->addError("This is an error message");

        $this->assertEquals(
                array("This is an error message"),
                $result->getErrors()->get()
            );

        $this->assertSame( $result, $result->setError("This is a new error") );

        $this->assertEquals(
                array("This is a new error"),
                $result->getErrors()->get()
            );
    }

    public function testHasErrors ()
    {
        $result = new \cPHP\Validator\ErrorList;

        $this->assertFalse( $result->hasErrors() );

        $result->addError("Test Error");

        $this->assertTrue( $result->hasErrors() );

        $result->clearErrors();

        $this->assertFalse( $result->hasErrors() );
    }

    public function testGetFirstError ()
    {
        $result = new \cPHP\Validator\ErrorList;

        $this->assertNull( $result->getFirstError() );

        $result->addError("Test Error");

        $this->assertEquals("Test Error", $result->getFirstError());

        $result->addError("Another Error");

        $this->assertEquals("Test Error", $result->getFirstError());
    }

}

?>