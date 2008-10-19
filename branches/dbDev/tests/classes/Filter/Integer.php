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
class classes_filter_integer extends PHPUnit_Framework_TestCase
{
    
    public function testInteger ()
    {
        $filter = new cPHP::Filter::Integer;
        $this->assertSame( 1, $filter->filter(1) );
        $this->assertSame( 20, $filter->filter(20) );
        $this->assertSame( -10, $filter->filter(-10) );
        $this->assertSame( 0, $filter->filter(0) );
    }
    
    public function testBoolean ()
    {
        $filter = new cPHP::Filter::Integer;
        $this->assertSame( 1, $filter->filter(TRUE) );
        $this->assertSame( 0, $filter->filter(FALSE) );
    }
    
    public function testFloat ()
    {
        $filter = new cPHP::Filter::Integer;
        $this->assertSame( 1, $filter->filter(1.0) );
        $this->assertSame( 0, $filter->filter(.5) );
        $this->assertSame( 20, $filter->filter(20.25) );
        $this->assertSame( -10, $filter->filter(-10.75) );
        $this->assertSame( 0, $filter->filter(0.0) );
    }
    
    public function testNull ()
    {
        $filter = new cPHP::Filter::Integer;
        
        $this->assertSame( 0, $filter->filter(NULL) );
    }
    
    public function testString ()
    {
        $filter = new cPHP::Filter::Integer;
        
        $this->assertSame( 0, $filter->filter("Some String") );
        $this->assertSame( 20, $filter->filter("20") );
        $this->assertSame( -20, $filter->filter("-20") );
        $this->assertSame( -40, $filter->filter("- 40") );
        $this->assertSame( 404040, $filter->filter("40-40-40") );
        $this->assertSame( -402030, $filter->filter("-40-20-30") );
        $this->assertSame( 50, $filter->filter("Some50String") );
        $this->assertSame( 20, $filter->filter("20.0") );
        $this->assertSame( -20, $filter->filter("-20.04") );
        $this->assertSame( -40, $filter->filter("- 40.90d") );
        $this->assertSame( 50, $filter->filter("Some50.123String") );
    }
    
    public function testArray ()
    {
        $filter = new cPHP::Filter::Integer;
        
        $this->assertSame( 50, $filter->filter( array(50) ) );
        $this->assertSame( 0, $filter->filter( array() ) );
    }
    
    public function testObject ()
    {
        $filter = new cPHP::Filter::Integer;
        
        $this->assertSame( 1, $filter->filter( $this->getMock("stub_random_obj") ) );
        
    }
    
}

?>