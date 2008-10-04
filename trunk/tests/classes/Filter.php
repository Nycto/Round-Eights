<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once rtrim( dirname( __FILE__ ), "/" ) ."/../general.php";

/**
 * test suite
 */
class classes_filter
{
    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite('commonPHP Boolean Filter Class');
        $suite->addLib();
        $suite->addTestSuite( 'classes_filter_tests' );
        
        return $suite;
    }
}

/**
 * unit tests
 */
class classes_filter_tests extends PHPUnit_Framework_TestCase
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