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
class classes_validator_email extends PHPUnit_Framework_TestCase
{

    public function testValidAddresses ()
    {
        $validator = new cPHP::Validator::Email;

        $this->assertTrue( $validator->isValid('abc@example.com') );
        $this->assertTrue( $validator->isValid('Abc@example.com') );
        $this->assertTrue( $validator->isValid('aBC@example.com') );
        $this->assertTrue( $validator->isValid('abc.123@example.com') );
        $this->assertTrue( $validator->isValid('abc.123@sub.example.com') );
        $this->assertTrue( $validator->isValid('abc.123@sub.sub.example.com') );
        $this->assertTrue( $validator->isValid('abc+123@example.com') );
        $this->assertTrue( $validator->isValid('1234567890@example.com') );
        $this->assertTrue( $validator->isValid('_______@example.com') );
        $this->assertTrue( $validator->isValid('abc+mailbox/department=shipping@example.com') );
        $this->assertTrue( $validator->isValid('!#$%&\'*+-/=?^_`.{|}~@example.com') );

        // Just under the length caps
        $this->assertTrue( $validator->isValid( str_repeat('a', 64) .'@example.com') );
        $this->assertTrue( $validator->isValid( "abc@". str_repeat('a', 251) .'.com') );
    }

    public function testInvalidAddresses ()
    {
        $validator = new cPHP::Validator::Email;

        // Missing an @ symbol
        $result = $validator->validate('Abc.example.com');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address must contain an 'at' (@) symbol"),
                $result->getErrors()->get()
            );

        // Multiple @ symbols
        $result = $validator->validate('A@b@c@example.com');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address must only contain one 'at' (@) symbol"),
                $result->getErrors()->get()
            );

        // Repated periods
        $result = $validator->validate('Abc..123@example.com');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address must not contain repeated periods"),
                $result->getErrors()->get()
            );

        // Spaces
        $result = $validator->validate('Abc. 123@ example.com');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address must not contain spaces"),
                $result->getErrors()->get()
            );

        // Line Breaks... \n
        $result = $validator->validate("Abc.\n123@example.com");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address must not contain line breaks"),
                $result->getErrors()->get()
            );

        // Line Breaks... \r
        $result = $validator->validate("Abc.\r123@example.com");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address must not contain line breaks"),
                $result->getErrors()->get()
            );

        // Tabs
        $result = $validator->validate("Abc123@\texample.com");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address must not contain tabs"),
                $result->getErrors()->get()
            );

        // invalid characters
        $result = $validator->validate('()[]\;:,<>@example.com');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address contains invalid characters"),
                $result->getErrors()->get()
            );

        // Period as the last character
        $result = $validator->validate('Abc@example.com.');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address must not end with a period"),
                $result->getErrors()->get()
            );

        // Period as the first character
        $result = $validator->validate('.Abc@example.com');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address must not start with a period"),
                $result->getErrors()->get()
            );

        // Period as the last character in the local part
        $result = $validator->validate('Abc.@example.com');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address is not valid"),
                $result->getErrors()->get()
            );

        // Period as the first character of the domain
        $result = $validator->validate('Abc@.example.com');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address is not valid"),
                $result->getErrors()->get()
            );

        // The local part is too long
        $result = $validator->validate( str_repeat('a', 65) .'@example.com');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address is too long"),
                $result->getErrors()->get()
            );

        // The domain is too long
        $result = $validator->validate( "abc@". str_repeat('a', 252) .'.com');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address is too long"),
                $result->getErrors()->get()
            );

        // Nothing before the @
        $result = $validator->validate('@example.com');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address is not valid"),
                $result->getErrors()->get()
            );

        // Nothing after the @
        $result = $validator->validate('abc@');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address is not valid"),
                $result->getErrors()->get()
            );

        // No top level domain
        $result = $validator->validate('abc@example');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Email Address is not valid"),
                $result->getErrors()->get()
            );

    }

}

?>