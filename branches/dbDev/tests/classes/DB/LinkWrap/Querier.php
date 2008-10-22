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

    public function testInsert_Errors ()
    {
        
        $link = $this->getMock(
                "cPHP::iface::DB::Link",
                array("query", "quote", "escape")
            );
        
        $query = new ::cPHP::DB::LinkWrap::Querier( $link );
        
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
    
    }
    
    public function testInsert_Success ()
    {
        
        $link = $this->getMock(
                "cPHP::iface::DB::Link",
                array("query", "quote", "escape")
            );
        
        $query = new ::cPHP::DB::LinkWrap::Querier( $link );
        
        $link->expects($this->once())
            ->method("query")
            ->with( $this->equalTo("INSERT INTO table SET `field1` = 404, `field2` = 'error'" ) )
            ->will( $this->returnValue(
                    new ::cPHP::DB::Result::Write(1, 20, "INSERT")
                ));
            
        $link->expects($this->at(0))
            ->method("quote")
            ->with( $this->equalTo(404) )
            ->will( $this->returnValue( 404 ));
            
        $link->expects($this->at(1))
            ->method("quote")
            ->with( $this->equalTo('error') )
            ->will( $this->returnValue("'error'"));
        
        $this->assertSame(
                20,
                $query->insert("table", array('field1' => 404, 'field2' => 'error'))
            );
    }
    
    public function testInsert_ReturnFalse ()
    {
        
        $link = $this->getMock(
                "cPHP::iface::DB::Link",
                array("query", "quote", "escape")
            );
        
        $query = new ::cPHP::DB::LinkWrap::Querier( $link );
        
        $link->expects($this->once())
            ->method("query")
            ->with( $this->equalTo("INSERT INTO table SET `field1` = 404, `field2` = 'error'" ) )
            ->will( $this->returnValue( FALSE ));
            
        $link->expects($this->at(0))
            ->method("quote")
            ->with( $this->equalTo(404) )
            ->will( $this->returnValue( 404 ));
            
        $link->expects($this->at(1))
            ->method("quote")
            ->with( $this->equalTo('error') )
            ->will( $this->returnValue("'error'"));
            
        $this->assertFalse(
                $query->insert("table", array('field1' => 404, 'field2' => 'error'))
            );
    }
    
    public function testUpdate_Errors ()
    {
        
        $link = $this->getMock(
                "cPHP::iface::DB::Link",
                array("query", "quote", "escape")
            );
        
        $query = new ::cPHP::DB::LinkWrap::Querier( $link );
        
        try {
            $query->update( "", null, array() );
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }
        
        try {
            $query->update( "tablename", null, array() );
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }
        
        try {
            $query->update( "tablename", null, "some string" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::Argument $err ) {
            $this->assertSame("Must be an array or traversable", $err->getMessage());
        }
    
    }
    
    public function testUpdate_NoWhere ()
    {
        
        $link = $this->getMock(
                "cPHP::iface::DB::Link",
                array("query", "quote", "escape")
            );
        
        $query = new ::cPHP::DB::LinkWrap::Querier( $link );
        
        $result = new ::cPHP::DB::Result::Write(1, null, "UPDATE");
        
        $link->expects($this->once())
            ->method("query")
            ->with( $this->equalTo("UPDATE table SET `field1` = 404, `field2` = 'error'" ) )
            ->will( $this->returnValue( $result ));
            
        $link->expects($this->at(0))
            ->method("quote")
            ->with( $this->equalTo(404) )
            ->will( $this->returnValue( 404 ));
            
        $link->expects($this->at(1))
            ->method("quote")
            ->with( $this->equalTo('error') )
            ->will( $this->returnValue("'error'"));
        
        $this->assertSame(
                $result,
                $query->update("table", null, array('field1' => 404, 'field2' => 'error'))
            );
    }
    
    public function testUpdate_WithWhere ()
    {
        
        $link = $this->getMock(
                "cPHP::iface::DB::Link",
                array("query", "quote", "escape")
            );
        
        $query = new ::cPHP::DB::LinkWrap::Querier( $link );
        
        $result = new ::cPHP::DB::Result::Write(1, null, "UPDATE");
        
        $link->expects($this->once())
            ->method("query")
            ->with( $this->equalTo("UPDATE table SET `field1` = 404, `field2` = 'error' WHERE id > 5" ) )
            ->will( $this->returnValue( $result ));
            
        $link->expects($this->at(0))
            ->method("quote")
            ->with( $this->equalTo(404) )
            ->will( $this->returnValue( 404 ));
            
        $link->expects($this->at(1))
            ->method("quote")
            ->with( $this->equalTo('error') )
            ->will( $this->returnValue("'error'"));
        
        $this->assertSame(
                $result,
                $query->update("table", "id > 5", array('field1' => 404, 'field2' => 'error'))
            );
    }
    
    public function testGetRow_WrongResult ()
    {
        
        $link = $this->getMock(
                "cPHP::iface::DB::Link",
                array("query", "quote", "escape")
            );
        
        $query = new ::cPHP::DB::LinkWrap::Querier( $link );
        
        
        $link->expects($this->once())
            ->method("query")
            ->with( $this->equalTo("UPDATE table SET id = 1" ) )
            ->will( $this->returnValue( new cPHP::DB::Result::Write(0, null, "UPDATE") ));
    
        try {
            $query->getRow( "UPDATE table SET id = 1" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::DB::Query $err ) {
            $this->assertSame("Query did not a valid Read result object", $err->getMessage());
        }
        
    }
    
    public function testGetRow_Valid ()
    {
        
        $link = $this->getMock(
                "cPHP::iface::DB::Link",
                array("query", "quote", "escape")
            );
        
        $query = new ::cPHP::DB::LinkWrap::Querier( $link );
        
        $result = $this->getMock(
                "cPHP::DB::Result::Read",
                array("rawCount", "rawFetch", "rawSeek", "rawFields", "rawFree"),
                array(null, "SELECT * FROM table")
            );
        
        $link->expects($this->once())
            ->method("query")
            ->with( $this->equalTo("SELECT * FROM table" ) )
            ->will( $this->returnValue( $result ));
    
        
        $result->expects( $this->once() )
            ->method("rawSeek")
            ->with( $this->equalTo(0) )
            ->will( $this->returnValue(array( 'one', 'two' )));
        
        $result->expects( $this->once() )
            ->method("rawCount")
            ->will( $this->returnValue(5));
    
        $this->assertSame(
                array( 'one', 'two' ),
                $query->getRow( "SELECT * FROM table" )
            );
        
    }
    
    public function testGetRow_OtherRow ()
    {
        
        $link = $this->getMock(
                "cPHP::iface::DB::Link",
                array("query", "quote", "escape")
            );
        
        $query = new ::cPHP::DB::LinkWrap::Querier( $link );
        
        $result = $this->getMock(
                "cPHP::DB::Result::Read",
                array("rawCount", "rawFetch", "rawSeek", "rawFields", "rawFree"),
                array(null, "SELECT * FROM table")
            );
        
        $link->expects($this->once())
            ->method("query")
            ->with( $this->equalTo("SELECT * FROM table" ) )
            ->will( $this->returnValue( $result ));
    
        
        $result->expects( $this->once() )
            ->method("rawSeek")
            ->with( $this->equalTo(3) )
            ->will( $this->returnValue(array( 'one', 'two' )));
        
        $result->expects( $this->once() )
            ->method("rawCount")
            ->will( $this->returnValue(5));
    
        $this->assertSame(
                array( 'one', 'two' ),
                $query->getRow( "SELECT * FROM table", 3 )
            );
        
    }
    
    public function testGetRow_NoResults ()
    {
        
        $link = $this->getMock(
                "cPHP::iface::DB::Link",
                array("query", "quote", "escape")
            );
        
        $query = new ::cPHP::DB::LinkWrap::Querier( $link );
        
        $result = $this->getMock(
                "cPHP::DB::Result::Read",
                array("rawCount", "rawFetch", "rawSeek", "rawFields", "rawFree"),
                array(null, "SELECT * FROM table")
            );
        
        $link->expects($this->once())
            ->method("query")
            ->with( $this->equalTo("SELECT * FROM table" ) )
            ->will( $this->returnValue( $result ));
    
        
        $result->expects( $this->never() )
            ->method("rawSeek");
        
        $result->expects( $this->once() )
            ->method("rawCount")
            ->will( $this->returnValue(0));
    
        $this->assertFalse(
                $query->getRow( "SELECT * FROM table" )
            );
        
    }
    
    public function testGetField ()
    {
        $this->markTestIncomplete("Not yet written");
    }
    
}

?>