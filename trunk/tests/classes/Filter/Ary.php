<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_filter_ary extends PHPUnit_Framework_TestCase
{
    
    public function testConstruct ()
    {
        $int = new ::cPHP::Filter::Integer;
        
        $filter = new ::cPHP::Filter::Ary( $int );
        
        $this->assertSame( $int, $filter->getFilter() );
    }
    
    public function testSetFilter ()
    {
        $int = new ::cPHP::Filter::Integer;
        
        $filter = new ::cPHP::Filter::Ary( $int );
        
        $this->assertSame( $int, $filter->getFilter() );
        
        $bool = new ::cPHP::Filter::Boolean;
        $this->assertSame( $filter, $filter->setFilter($bool) );
        
        $this->assertSame( $bool, $filter->getFilter() );
    }
    
    public function testFilter ()
    {
        $int = new ::cPHP::Filter::Integer;
        $filter = new ::cPHP::Filter::Ary( $int );
        
        $this->assertSame(
                array(5, 10, 20),
                $filter->filter(array("5", "10.5", 20.2))
            );
    }
    
    public function testFilter_nonAry ()
    {
        $int = new ::cPHP::Filter::Integer;
        $filter = new ::cPHP::Filter::Ary( $int );
        
        $this->assertSame(
                array( 28 ),
                $filter->filter("28")
            );
        
        $this->assertSame(
                array( 28 ),
                $filter->filter( new ::cPHP::Ary(array("28")) )->get()
            );
    }
    
}

?>