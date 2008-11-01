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
class classes_filter_standardempty extends PHPUnit_Framework_TestCase
{
    
    public function testConstruct ()
    {
        $filter = new cPHP::Filter::StandardEmpty();
        $this->assertEquals( 0, $filter->getFlags() );
        $this->assertEquals( NULL, $filter->getValue() );
        
        $filter = new cPHP::Filter::StandardEmpty( "Empty Value", 5 );
        $this->assertEquals( 5, $filter->getFlags() );
        $this->assertEquals( "Empty Value", $filter->getValue() );
    }
    
    public function testSetValue ()
    {
        $filter = new cPHP::Filter::StandardEmpty();
        
        $this->assertEquals( NULL, $filter->getValue() );
        
        $this->assertSame( $filter, $filter->setValue("New Empty") );
        
        $this->assertEquals( "New Empty", $filter->getValue() );
    }
    
    public function testSetFlags ()
    {
        $filter = new cPHP::Filter::StandardEmpty();
        
        $this->assertEquals( 0, $filter->getFlags() );
        
        $this->assertSame( $filter, $filter->setFlags(5) );
        
        $this->assertEquals( 5, $filter->getFlags() );
    }
    
    public function testAddFlags ()
    {
        $filter = new cPHP::Filter::StandardEmpty();
        
        $this->assertEquals( 0, $filter->getFlags() );
        
        $this->assertSame( $filter, $filter->addFlags(1) );
        
        $this->assertEquals( 1, $filter->getFlags() );
        
        $this->assertSame( $filter, $filter->addFlags(2) );
        
        $this->assertEquals( 3, $filter->getFlags() );
        
        $this->assertSame( $filter, $filter->addFlags(8) );
        
        $this->assertEquals( 11, $filter->getFlags() );
    }
    
    public function testInteger ()
    {
        $filter = new cPHP::Filter::StandardEmpty;
        $this->assertSame( NULL, $filter->filter(0) );
        $this->assertSame( 1, $filter->filter(1) );
        $this->assertSame( 20, $filter->filter(20) );
        $this->assertSame( -10, $filter->filter(-10) );
    }
    
    public function testBoolean ()
    {
        $filter = new cPHP::Filter::StandardEmpty;
        $this->assertSame( NULL, $filter->filter(FALSE) );
        $this->assertSame( TRUE, $filter->filter(TRUE) );
    }
    
    public function testFloat ()
    {
        $filter = new cPHP::Filter::StandardEmpty;
        
        $this->assertSame( NULL, $filter->filter(0.0) );
        $this->assertSame( 20.25, $filter->filter(20.25) );
    }
    
    public function testNull ()
    {
        $filter = new cPHP::Filter::StandardEmpty;
        
        $this->assertSame( NULL, $filter->filter(NULL) );
    }
    
    public function testString ()
    {
        $filter = new cPHP::Filter::StandardEmpty;
        
        $this->assertSame( NULL, $filter->filter("") );
        $this->assertSame( NULL, $filter->filter("    ") );
        $this->assertSame( "Some String", $filter->filter("Some String") );
    }
    
    public function testArray ()
    {
        $filter = new cPHP::Filter::StandardEmpty;
        
        $this->assertSame( array(50), $filter->filter( array(50) ) );
        $this->assertSame( NULL, $filter->filter( array() ) );
    }
    
    public function testObject ()
    {
        $filter = new cPHP::Filter::StandardEmpty;
        
        $mock = $this->getMock("stub_random_obj");
        
        $this->assertSame( $mock, $filter->filter( $mock ) );
        
    }
    
}

?>