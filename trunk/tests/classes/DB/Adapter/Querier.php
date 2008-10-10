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
class classes_db_adapter_querier
{
    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite('commonPHP Database Query Adapter Class');
        $suite->addLib();
        $suite->addTestSuite( 'classes_db_adapter_querier_tests' );
        return $suite;
    }
}

/**
 * unit tests
 */
class classes_db_adapter_querier_tests extends PHPUnit_Framework_TestCase
{
    
    public function testQuery ()
    {
        
        $Link = $this->getMock(
                "cPHP::iface::DB::Link",
                array("query", "quote", "escape")
            );
        
        $query = new ::cPHP::DB::Adapter::Querier( $Link );
        
        
        $Link->expects( $this->at(0) )
            ->method("query")
            ->with( $this->equalTo("SELECT * FROM table"), $this->equalTo(0) )
            ->will( $this->returnValue("Result Set") );
        
        $this->assertSame( "Result Set", $query->query("SELECT * FROM table") );
        
        
        $Link->expects( $this->at(0) )
            ->method("query")
            ->with( $this->equalTo("SELECT * FROM table"), $this->equalTo(5) )
            ->will( $this->returnValue("Result Set") );
        
        $this->assertSame( "Result Set", $query->query("SELECT * FROM table", 5) );
        
        
        $Link->expects( $this->at(0) )
            ->method("query")
            ->with( $this->equalTo("SELECT * FROM table"), $this->equalTo(0) )
            ->will( $this->throwException(
                    new ::cPHP::Exception::Database::Query("SELECT * FROM table", "test exception")
                ) );
        
        try {
            $query->query("SELECT * FROM table");
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::Database::Query $err ) {
            $this->assertSame("test exception", $err->getMessage());
        }
        
        
        $Link->expects( $this->at(0) )
            ->method("query")
            ->with(
                    $this->equalTo("SELECT * FROM table"),
                    $this->equalTo( ::cPHP::DB::Adapter::Querier::SILENT )
                )
            ->will( $this->throwException(
                    new ::cPHP::Exception::Database::Query("SELECT * FROM table", "test exception")
                ) );
            
        $this->assertFalse(
                $query->query("SELECT * FROM table", ::cPHP::DB::Adapter::Querier::SILENT)
            );
    }
    
    public function testGetFieldList ()
    {
        
        $Link = $this->getMock(
                "cPHP::iface::DB::Link",
                array("query", "quote", "escape")
            );
        
        $query = new ::cPHP::DB::Adapter::Querier( $Link );
        
        
        $Link->expects( $this->at(0) )
            ->method("quote")
            ->with( $this->equalTo("value") )
            ->will( $this->returnValue("'value'") );
        
        $Link->expects( $this->at(1) )
            ->method("quote")
            ->with( $this->equalTo("wakka") )
            ->will( $this->returnValue("'wakka'") );
        
        $this->assertEquals(
                $query->getFieldList( array('data' => 'value', 'label' => 'wakka') ),
                "`data` = 'value', `label` = 'wakka'"
            );
        
        $Link->expects( $this->at(0) )
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

    public function testInsert ()
    {
        
        $Link = $this->getMock(
                "cPHP::iface::DB::Link",
                array("query", "quote", "escape")
            );
        
        $query = new ::cPHP::DB::Adapter::Querier( $Link );
        
        try {
            $query->insert( "", array() );
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }
        
        try {
            $query->insert( "tablename", array() );
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }
        
        try {
            $query->insert( "tablename", "some string" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::Argument $err ) {
            $this->assertSame("Must be an array or traversable", $err->getMessage());
        }
        
        $this->markTestIncomplete("Results need to be implemented before this can be completed");
    }
    
}

?>