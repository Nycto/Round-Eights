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
class classes_validator_url extends PHPUnit_Framework_TestCase
{
    
    public function testNonStrings ()
    {
        $validator = new ::cPHP::Validator::URL;
        
        $result = $validator->validate(5);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("URL must be a string"),
                $result->getErrors()->get()
            );
        
        $result = $validator->validate(5.5);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("URL must be a string"),
                $result->getErrors()->get()
            );
        
        $result = $validator->validate(null);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("URL must be a string"),
                $result->getErrors()->get()
            );
        
        $result = $validator->validate(TRUE);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("URL must be a string"),
                $result->getErrors()->get()
            );
    }
    
    public function testInvalid ()
    {
        $validator = new ::cPHP::Validator::URL;
        
        // Spaces
        $result = $validator->validate("http:// www.example.com");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("URL must not contain spaces"),
                $result->getErrors()->get()
            );
        
        // Tab
        $result = $validator->validate("http://\twww.example.com");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("URL must not contain tabs"),
                $result->getErrors()->get()
            );
        
        // Line break... \n
        $result = $validator->validate("http://\nwww.example.com");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("URL must not contain line breaks"),
                $result->getErrors()->get()
            );
        
        // Line break... \r
        $result = $validator->validate("http://\rwww.example.com");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("URL must not contain line breaks"),
                $result->getErrors()->get()
            );
        
        // invalid characters
        $result = $validator->validate('http://'. chr(15) .'example.com');
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("URL contains invalid characters"),
                $result->getErrors()->get()
            );
        
        
        $result = $validator->validate('example');
        $this->assertFalse( $result->isValid() );
        $this->assertSame(
                array("URL is not valid"),
                $result->getErrors()->get()
            );
        
        $result = $validator->validate('example.com');
        $this->assertFalse( $result->isValid() );
        $this->assertSame(
                array("URL is not valid"),
                $result->getErrors()->get()
            );
        
        $result = $validator->validate('www.example.com/test.php');
        $this->assertFalse( $result->isValid() );
        $this->assertSame(
                array("URL is not valid"),
                $result->getErrors()->get()
            );
        
    }
    
    public function testValid ()
    {
        $validator = new ::cPHP::Validator::URL;
        
        $this->assertTrue(
                $validator->validate('http://example.com')->isValid()
            );
        
        $this->assertTrue(
                $validator->validate('https://www.example.com')->isValid()
            );
        
        $this->assertTrue(
                $validator->validate('ftp://www.example.com/')->isValid()
            );
        
        $this->assertTrue(
                $validator->validate('ftp://www.example.com/index.php')->isValid()
            );
        
        $this->assertTrue(
                $validator->validate('ftp://www.example.com/dir')->isValid()
            );
        
        $this->assertTrue(
                $validator->validate('ftp://www.example.com/dir/index.php')->isValid()
            );
        
        $this->assertTrue(
                $validator->validate('ftp://www.example.com?test=1')->isValid()
            );
        
        $this->assertTrue(
                $validator->validate('ftp://www.example.com?test=1#fragment')->isValid()
            );
        
        $this->assertTrue(
                $validator->validate('foo://example.com:8042/over/there?name=ferret#nose')->isValid()
            );
        
    }
    
}

?>