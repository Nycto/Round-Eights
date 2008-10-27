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
class classes_validator_notempty extends PHPUnit_Framework_TestCase
{
    
    public function testInvalid_noFlags ()
    {
        
        $validator = new ::cPHP::Validator::NotEmpty;
        
        $result = $validator->validate("");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be empty"),
                $result->getErrors()->get()
            );
        
        $result = $validator->validate("    ");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be empty"),
                $result->getErrors()->get()
            );
        
        $result = $validator->validate(NULL);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be empty"),
                $result->getErrors()->get()
            );
        
        $result = $validator->validate(0);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be empty"),
                $result->getErrors()->get()
            );
        
        $result = $validator->validate(FALSE);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be empty"),
                $result->getErrors()->get()
            );
        
        $result = $validator->validate(array());
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be empty"),
                $result->getErrors()->get()
            );
        
    }
    
    public function testInvalid_flags ()
    {
        
        $validator = new ::cPHP::Validator::NotEmpty( ALLOW_BLANK );
        $this->assertTrue( $validator->isValid("") );
        
        $result = $validator->validate("    ");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be empty"),
                $result->getErrors()->get()
            );
        
        
        $validator = new ::cPHP::Validator::NotEmpty( ALLOW_NULL );
        $this->assertTrue( $validator->isValid(NULL) );
        
        $result = $validator->validate(0);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be empty"),
                $result->getErrors()->get()
            );
        
        
        $validator = new ::cPHP::Validator::NotEmpty( ALLOW_FALSE );
        $this->assertTrue( $validator->isValid(FALSE) );
        
        $result = $validator->validate(array());
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be empty"),
                $result->getErrors()->get()
            );
        
    }
    
    public function testValid ()
    {
        $validator = new ::cPHP::Validator::NotEmpty;
        
        $this->assertTrue( $validator->isValid("0") );
        $this->assertTrue( $validator->isValid("this is not empty") );
        $this->assertTrue( $validator->isValid( $this->getMock("NotEmpty") ) );
        $this->assertTrue( $validator->isValid( TRUE ) );
        $this->assertTrue( $validator->isValid( 20 ) );
    }
    
}

?>