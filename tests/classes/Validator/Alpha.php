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
class classes_validator_alpha extends PHPUnit_Framework_TestCase
{

    public function testNonStrings ()
    {
        $validator = new \cPHP\Validator\Alpha;

        $result = $validator->validate($this->getMock("stubObject"));
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must be a string"),
                $result->getErrors()->get()
            );

        $result = $validator->validate(10);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must be a string"),
                $result->getErrors()->get()
            );

        $result = $validator->validate(1.5);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must be a string"),
                $result->getErrors()->get()
            );

        $result = $validator->validate(NULL);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must be a string"),
                $result->getErrors()->get()
            );

        $result = $validator->validate(TRUE);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must be a string"),
                $result->getErrors()->get()
            );

        $result = $validator->validate(FALSE);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must be a string"),
                $result->getErrors()->get()
            );
    }

    public function testValid ()
    {
        $validator = new \cPHP\Validator\Alpha;

        $this->assertTrue( $validator->validate("string")->isValid() );
        $this->assertTrue( $validator->validate("abcXYZ")->isValid() );

        $valid = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
            .'abcdefghijklmnopqrstuvwxyz';

        $this->assertTrue( $validator->validate($valid)->isValid() );
    }

    public function testInvalid ()
    {
        $validator = new \cPHP\Validator\Alpha;

        $result = $validator->validate('1234567890');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must only contain letters"),
                $result->getErrors()->get()
            );

        // Get a list of extended range ascii characters
        $string = implode( "", array_map("chr", range(127, 255) ) );
        $result = $validator->validate($string);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must only contain letters"),
                $result->getErrors()->get()
            );

        $result = $validator->validate('!"#$%&\'()*+,-/:;<=>?@[\]^`{|}~');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must only contain letters"),
                $result->getErrors()->get()
            );
    }

}

?>