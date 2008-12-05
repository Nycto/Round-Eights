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
class classes_validator_nospaces extends PHPUnit_Framework_TestCase
{

    public function testValidNonStrings()
    {
        $validator = new \cPHP\Validator\NoSpaces;

        $this->assertTrue( $validator->isValid(TRUE) );
        $this->assertTrue( $validator->isValid(FALSE) );
        $this->assertTrue( $validator->isValid(50) );
        $this->assertTrue( $validator->isValid(0) );
        $this->assertTrue( $validator->isValid(1.5) );
        $this->assertTrue( $validator->isValid(NULL) );

    }

    public function testValidStrings()
    {
        $validator = new \cPHP\Validator\NoSpaces;

        $this->assertTrue( $validator->isValid("NoSpaces") );
        $this->assertTrue( $validator->isValid("!@$^$@$#{}:<>?") );
        $this->assertTrue( $validator->isValid("") );
    }

    public function testInvalidNonStrings()
    {
        $validator = new \cPHP\Validator\NoSpaces;

        $result = $validator->validate($this->getMock("NoSpaces"));
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must be a string"),
                $result->getErrors()->get()
            );
    }

    public function testInvalidStrings()
    {
        $validator = new \cPHP\Validator\NoSpaces;

        $result = $validator->validate("   ");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not contain any spaces"),
                $result->getErrors()->get()
            );

        $result = $validator->validate("String With Spaces");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not contain any spaces"),
                $result->getErrors()->get()
            );

        $result = $validator->validate("\tTabbed");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not contain any tabs"),
                $result->getErrors()->get()
            );

        $result = $validator->validate("lineBreak\n");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not contain any new lines"),
                $result->getErrors()->get()
            );

        $result = $validator->validate("return\r");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not contain any new lines"),
                $result->getErrors()->get()
            );

    }

}

?>