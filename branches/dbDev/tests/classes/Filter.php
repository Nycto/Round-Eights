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
class classes_filter extends PHPUnit_Framework_TestCase
{
    
    public function testInvoke ()
    {
        
        $mock = $this->getMock("cPHP::Filter", array("filter"));
        
        $mock->expects($this->once())
            ->method('filter')
            ->with($this->equalTo('Input Value'))
            ->will($this->returnValue('Filtered Value'));
        
        $this->assertEquals( "Filtered Value", $mock('Input Value') );
        
    }
    
    public function testCallStatic ()
    {
        $filter = cPHP::Filter::StandardEmpty();
        $this->assertThat( $filter, $this->isInstanceOf("cPHP::Filter::StandardEmpty") );
        $this->assertEquals( 0, $filter->getFlags() );
        $this->assertNull( $filter->getValue() );
        
        $filter = cPHP::Filter::StandardEmpty( "Empty Value" );
        $this->assertThat( $filter, $this->isInstanceOf("cPHP::Filter::StandardEmpty") );
        $this->assertEquals( 0, $filter->getFlags() );
        $this->assertEquals( "Empty Value", $filter->getValue() );
        
        $filter = cPHP::Filter::StandardEmpty( "Empty Value", 5 );
        $this->assertThat( $filter, $this->isInstanceOf("cPHP::Filter::StandardEmpty") );
        $this->assertEquals( 5, $filter->getFlags() );
        $this->assertEquals( "Empty Value", $filter->getValue() );
    }
    
}

?>