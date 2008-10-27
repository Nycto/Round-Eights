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

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_validator_collection_none extends PHPUnit_Framework_TestCase
{
    
    public function testNoValidators ()
    {
        $all = new ::cPHP::Validator::Collection::None;
        
        $result = $all->validate("example value");
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertTrue( $result->isValid() );
        
    }
    
    public function testInvalidResult ()
    {
        $valid = $this->getMock("cPHP::iface::Validator", array("validate", "isValid"));
        $valid->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue("This is an invalid result") );
        
        $none = new ::cPHP::Validator::Collection::None( $valid );
        $this->assertEquals( array($valid), $none->getValidators()->get() );
        
        try {
            $none->validate("example value");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( ::cPHP::Exception::Data $err ) {}
    }
    
    public function testValid ()
    {

        $valid1 = $this->getMock("cPHP::iface::Validator", array("validate", "isValid"));
        $result1 = new ::cPHP::Validator::Result("example value");
        $result1->addError("Spoof Error");
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result1 ) );
        
        $valid2 = $this->getMock("cPHP::iface::Validator", array("validate", "isValid"));
        $result2 = new ::cPHP::Validator::Result("example value");
        $result2->addError("Spoof Error");
        $valid2->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result2 ) );
        
        
        $none = new ::cPHP::Validator::Collection::None( $valid1, $valid2 );
        
        $result = $none->validate("example value");
        
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertTrue( $result->isValid() );
        
    }
    
    public function testOneInvalid ()
    {
        
        $result1 = new ::cPHP::Validator::Result("example value");
        $valid1 = $this->getMock("cPHP::iface::Validator", array("validate", "isValid"));
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result1 ) );
        
        
        $none = new ::cPHP::Validator::Collection::None( $valid1 );
        
        $result = $none->validate("example value");
        
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Value is not valid"),
                $result->getErrors()->get()
            );

    }
    
    public function testFirstInvalid ()
    {
        
        $result1 = new ::cPHP::Validator::Result("example value");
        $valid1 = $this->getMock("cPHP::iface::Validator", array("validate", "isValid"));
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result1 ) );
        
        $valid2 = $this->getMock("cPHP::iface::Validator", array("validate", "isValid"));
        $valid2->expects( $this->never() )
            ->method( "validate" );
            
        
        $none = new ::cPHP::Validator::Collection::None( $valid1, $valid2 );
        
        $result = $none->validate("example value");
        
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Value is not valid"),
                $result->getErrors()->get()
            );

    }
    
    public function testSecondInvalid ()
    {
        
        $result1 = new ::cPHP::Validator::Result("example value");
        $result1->addError("This is an error");
        $valid1 = $this->getMock("cPHP::iface::Validator", array("validate", "isValid"));
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result1 ) );
        
        $result2 = new ::cPHP::Validator::Result("example value");
        $valid2 = $this->getMock("cPHP::iface::Validator", array("validate", "isValid"));
        $valid2->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result2 ) );
            
        
        $none = new ::cPHP::Validator::Collection::None( $valid1, $valid2 );
        
        $result = $none->validate("example value");
        
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Value is not valid"),
                $result->getErrors()->get()
            );

    }
    
}

?>