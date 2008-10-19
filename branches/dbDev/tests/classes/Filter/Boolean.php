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
class classes_filter_boolean extends PHPUnit_Framework_TestCase
{
    
    public function testBoolean ()
    {
        $filter = new cPHP::Filter::Boolean;
        $this->assertTrue( $filter->filter(TRUE) );
        $this->assertFalse( $filter->filter(FALSE) );
    }
    
    public function testInteger ()
    {
        $filter = new cPHP::Filter::Boolean;
        $this->assertTrue( $filter->filter(1) );
        $this->assertTrue( $filter->filter(20) );
        $this->assertTrue( $filter->filter(-10) );
        $this->assertFalse( $filter->filter(0) );
    }
    
    public function testFloat ()
    {
        $filter = new cPHP::Filter::Boolean;
        $this->assertTrue( $filter->filter(1.0) );
        $this->assertTrue( $filter->filter(.5) );
        $this->assertTrue( $filter->filter(20.5) );
        $this->assertTrue( $filter->filter(-10.5) );
        $this->assertFalse( $filter->filter(0.0) );
    }
    
    public function testNull ()
    {
        $filter = new cPHP::Filter::Boolean;
        
        $this->assertFalse( $filter->filter(NULL) );
    }
    
    public function testString ()
    {
        $filter = new cPHP::Filter::Boolean;
        
        $this->assertTrue( $filter->filter("t") );
        $this->assertTrue( $filter->filter("T") );
        $this->assertTrue( $filter->filter("true") );
        $this->assertTrue( $filter->filter("TRUE") );
        
        $this->assertTrue( $filter->filter("y") );
        $this->assertTrue( $filter->filter("Y") );
        $this->assertTrue( $filter->filter("yes") );
        $this->assertTrue( $filter->filter("YES") );
        
        $this->assertTrue( $filter->filter("on") );
        $this->assertTrue( $filter->filter("ON") );
        
        $this->assertTrue( $filter->filter("Some Other String") );
        
        $this->assertFalse( $filter->filter("f") );
        $this->assertFalse( $filter->filter("F") );
        $this->assertFalse( $filter->filter("false") );
        $this->assertFalse( $filter->filter("FALSE") );
        
        $this->assertFalse( $filter->filter("n") );
        $this->assertFalse( $filter->filter("N") );
        $this->assertFalse( $filter->filter("no") );
        $this->assertFalse( $filter->filter("NO") );
        
        $this->assertFalse( $filter->filter("off") );
        $this->assertFalse( $filter->filter("OFF") );
        
        $this->assertFalse( $filter->filter("") );
        $this->assertFalse( $filter->filter("  ") );
    }
    
    public function testArray ()
    {
        $filter = new cPHP::Filter::Boolean;
        
        $this->assertTrue( $filter->filter( array(50) ) );
        $this->assertFalse( $filter->filter( array() ) );
    }
    
    public function testOther ()
    {
        $filter = new cPHP::Filter::Boolean;
        
        $this->assertTrue( $filter->filter( $this->getMock("stub_spoof") ) );
    }
    
}

?>