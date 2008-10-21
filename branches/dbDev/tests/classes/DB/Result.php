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
class classes_db_result extends PHPUnit_Framework_TestCase
{
    public function testGetQuery ()
    {
        $mock = $this->getMock(
                "cPHP::DB::Result",
                array("rawFree"),
                array(null, "SELECT * FROM table")
            );
        
        $this->assertSame(
                "SELECT * FROM table",
                $mock->getQuery()
            );
    }
    
    public function testHasResult ()
    {
        $mock = $this->getMock(
                "cPHP::DB::Result",
                array("rawFree"),
                array("not a resource", "SELECT * FROM table")
            );
        
        $this->assertFalse(
                $mock->hasResult()
            );
    }
    
    public function testFree_noResource ()
    {
        $mock = $this->getMock(
                "cPHP::DB::Result",
                array("rawFree"),
                array("not a resource", "SELECT * FROM table")
            );
        
        $mock->expects( $this->never() )
            ->method("rawFree");
            
        $this->assertSame( $mock, $mock->free() );
    }
    
    public function testFree_fakedResource ()
    {
        $mock = $this->getMock(
                "cPHP::DB::Result",
                array("rawFree", "hasResult"),
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
                "cPHP::DB::Result",
                array("rawFree", "hasResult"),
                array("not a resource", "SELECT * FROM table")
            );
        
        $mock->expects( $this->at(0) )
            ->method("hasResult")
            ->will( $this->returnValue(TRUE) );
        
        $mock->expects( $this->once() )
            ->method("rawFree");
            
        $mock->__destruct();
    }
    
}

?>