<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once rtrim( dirname( __FILE__ ), "/" ) ."/../../general.php";

/**
 * test suite
 */
class classes_filter_aryoffset
{
    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite('commonPHP Array Offset Filtering Class');
        $suite->addLib();
        $suite->addTestSuite( 'classes_filter_aryoffset_tests' );
        
        return $suite;
    }
}

/**
 * unit tests
 */
class classes_filter_aryoffset_tests extends PHPUnit_Framework_TestCase
{
    
    public function testSetFilter ()
    {
        $filter = new ::cPHP::Filter::AryOffset;
        
        $intFilter = new ::cPHP::Filter::Integer;
        $this->assertEquals(
                $filter,
                $filter->setFilter( 50, $intFilter )
            );
        
        $list = $filter->getFilters();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $this->assertSame(
                array( 50 => $intFilter ),
                $list->get()
            );
        
        
        $boolFilter = new ::cPHP::Filter::Boolean;
        $this->assertEquals(
                $filter,
                $filter->setFilter( 50, $boolFilter )
            );
        
        $list = $filter->getFilters();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $this->assertSame(
                array( 50 => $boolFilter ),
                $list->get()
            );
        
        
        $this->assertEquals(
                $filter,
                $filter->setFilter( "str", $intFilter)
            );
        
        $list = $filter->getFilters();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $this->assertSame(
                array( 50 => $boolFilter, "str" => $intFilter ),
                $list->get()
            );
    }
    
    public function testImport ()
    {
        $filter = new ::cPHP::Filter::AryOffset;
        
        $filter->import(array(
                5 => new ::cPHP::Filter::Number,
                "index" => new ::cPHP::Filter::URL
            ));
        
        $list = $filter->getFilters();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $list = $list->get();
        
        $this->assertArrayHasKey( 5, $list );
        $this->assertThat( $list[5], $this->isInstanceOf("cPHP::Filter::Number") );
        
        $this->assertArrayHasKey( "index", $list );
        $this->assertThat( $list["index"], $this->isInstanceOf("cPHP::Filter::URL") );
    }
    
    public function testConstruct ()
    {
        $filter = new ::cPHP::Filter::AryOffset(array(
                5 => new ::cPHP::Filter::Number,
                "index" => new ::cPHP::Filter::URL
            ));
        
        $list = $filter->getFilters();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $list = $list->get();
        
        $this->assertArrayHasKey( 5, $list );
        $this->assertThat( $list[5], $this->isInstanceOf("cPHP::Filter::Number") );
        
        $this->assertArrayHasKey( "index", $list );
        $this->assertThat( $list["index"], $this->isInstanceOf("cPHP::Filter::URL") );
    }
    
    public function testFilter ()
    {
        $filter = new ::cPHP::Filter::AryOffset(array(
                1 => new ::cPHP::Filter::Number,
                5 => new ::cPHP::Filter::Boolean
            ));
        
        $this->assertSame(
                array( 1 => 10, 5 => true ),
                $filter->filter(array( 1 => "10", 5 => 1 ))
            );
        
        $ary = new ::cPHP::Ary(array( 1 => "10", 5 => 1 ));
        $result = $filter->filter( $ary );
        
        $this->assertSame( $ary, $result );
        $this->assertSame(
                array( 1 => 10, 5 => true ),
                $result->get()
            );
        
    }
    
}

?>