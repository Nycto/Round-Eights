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

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * general function unit tests
 */
class functions_general extends PHPUnit_Framework_TestCase
{
    
    public function testSwap ()
    {
        
        $var1 = "test";
        $var2 = "other";
        cPHP::swap($var1, $var2);

        $this->assertEquals("test", $var2);
        $this->assertEquals("other", $var1);
    }
    
    public function testReduce ()
    {
        $this->assertFalse( cPHP::reduce( FALSE ) );
        $this->assertTrue( cPHP::reduce( TRUE ) );
        $this->assertNull( cPHP::reduce( NULL ) );
        $this->assertEquals( 270, cPHP::reduce( 270 ) );
        $this->assertEquals( 151.12, cPHP::reduce( 151.12 ) );
        $this->assertEquals( 151.12, cPHP::reduce( array(151.12, 150) ) );
        $this->assertEquals( 151.12, cPHP::reduce( array( array(151.12, 150) ) ) );
    }
    
    public function testDefineIf ()
    {
        $this->assertFalse( defined("testDefineIf_example") );
        
        $this->assertTrue( cPHP::defineIf("testDefineIf_example", "value") );
        
        $this->assertTrue( defined("testDefineIf_example") );
        $this->assertEquals( "value", testDefineIf_example );
        
        $this->assertTrue( cPHP::defineIf("testDefineIf_example", "new value") );
        
        $this->assertEquals( "value", testDefineIf_example );
        
    }

    public function testIsEmpty ()
    {
        $this->assertTrue( cPHP::is_empty("") );
        $this->assertTrue( cPHP::is_empty(0) );
        $this->assertTrue( cPHP::is_empty(NULL) );
        $this->assertTrue( cPHP::is_empty(FALSE) );
        $this->assertTrue( cPHP::is_empty( array() ) );
        $this->assertTrue( cPHP::is_empty( "  " ) );

        $this->assertFalse( cPHP::is_empty("string") );
        $this->assertFalse( cPHP::is_empty(1) );
        $this->assertFalse( cPHP::is_empty("0") );
        $this->assertFalse( cPHP::is_empty("1") );
        $this->assertFalse( cPHP::is_empty(TRUE) );
        $this->assertFalse( cPHP::is_empty( array(1) ) );

        $this->assertFalse( cPHP::is_empty("", cPHP::ALLOW_BLANK) );
        $this->assertFalse( cPHP::is_empty(0, cPHP::ALLOW_ZERO) );
        $this->assertFalse( cPHP::is_empty(NULL, cPHP::ALLOW_NULL) );
        $this->assertFalse( cPHP::is_empty(FALSE, cPHP::ALLOW_FALSE) );
        $this->assertFalse( cPHP::is_empty( array(), cPHP::ALLOW_EMPTY_ARRAYS ) );
        $this->assertFalse( cPHP::is_empty( "  ", cPHP::ALLOW_SPACES ) );
    }

    public function testIsVague ()
    {
        $this->assertTrue( cPHP::is_vague(FALSE) );
        $this->assertTrue( cPHP::is_vague(TRUE) );
        $this->assertTrue( cPHP::is_vague("") );
        $this->assertTrue( cPHP::is_vague(0) );
        $this->assertTrue( cPHP::is_vague(NULL) );
        $this->assertTrue( cPHP::is_vague( array() ) );
        $this->assertTrue( cPHP::is_vague( "  " ) );

        $this->assertFalse( cPHP::is_vague("string") );
        $this->assertFalse( cPHP::is_vague(1) );
        $this->assertFalse( cPHP::is_vague("0") );
        $this->assertFalse( cPHP::is_vague("1") );
        $this->assertFalse( cPHP::is_vague( array(1) ) );
    }
    
    public function testArrayVal ()
    {
        $this->assertEquals( array(1, 2, 3), cPHP::arrayVal(array(1, 2, 3)) );
        $this->assertEquals( array(1), cPHP::arrayVal(1) );
    }
    
    public function testNumVal ()
    {
        $this->assertEquals( 1, cPHP::numVal(1) );
        $this->assertEquals( 1.5, cPHP::numVal(1.5) );
        $this->assertEquals( 1, cPHP::numVal("1") );
        $this->assertEquals( 1.5, cPHP::numVal("1.5") );
    }
    
    public function testBoolVal ()
    {
        $this->assertEquals( TRUE, cPHP::boolVal(TRUE) );
        $this->assertEquals( FALSE, cPHP::boolVal(FALSE) );
        $this->assertEquals( TRUE, cPHP::boolVal(1) );
        $this->assertEquals( FALSE, cPHP::boolVal(0) );
    }
    
    public function testStrVal ()
    {
        $this->assertEquals( "string", ::cPHP::strVal("string") );
        $this->assertEquals( "5", cPHP::strVal(5) );
        
        $toString = $this->getMock("stub_strval", array("__toString"));
        $toString->expects( $this->once() )
            ->method("__toString")
            ->will( $this->returnValue("String Version") );
        
        $this->assertEquals( "String Version", ::cPHP::strVal( $toString ) );
    }
    
    public function testKindOf ()
    {
        $filter = new cPHP::Filter::Chain;
        
        $this->assertTrue( ::cPHP::kindOf( $filter, "cPHP::Filter::Chain" ) );
        $this->assertTrue( ::cPHP::kindOf( $filter, "cPHP::Filter" ) );
        $this->assertTrue( ::cPHP::kindOf( $filter, "cPHP::iface::Filter" ) );
        
        $this->assertTrue( ::cPHP::kindOf( $filter, "cphp::filter::chain" ) );
        $this->assertTrue( ::cPHP::kindOf( $filter, "cphp::filter" ) );
        $this->assertTrue( ::cPHP::kindOf( $filter, "cphp::iface::filter" ) );
        
        $this->assertTrue( ::cPHP::kindOf( $filter, "::cPHP::Filter::Chain" ) );
        $this->assertTrue( ::cPHP::kindOf( $filter, "::cPHP::Filter" ) );
        $this->assertTrue( ::cPHP::kindOf( $filter, "::cPHP::iface::Filter" ) );
        
        $this->assertFalse( ::cPHP::kindOf( $filter, "cPHP::Validator" ) );
        $this->assertFalse( ::cPHP::kindOf( $filter, "cPHP::iface::Validator" ) );
        
        $this->assertFalse( ::cPHP::kindOf( $filter, "::cPHP::Validator" ) );
        $this->assertFalse( ::cPHP::kindOf( $filter, "::cPHP::iface::Validator" ) );
        
        
        $this->assertTrue( ::cPHP::kindOf( "cPHP::Filter::Chain", "cPHP::Filter::Chain" ) );
        $this->assertTrue( ::cPHP::kindOf( "cPHP::Filter::Chain", "cPHP::Filter" ) );
        $this->assertTrue( ::cPHP::kindOf( "cPHP::Filter::Chain", "cPHP::iface::Filter" ) );
        
        $this->assertTrue( ::cPHP::kindOf( "cPHP::Filter::Chain", "cphp::filter::chain" ) );
        $this->assertTrue( ::cPHP::kindOf( "cPHP::Filter::Chain", "cphp::filter" ) );
        $this->assertTrue( ::cPHP::kindOf( "cPHP::Filter::Chain", "cphp::iface::filter" ) );
        
        $this->assertTrue( ::cPHP::kindOf( "::cPHP::Filter::Chain", "cPHP::Filter::Chain" ) );
        $this->assertTrue( ::cPHP::kindOf( "::cPHP::Filter::Chain", "cPHP::Filter" ) );
        $this->assertTrue( ::cPHP::kindOf( "::cPHP::Filter::Chain", "cPHP::iface::Filter" ) );
        
        $this->assertTrue( ::cPHP::kindOf( "cPHP::Filter::Chain", "::cPHP::Filter::Chain" ) );
        $this->assertTrue( ::cPHP::kindOf( "cPHP::Filter::Chain", "::cPHP::Filter" ) );
        $this->assertTrue( ::cPHP::kindOf( "cPHP::Filter::Chain", "::cPHP::iface::Filter" ) );
        
        $this->assertTrue( ::cPHP::kindOf( "::cPHP::Filter::Chain", "::cPHP::Filter::Chain" ) );
        $this->assertTrue( ::cPHP::kindOf( "::cPHP::Filter::Chain", "::cPHP::Filter" ) );
        $this->assertTrue( ::cPHP::kindOf( "::cPHP::Filter::Chain", "::cPHP::iface::Filter" ) );
        
        
        $this->assertFalse( ::cPHP::kindOf( "cPHP::Filter::Chain", "cPHP::Validator" ) );
        $this->assertFalse( ::cPHP::kindOf( "cPHP::Filter::Chain", "cPHP::iface::Validator" ) );
        
        $this->assertFalse( ::cPHP::kindOf( "::cPHP::Filter::Chain", "cPHP::Validator" ) );
        $this->assertFalse( ::cPHP::kindOf( "::cPHP::Filter::Chain", "cPHP::iface::Validator" ) );
        
        $this->assertFalse( ::cPHP::kindOf( "cPHP::Filter::Chain", "::cPHP::Validator" ) );
        $this->assertFalse( ::cPHP::kindOf( "cPHP::Filter::Chain", "::cPHP::iface::Validator" ) );
        
        $this->assertFalse( ::cPHP::kindOf( "::cPHP::Filter::Chain", "::cPHP::Validator" ) );
        $this->assertFalse( ::cPHP::kindOf( "::cPHP::Filter::Chain", "::cPHP::iface::Validator" ) );
    }

}

?>