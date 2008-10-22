<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * This is a stub used to test the iteration 
 */
class stub_db_result_read extends ::cPHP::DB::Result::Read
{
    
    /**
     * The data that will be iterated over
     */
    public $ary = array();
    
    /**
     * Counts the elements in the test array
     *
     * @return Integer
     */
    protected function rawCount ()
    {
        return count( $this->ary );
    }
    
    /**
     * Returns an empty array
     *
     * @return Array
     */
    protected function rawFields ()
    {
        return array();
    }
    
    /**
     * Returns the next value from the test array
     *
     * @return Array
     */
    protected function rawFetch ()
    {
        $nextData = each( $this->ary );
        return $nextData['value'];
    }
    
    /**
     * Seeks to a specific row in the test array
     *
     * @param Integer The raw to seek to
     */
    protected function rawSeek ( $offset )
    {
        if ( $offset == 0 ) {
            $return = reset( $this->ary );
            next( $this->ary );
            return $return;
        }
    }
    
    /**
     * Resets the result array to empty
     */
    protected function rawFree ()
    {
        $this->ary = array();
    }
    
}

/**
 * unit tests
 */
class classes_db_result_read extends PHPUnit_Framework_TestCase
{
    
    public function testHasResult_None ()
    {
        $mock = $this->getMock(
                "cPHP::DB::Result::Read",
                array("rawCount", "rawFetch", "rawSeek", "rawFields", "rawFree"),
                array("not a resource", "SELECT * FROM table")
            );
        
        $this->assertFalse(
                $mock->hasResult()
            );
    }
    
    public function testHasResult_Object ()
    {
        $mock = $this->getMock(
                "cPHP::DB::Result::Read",
                array("rawCount", "rawFetch", "rawSeek", "rawFields", "rawFree"),
                array( $this->getMock("MockResult"), "SELECT * FROM table")
            );
        
        $this->assertTrue(
                $mock->hasResult()
            );
    }
    
    public function testFree_noResult ()
    {
        $mock = $this->getMock(
                "cPHP::DB::Result::Read",
                array("rawCount", "rawFetch", "rawSeek", "rawFields", "rawFree"),
                array("not a resource", "SELECT * FROM table")
            );
        
        $mock->expects( $this->never() )
            ->method("rawFree");
            
        $this->assertSame( $mock, $mock->free() );
    }
    
    public function testFree_fakedResult ()
    {
        $mock = $this->getMock(
                "cPHP::DB::Result::Read",
                array("rawCount", "rawFetch", "rawSeek", "rawFields", "rawFree", "hasResult"),
                array("not a resource", "SELECT * FROM table")
            );
        
        $mock->expects( $this->at(0) )
            ->method("hasResult")
            ->will( $this->returnValue(TRUE) );
        
        $mock->expects( $this->once() )
            ->method("rawFree");
            
        $this->assertSame( $mock, $mock->free() );
    }
    
    public function testDestruct ()
    {
        $mock = $this->getMock(
                "cPHP::DB::Result::Read",
                array("rawCount", "rawFetch", "rawSeek", "rawFields", "rawFree", "hasResult"),
                array("not a resource", "SELECT * FROM table")
            );
        
        $mock->expects( $this->at(0) )
            ->method("hasResult")
            ->will( $this->returnValue(TRUE) );
        
        $mock->expects( $this->once() )
            ->method("rawFree");
            
        $mock->__destruct();
    }
    
    public function testCount_valid ()
    {
        $read = $this->getMock(
                "cPHP::DB::Result::Read",
                array("rawCount", "rawFetch", "rawSeek", "rawFields", "rawFree"),
                array(null, "SELECT * FROM table")
            );
        
        $read->expects( $this->once() )
            ->method("rawCount")
            ->will( $this->returnValue(20) );
        
        $this->assertSame( 20, $read->count() );
        $this->assertSame( 20, $read->count() );
        $this->assertSame( 20, $read->count() );
        $this->assertSame( 20, count( $read ) );
    }
    
    public function testCount_invalid ()
    {
        $read = $this->getMock(
                "cPHP::DB::Result::Read",
                array("rawCount", "rawFetch", "rawSeek", "rawFields", "rawFree"),
                array(null, "SELECT * FROM table")
            );
        
        $read->expects( $this->once() )
            ->method("rawCount")
            ->will( $this->returnValue(null) );
        
        $this->assertSame( 0, $read->count() );
        $this->assertSame( 0, $read->count() );
        $this->assertSame( 0, $read->count() );
        $this->assertSame( 0, count( $read ) );
    }
    
    public function testGetFields_valid ()
    {
        $read = $this->getMock(
                "cPHP::DB::Result::Read",
                array("rawCount", "rawFetch", "rawSeek", "rawFields", "rawFree"),
                array(null, "SELECT * FROM table")
            );
        
        $read->expects( $this->once() )
            ->method("rawFields")
            ->will( $this->returnValue( array("one", "two") ) );
        
        $this->assertSame( array("one", "two"), $read->getFields() );
        $this->assertSame( array("one", "two"), $read->getFields() );
        $this->assertSame( array("one", "two"), $read->getFields() );
    }
    
    public function testGetFields_invalid ()
    {
        $read = $this->getMock(
                "cPHP::DB::Result::Read",
                array("rawCount", "rawFetch", "rawSeek", "rawFields", "rawFree"),
                array(null, "SELECT * FROM table")
            );
        
        $read->expects( $this->once() )
            ->method("rawFields")
            ->will( $this->returnValue(null) );
        
        $this->assertSame( array(), $read->getFields() );
        $this->assertSame( array(), $read->getFields() );
        $this->assertSame( array(), $read->getFields() );
    }
    
    public function testSeek ()
    {
        $read = $this->getMock(
                "cPHP::DB::Result::Read",
                array("rawCount", "rawFetch", "rawSeek", "rawFields", "rawFree"),
                array(null, "SELECT * FROM table")
            );
        
        $read->expects( $this->once() )
            ->method("rawCount")
            ->will( $this->returnValue(5) );
        
        $read->expects( $this->at(1) )
            ->method("rawSeek")
            ->with( $this->equalTo(0) );
            
        $this->assertSame( $read, $read->seek( 0 ) );
        
        
        $read->expects( $this->at(0) )
            ->method("rawSeek")
            ->with( $this->equalTo(4) );
        
        $this->assertSame( $read, $read->seek( 6 ) );
        
        
        $read->expects( $this->never() )
            ->method("rawSeek");
        
        $this->assertSame( $read, $read->seek( 4 ) );
        
    }
    
    public function testIteration_forEach ()
    {
        $read = new stub_db_result_read( null, "SELECT * FROM test" );
        $input = array(
                array("one", "two"),
                array("three", "four"),
                array("six", "five"),
            );
        $read->ary = $input;
        
        $result = array();
        foreach($read AS $key => $value) {
            $result[$key] = $value;
        }
        
        $this->assertSame( $result, $input );
        
        
        $result = array();
        foreach($read AS $key => $value) {
            $result[$key] = $value;
        }
        
        $this->assertSame( $result, $input );
        
    }
    
    public function testIteration_Manual()
    {
        $read = new stub_db_result_read( null, "SELECT * FROM test" );
        $input = array(
                array("one", "two"),
                array("three", "four")
            );
        $read->ary = $input;
        
        
        $this->assertSame( $read, $read->next() );
        $this->assertSame(
                array("one", "two"),
                $read->current()
            );
        $this->assertSame( 0, $read->key() );
        
        
        $this->assertSame( $read, $read->next() );
        $this->assertSame(
                array("three", "four"),
                $read->current()
            );
        $this->assertSame( 1, $read->key() );
        
        
        $this->assertSame( $read, $read->next() );
        $this->assertFalse( $read->current() );
        $this->assertSame( 2, $read->key() );
        
        
        $this->assertSame( $read, $read->next() );
        $this->assertFalse( $read->current() );
        $this->assertSame( 2, $read->key() );
    }
    
    public function testIsField ()
    {
        $this->markTestIncomplete("To be written");
    }
    
}

?>