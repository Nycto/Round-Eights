<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once rtrim( dirname( __FILE__ ), "/" ) ."/../../../general.php";

/**
 * test suite
 */
class classes_db_adapter_query
{
    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite('commonPHP Database Query Adapter Class');
        $suite->addLib();
        $suite->addTestSuite( 'classes_db_adapter_query_tests' );
        return $suite;
    }
}

/**
 * unit tests
 */
class classes_db_adapter_query_tests extends PHPUnit_Framework_TestCase
{
    
    public function testGetFieldList ()
    {
        
        $connection = $this->getMock(
                "cPHP::iface::DB::Connection",
                array("query", "quote", "escape")
            );
        
        $query = new ::cPHP::DB::Adapter::Query( $connection );
        
        
        $connection->expects( $this->at(0) )
            ->method("quote")
            ->with( $this->equalTo("value") )
            ->will( $this->returnValue("'value'") );
        
        $connection->expects( $this->at(1) )
            ->method("quote")
            ->with( $this->equalTo("wakka") )
            ->will( $this->returnValue("'wakka'") );
        
        $this->assertEquals(
                $query->getFieldList( array('data' => 'value', 'label' => 'wakka') ),
                "`data` = 'value', `label` = 'wakka'"
            );
        
        
        $connection->expects( $this->at(0) )
            ->method("quote")
            ->with( $this->equalTo(5) )
            ->will( $this->returnValue("5") );

        $this->assertEquals(
                $query->getFieldList( array('data' => 5) ),
                "`data` = 5"
            );
        
        try {
            $query->getFieldList( "not an array" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::Argument $err ) {
            $this->assertSame("Must be an array or traversable", $err->getMessage());
        }
        
        
        try {
            $query->getFieldList( array() );
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }
        
    }

    
}

?>