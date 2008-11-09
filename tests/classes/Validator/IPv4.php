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
class classes_validator_ipv4 extends PHPUnit_Framework_TestCase
{

    public function testValid ()
    {
        $validator = new ::cPHP::Validator::IPv4;

        $this->assertTrue( $validator->isValid("192.168.0.1") );
        $this->assertTrue( $validator->isValid("255.255.255.0") );
        $this->assertTrue( $validator->isValid("209.85.171.99") );
        $this->assertTrue( $validator->isValid("0.0.0.0") );
        $this->assertTrue( $validator->isValid("172.16.0.0") );
        $this->assertTrue( $validator->isValid("169.254.0.0") );
    }

    public function testInvalid ()
    {
        $validator = new ::cPHP::Validator::IPv4;


        $result = $validator->validate('example');
        $this->assertFalse( $result->isValid() );
        $this->assertSame(
                array("IP address is not valid"),
                $result->getErrors()->get()
            );


        $result = $validator->validate('0.0.0');
        $this->assertFalse( $result->isValid() );
        $this->assertSame(
                array("IP address is not valid"),
                $result->getErrors()->get()
            );


        $result = $validator->validate('2001:0db8:85a3:0000:0000:8a2e:0370:7334');
        $this->assertFalse( $result->isValid() );
        $this->assertSame(
                array("IP address is not valid"),
                $result->getErrors()->get()
            );
    }

}

?>