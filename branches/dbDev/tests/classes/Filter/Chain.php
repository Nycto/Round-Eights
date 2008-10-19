<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once rtrim( dirname( __FILE__ ), "/" ) ."/../../general.php";

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