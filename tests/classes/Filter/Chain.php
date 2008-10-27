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
class classes_filter_chain extends PHPUnit_Framework_TestCase
{
   
    public function testAdd ()
    {
        $mock = $this->getMock("cPHP::iface::Filter", array("filter"));
        $mock2 = $this->getMock("cPHP::iface::Filter", array("filter"));
        
        $filter = new cPHP::Filter::Chain;
        $this->assertSame( $filter, $filter->add( $mock ) );
        
        $list = $filter->get();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals( 1, count($list) );
        $this->assertSame( $mock, $list[0] );
        
        $this->assertSame( $filter, $filter->add( $mock2 ) );
        
        $list = $filter->get();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals( 2, count($list) );
        $this->assertSame( array($mock, $mock2), $list->get() );
    }
    
    public function testConstruct ()
    {
        $mock = $this->getMock("cPHP::iface::Filter", array("filter"));
        $mock2 = $this->getMock("cPHP::iface::Filter", array("filter"));
        
        $filter = new cPHP::Filter::Chain( $mock, $mock2 );
        
        $list = $filter->get();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals( 2, count($list) );
        $this->assertSame( array($mock, $mock2), $list->get() );
    }
    
    public function testClear ()
    {
        $mock = $this->getMock("cPHP::iface::Filter", array("filter"));
        $mock2 = $this->getMock("cPHP::iface::Filter", array("filter"));
        
        $filter = new cPHP::Filter::Chain( $mock, $mock2 );
        
        $list = $filter->get();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals( 2, count($list) );
        $this->assertSame( array($mock, $mock2), $list->get() );
        
        $this->assertSame( $filter, $filter->clear() );
        
        $list = $filter->get();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals( 0, count($list) );
    }
    
    public function testFilter ()
    {
        $mock = $this->getMock("cPHP::iface::Filter", array("filter"));
        
        $mock->expects($this->once())
            ->method('filter')
            ->with($this->equalTo('Input Value'))
            ->will($this->returnValue('Filtered Value'));
        
        $filter = new cPHP::Filter::Chain( $mock );
        
        $this->assertEquals( "Filtered Value", $filter->filter('Input Value') );
    }
    
    public function testChaining ()
    {
        $mock = $this->getMock("cPHP::iface::Filter", array("filter"));
        
        $mock->expects($this->once())
            ->method('filter')
            ->with($this->equalTo('Input Value'))
            ->will($this->returnValue('Result From One'));
            
            
        $mock2 = $this->getMock("cPHP::iface::Filter", array("filter"));
        
        $mock2->expects($this->once())
            ->method('filter')
            ->with($this->equalTo('Result From One'))
            ->will($this->returnValue('Result From Two'));
            
            
        $mock3 = $this->getMock("cPHP::iface::Filter", array("filter"));
        
        $mock3->expects($this->once())
            ->method('filter')
            ->with($this->equalTo('Result From Two'))
            ->will($this->returnValue('Result From Three'));
            
        
        $filter = new cPHP::Filter::Chain( $mock, $mock2, $mock3 );
        
        $this->assertEquals( 'Result From Three', $filter->filter('Input Value') );
    }
   
}

?>