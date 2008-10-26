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
 * unit tests
 */
class classes_curry extends PHPUnit_Framework_TestCase
{
    
    
    public function testCreate ()
    {
        
        $this->assertThat(
                cPHP::Curry::Call::Create("trim"),
                $this->isInstanceOf("cPHP::Curry::Call")
            );
        
        $instance = cPHP::Curry::Call::Create("trim", "/");
        
        $this->assertThat(
                $instance,
                $this->isInstanceOf("cPHP::Curry::Call")
            );
        
        $this->assertEquals( array("/"), $instance->getRight() );
        
    }
    
    public function testSet ()
    {
        $curry = $this->getMock("cPHP::Curry", array("exec"));
        
        $this->assertEquals( array(), $curry->getLeft() );
        $this->assertEquals( array(), $curry->getRight() );
        
        $this->assertSame(
                $curry,
                $curry->setRight("wakka", "peanut")
            );
        
        $this->assertEquals( array(), $curry->getLeft() );
        $this->assertEquals( array("wakka", "peanut"), $curry->getRight() );
        
        $this->assertSame(
                $curry,
                $curry->setLeft("bean", "orange")
            );
        
        $this->assertEquals( array("bean", "orange"), $curry->getLeft() );
        $this->assertEquals( array("wakka", "peanut"), $curry->getRight() );
    }
    
    public function testSetByArray ()
    {
        $curry = $this->getMock("cPHP::Curry", array("exec"));
        
        $this->assertEquals( array(), $curry->getLeft() );
        $this->assertEquals( array(), $curry->getRight() );
        
        $this->assertSame(
                $curry,
                $curry->setRightByArray( array("wakka", "peanut") )
            );
        
        $this->assertEquals( array(), $curry->getLeft() );
        $this->assertEquals( array("wakka", "peanut"), $curry->getRight() );
        
        
        $this->assertSame(
                $curry,
                $curry->setLeftByArray( array("bean", "orange") )
            );
        
        $this->assertEquals( array("bean", "orange"), $curry->getLeft() );
        $this->assertEquals( array("wakka", "peanut"), $curry->getRight() );
    }
    
    public function testClearLeftRight ()
    {
        $curry = $this->getMock("cPHP::Curry", array("exec"));
        
        $curry->setRight("wakka", "peanut");
        $curry->setLeft("bean", "orange");
        
        $this->assertEquals( array("bean", "orange"), $curry->getLeft() );
        $this->assertEquals( array("wakka", "peanut"), $curry->getRight() );
        
        $this->assertSame(
                $curry,
                $curry->clearLeft()
            );
        
        $this->assertEquals( array(), $curry->getLeft() );
        $this->assertEquals( array("wakka", "peanut"), $curry->getRight() );
        
        $this->assertSame(
                $curry,
                $curry->clearRight()
            );
        
        $this->assertEquals( array(), $curry->getLeft() );
        $this->assertEquals( array(), $curry->getRight() );   
    }
    
    public function testClearArgs ()
    {
        $curry = $this->getMock("cPHP::Curry", array("exec"));
        
        $curry->setRight("wakka", "peanut");
        $curry->setLeft("bean", "orange");
        
        $this->assertEquals( array("bean", "orange"), $curry->getLeft() );
        $this->assertEquals( array("wakka", "peanut"), $curry->getRight() );
        
        $this->assertSame(
                $curry,
                $curry->clearArgs()
            );
        
        $this->assertEquals( array(), $curry->getLeft() );
        $this->assertEquals( array(), $curry->getRight() );  
    }
    
    public function testOffset ()
    {
        
        $curry = $this->getMock("cPHP::Curry", array("exec"));
        
        $this->assertEquals( 0, $curry->getOffset() );
        
        $this->assertSame( $curry, $curry->setOffset( 1 ) );
        
        $this->assertEquals( 1, $curry->getOffset() );
        
        $this->assertSame( $curry, $curry->clearOffset() );
        
        $this->assertEquals( 0, $curry->getOffset() );
        
        $this->assertSame( $curry, $curry->setOffset( 5 ) );
        
        $this->assertEquals( 5, $curry->getOffset() );
    }
    
    public function testLimit ()
    {
        $curry = $this->getMock("cPHP::Curry", array("exec"));
        
        $this->assertFalse( $curry->issetLimit() );
        $this->assertFalse( $curry->getLimit() );
        
        $this->assertSame( $curry, $curry->setLimit( 2 ) );
        
        $this->assertTrue( $curry->issetLimit() );
        $this->assertEquals( 2, $curry->getLimit() );
        
        $this->assertSame( $curry, $curry->clearLimit() );
        
        $this->assertFalse( $curry->issetLimit() );
        $this->assertFalse( $curry->getLimit() );
        
        $this->assertSame( $curry, $curry->setLimit( 5 ) );
        
        $this->assertTrue( $curry->issetLimit() );
        $this->assertEquals( 5, $curry->getLimit() );
    }
    
    public function testClearSlicing ()
    {
        
        $curry = $this->getMock("cPHP::Curry", array("exec"));
        
        $curry->setLimit( 1 );
        $curry->setOffset( 1 );
        
        $this->assertSame( $curry, $curry->clearSlicing() );
        
        $this->assertEquals( 0, $curry->getOffset() );
        $this->assertFalse( $curry->issetLimit() );
    }
    
    public function testClear ()
    {
        
        $curry = $this->getMock("cPHP::Curry", array("exec"));
        
        $curry->setRight("wakka", "peanut");
        $curry->setLeft("bean", "orange");
        $curry->setLimit( 1 );
        $curry->setOffset( 1 );
        
        
        $this->assertSame( $curry, $curry->clear() );
        
        $this->assertEquals( array(), $curry->getLeft() );
        $this->assertEquals( array(), $curry->getRight() );  
        $this->assertEquals( 0, $curry->getOffset() );
        $this->assertFalse( $curry->issetLimit() );
        
    }
    
    public function testCollectArgs ()
    {
        $curry = $this->getMock("cPHP::Curry", array("exec"));
        
        $this->assertEquals(
                array(1, 2, 3),
                $curry->collectArgs( array(1, 2, 3) )
            );
        
        $curry->setLeft("l1", "l2");
        $this->assertEquals(
                array("l1", "l2", 1, 2, 3),
                $curry->collectArgs( array(1, 2, 3) )
            );
        
        $curry->setRight("r1", "r2");
        $this->assertEquals(
                array("l1", "l2", 1, 2, 3, "r1", "r2"),
                $curry->collectArgs( array(1, 2, 3) )
            );
        
        $curry->setOffset( 1 );
        $this->assertEquals(
                array("l1", "l2", 2, 3, "r1", "r2"),
                $curry->collectArgs( array(1, 2, 3) )
            );
        
        $curry->setLimit( 1 );
        
        $this->assertEquals(
                array("l1", "l2", 2, "r1", "r2"),
                $curry->collectArgs( array(1, 2, 3) )
            );
        
        $curry->clear();
        
        $curry->setLimit( 2 );
        $this->assertEquals(
                array(1, 2),
                $curry->collectArgs( array(1, 2, 3) )
            );
    }
    
    public function testCall ()
    {
        $curry = $this->getMock("cPHP::Curry", array("exec"));
        
        $curry->expects($this->once())
            ->method('exec')
            ->with( $this->equalTo( array("wakka", "test1") ) );
            
        $curry->call("wakka", "test1");
    }
    
    public function testCallWithLeftRight ()
    {
        $curry = $this->getMock("cPHP::Curry", array("exec"));
        $curry->setLeft("l1", "l2");
        $curry->setRight("r1", "r2");
        
        $curry->expects($this->once())
            ->method('exec')
            ->with( $this->equalTo( array("l1", "l2", 1, 2, 3, "r1", "r2") ) );
            
        $curry->call(1, 2, 3);
    }
    
    public function testCallWithSlicing ()
    {
        $curry = $this->getMock("cPHP::Curry", array("exec"));
        $curry->setLeft("l1", "l2");
        $curry->setRight("r1", "r2");
        $curry->setOffset(2)->setLimit(2);
        
        $curry->expects($this->once())
            ->method('exec')
            ->with( $this->equalTo( array("l1", "l2", 3, 4, "r1", "r2") ) );
            
        $curry->call(1, 2, 3, 4, 5, 6);
    }
    
    public function testApply ()
    {
        $curry = $this->getMock("cPHP::Curry", array("exec"));
        
        $curry->expects($this->once())
            ->method('exec')
            ->with( $this->equalTo( array("wakka", "test1") ) );
            
        $curry->apply( array("wakka", "test1") );
    }
    
    public function testApplyWithLeftRight ()
    {
        $curry = $this->getMock("cPHP::Curry", array("exec"));
        $curry->setLeft("l1", "l2");
        $curry->setRight("r1", "r2");
        
        $curry->expects($this->once())
            ->method('exec')
            ->with( $this->equalTo( array("l1", "l2", 1, 2, 3, "r1", "r2") ) );
            
        $curry->apply( array(1, 2, 3) );
    }
    
    public function testApplyWithSlicing ()
    {
        $curry = $this->getMock("cPHP::Curry", array("exec"));
        $curry->setLeft("l1", "l2");
        $curry->setRight("r1", "r2");
        $curry->setOffset(2)->setLimit(2);
        
        $curry->expects($this->once())
            ->method('exec')
            ->with( $this->equalTo( array("l1", "l2", 3, 4, "r1", "r2") ) );
            
        $curry->apply( array(1, 2, 3, 4, 5, 6) );
    }
    
    public function testInvoke ()
    {
        $curry = $this->getMock("cPHP::Curry", array("exec"));
        
        $curry->expects($this->once())
            ->method('exec')
            ->with( $this->equalTo( array("wakka", "test1") ) );
            
        $curry("wakka", "test1");
    }
    
    public function testInvokeWithLeftRight ()
    {
        $curry = $this->getMock("cPHP::Curry", array("exec"));
        $curry->setLeft("l1", "l2");
        $curry->setRight("r1", "r2");
        
        $curry->expects($this->once())
            ->method('exec')
            ->with( $this->equalTo( array("l1", "l2", 1, 2, 3, "r1", "r2") ) );
            
        $curry(1, 2, 3);
    }
    
    public function testInvokeWithSlicing ()
    {
        $curry = $this->getMock("cPHP::Curry", array("exec"));
        $curry->setLeft("l1", "l2");
        $curry->setRight("r1", "r2");
        $curry->setOffset(2)->setLimit(2);
        
        $curry->expects($this->once())
            ->method('exec')
            ->with( $this->equalTo( array("l1", "l2", 3, 4, "r1", "r2") ) );
            
        $curry(1, 2, 3, 4, 5, 6);
    }
    
    public function testFilter ()
    {
        $curry = $this->getMock("cPHP::Curry", array("exec"));
        
        $curry->expects($this->once())
            ->method('exec')
            ->with( $this->equalTo( array("wakka") ) );
            
        $curry->filter("wakka");
    }
    
    public function testFilterWithLeftRight ()
    {
        $curry = $this->getMock("cPHP::Curry", array("exec"));
        $curry->setLeft("l1", "l2");
        $curry->setRight("r1", "r2");
        
        $curry->expects($this->once())
            ->method('exec')
            ->with( $this->equalTo( array("l1", "l2", 1, "r1", "r2") ) );
            
        $curry->filter(1);
    }
    
    public function testFilterWithZeroLimit ()
    {
        $curry = $this->getMock("cPHP::Curry", array("exec"));
        $curry->setLeft("l1", "l2");
        $curry->setRight("r1", "r2");
        $curry->setLimit(0);
        
        $curry->expects($this->once())
            ->method('exec')
            ->with( $this->equalTo( array("l1", "l2", "r1", "r2") ) );
            
        $curry->filter(1);
    }

}

?>