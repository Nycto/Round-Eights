<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once rtrim( dirname( __FILE__ ), "/" ) ."/../general.php";

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