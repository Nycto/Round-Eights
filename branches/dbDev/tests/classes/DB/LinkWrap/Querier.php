<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_db_linkwrap_querier extends PHPUnit_Framework_TestCase
{
    
    public function testQuery ()
    {
        
        $Link = $this->getMock(
                "cPHP::iface::DB::Link",
                array("query", "quote", "escape")
            );
        
        $query = new ::cPHP::DB::LinkWrap::Querier( $Link );
        
        
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
                    new ::cPHP::Exception::DB::Query("SELECT * FROM table", "test exception")
                ) );
        
        try {
            $query->query("SELECT * FROM table");
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::DB::Query $err ) {
            $this->assertSame("test exception", $err->getMessage());
        }
        
        
        $Link->expects( $this->at(0) )
            ->method("query")
            ->with(
                    $this->equalTo("SELECT * FROM table"),
                    $this->equalTo( ::cPHP::DB::LinkWrap::Querier::SILENT )
                )
            ->will( $this->throwException(
                    new ::cPHP::Exception::DB::Query("SELECT * FROM table", "test exception")
                ) );
            
        $this->assertFalse(
                $query->query("SELECT * FROM table", ::cPHP::DB::LinkWrap::Querier::SILENT)
            );
    }
    
    public function testBegin ()
    {
        $Link = $this->getMock(
                "cPHP::iface::DB::Link",
                array("query", "quote", "escape")
            );
        
        $query = new ::cPHP::DB::LinkWrap::Querier( $Link );
        
        $Link->expects( $this->once() )
            ->method("query")
            ->with( $this->equalTo("BEGIN") )
            ->will( $this->returnValue("Result Set") );
            
        $this->assertSame( $query, $query->begin() );
    }
    
    public function testCommit ()
    {
        $Link = $this->getMock(
                "cPHP::iface::DB::Link",
                array("query", "quote", "escape")
            );
        
        $query = new ::cPHP::DB::LinkWrap::Querier( $Link );
        
        $Link->expects( $this->once() )
            ->method("query")
            ->with( $this->equalTo("COMMIT") )
            ->will( $this->returnValue("Result Set") );
            
        $this->assertSame( $query, $query->commit() );
    }
    
    public function testRollBack ()
    {
        $Link = $this->getMock(
                "cPHP::iface::DB::Link",
                array("query", "quote", "escape")
            );
        
        $query = new ::cPHP::DB::LinkWrap::Querier( $Link );
        
        $Link->expects( $this->once() )
            ->method("query")
            ->with( $this->equalTo("ROLLBACK") )
            ->will( $this->returnValue("Result Set") );
            
        $this->assertSame( $query, $query->rollBack() );
    }
    
    public function testGetFieldList ()
    {
        
        $Link = $this->getMock(
                "cPHP::iface::DB::Link",
                array("query", "quote", "escape")
            );
        
        $query = new ::cPHP::DB::LinkWrap::Querier( $Link );
        
        
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
        
        $query = new ::cPHP::DB::LinkWrap::Querier( $Link );
        
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