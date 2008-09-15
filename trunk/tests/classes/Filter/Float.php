<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once 'PHPUnit/Framework.php';
require_once rtrim( dirname( __FILE__ ), "/" ) ."/../../general.php";

/**
 * test suite
 */
class classes_filter_float
{
    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite('commonPHP Float Filter Class');
        $suite->addLib();
        $suite->addTestSuite( 'classes_filter_float_tests' );
        
        return $suite;
    }
}

/**
 * unit tests
 */
class classes_filter_float_tests extends PHPUnit_Framework_TestCase
{
    
    public function testInteger ()
    {
        $filter = new cPHP::Filter::Float;
        $this->assertSame( 1.0, $filter->filter(1) );
        $this->assertSame( 20.0, $filter->filter(20) );
        $this->assertSame( -10.0, $filter->filter(-10) );
        $this->assertSame( 0.0, $filter->filter(0) );
    }
    
    public function testBoolean ()
    {
        $filter = new cPHP::Filter::Float;
        $this->assertSame( 1.0, $filter->filter(TRUE) );
        $this->assertSame( 0.0, $filter->filter(FALSE) );
    }
    
    public function testFloat ()
    {
        $filter = new cPHP::Filter::Float;
        $this->assertSame( 1.0, $filter->filter(1.0) );
        $this->assertSame( .5, $filter->filter(.5) );
        $this->assertSame( 20.25, $filter->filter(20.25) );
        $this->assertSame( -10.75, $filter->filter(-10.75) );
        $this->assertSame( 0.0, $filter->filter(0.0) );
    }
    
    public function testNull ()
    {
        $filter = new cPHP::Filter::Float;
        $this->assertSame( 0.0, $filter->filter(NULL) );
    }
    
    public function testIntegerString ()
    {
        $filter = new cPHP::Filter::Float;
        
        $this->assertSame( 0.0, $filter->filter("Some String") );
        $this->assertSame( 20.0, $filter->filter("20") );
        $this->assertSame( -20.0, $filter->filter("-20") );
        $this->assertSame( -40.0, $filter->filter("- 40") );
        $this->assertSame( 404040.0, $filter->filter("40-40-40") );
        $this->assertSame( -402030.0, $filter->filter("-40-20-30") );
        $this->assertSame( 50.0, $filter->filter("Some50String") );
        
    }
    
    public function testFloatString ()
    {
        $filter = new cPHP::Filter::Float;
        
        $this->assertSame( 20.0, $filter->filter("20.0") );
        $this->assertSame( -20.04, $filter->filter("-20.04") );
        $this->assertSame( -40.90, $filter->filter("- 40.90d") );
        $this->assertSame( 50.123, $filter->filter("Some50.123String") );
        $this->assertSame( 50.12, $filter->filter("Some50.12.3String") );
    }
    
    public function testArray ()
    {
        $filter = new cPHP::Filter::Float;
        
        $this->assertSame( 50.5, $filter->filter( array(50.5) ) );
        $this->assertSame( 0.0, $filter->filter( array() ) );
    }
    
    public function testObject ()
    {
        $filter = new cPHP::Filter::Float;
        
        $this->assertSame( 1.0, $filter->filter( $this->getMock("stub_random_obj") ) );
    }
    
}

?>