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
class classes_validator_regex extends PHPUnit_Framework_TestCase
{
    
    public function testConstruct ()
    {
        try {
            new ::cPHP::Validator::RegEx("");
            $this->fail("An expected exception was not thrown");
        }
        catch ( cPHP::Exception::Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }
        
        try {
            new ::cPHP::Validator::RegEx("   ");
            $this->fail("An expected exception was not thrown");
        }
        catch ( cPHP::Exception::Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }
    }
    
    public function testInvalidRegex ()
    {
        $regex = new ::cPHP::Validator::RegEx("1234");
        
        try {
            $regex->validate( "test" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( PHPUnit_Framework_Error $err ) {
            $this->assertSame(
                    "preg_match(): Delimiter must not be alphanumeric or backslash",
                    $err->getMessage()
                );
        }
        
    }
    
    public function testInvalidNonStrings()
    {
        $validator = new ::cPHP::Validator::RegEx("/[a-z]/");
        
        $result = $validator->validate($this->getMock("NoSpaces"));
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must be a string"),
                $result->getErrors()->get()
            );
    }
    
    public function testTrue()
    {
        $validator = new ::cPHP::Validator::RegEx('/^1$/');
        $this->assertTrue( $validator->isValid(TRUE) );
        
        $validator = new ::cPHP::Validator::RegEx('/[a-z]/');
        $result = $validator->validate(TRUE);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must match the following regular expression: /[a-z]/"),
                $result->getErrors()->get()
            );
    }
    
    public function testFalse()
    {
        $validator = new ::cPHP::Validator::RegEx('/^$/');
        $this->assertTrue( $validator->isValid(FALSE) );
        
        $validator = new ::cPHP::Validator::RegEx('/[a-z]/');
        $result = $validator->validate(FALSE);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must match the following regular expression: /[a-z]/"),
                $result->getErrors()->get()
            );
    }
    
    public function testInteger()
    {
        $validator = new ::cPHP::Validator::RegEx('/^50$/');
        $this->assertTrue( $validator->isValid(50) );
        
        $validator = new ::cPHP::Validator::RegEx('/[a-z]/');
        $result = $validator->validate(50);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must match the following regular expression: /[a-z]/"),
                $result->getErrors()->get()
            );
    }
    
    public function testZero()
    {
        $validator = new ::cPHP::Validator::RegEx('/^0$/');
        $this->assertTrue( $validator->isValid(0) );
        
        $validator = new ::cPHP::Validator::RegEx('/[a-z]/');
        $result = $validator->validate(0);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must match the following regular expression: /[a-z]/"),
                $result->getErrors()->get()
            );
    }
    
    public function testNull()
    {
        $validator = new ::cPHP::Validator::RegEx('/^$/');
        $this->assertTrue( $validator->isValid(NULL) );
        
        $validator = new ::cPHP::Validator::RegEx('/[a-z]/');
        $result = $validator->validate(NULL);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must match the following regular expression: /[a-z]/"),
                $result->getErrors()->get()
            );
    }
    
    public function testFloat()
    {
        $validator = new ::cPHP::Validator::RegEx('/^1\.1$/');
        $this->assertTrue( $validator->isValid(1.1) );
        
        $validator = new ::cPHP::Validator::RegEx('/[a-z]/');
        $result = $validator->validate(1.1);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must match the following regular expression: /[a-z]/"),
                $result->getErrors()->get()
            );
    }
    
    public function _testString()
    {
        $validator = new ::cPHP::Validator::RegEx('/\.php$/');
        $this->assertTrue( $validator->isValid("file.php") );
        
        $validator = new ::cPHP::Validator::RegEx('/[0-9]/');
        $result = $validator->validate("This is a string");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must match the following regular expression: /[0-9]/"),
                $result->getErrors()->get()
            );
    }
    
}

?>