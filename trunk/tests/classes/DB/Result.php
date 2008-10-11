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
class classes_db_result
{
    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite('commonPHP Database Result Class');
        $suite->addLib();
        $suite->addTestSuite( 'classes_db_result_tests' );
        return $suite;
    }
}

/**
 * unit tests
 */
class classes_db_result_tests extends PHPUnit_Framework_TestCase
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
    
    public function testHasResource ()
    {
        $mock = $this->getMock(
                "cPHP::DB::Result",
                array("rawFree"),
                array("not a resource", "SELECT * FROM table")
            );
        
        $this->assertFalse(
                $mock->hasResource()
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
                array("rawFree", "hasResource"),
                array("not a resource", "SELECT * FROM table")
            );
        
        $mock->expects( $this->at(0) )
            ->method("hasResource")
            ->will( $this->returnValue(TRUE) );
        
        $mock->expects( $this->once() )
            ->method("rawFree");
            
        $this->assertSame( $mock, $mock->free() );
    }
    
    public function testDestruct ()
    {
        $mock = $this->getMock(
                "cPHP::DB::Result",
                array("rawFree", "hasResource"),
                array("not a resource", "SELECT * FROM table")
            );
        
        $mock->expects( $this->at(0) )
            ->method("hasResource")
            ->will( $this->returnValue(TRUE) );
        
        $mock->expects( $this->once() )
            ->method("rawFree");
            
        $mock->__destruct();
    }
    
    public function testGetResource ()
    {
        
        $mock = $this->getMock(
                "cPHP::DB::Result",
                array("rawFree", "hasResource"),
                array("not a resource", "SELECT * FROM table")
            );
        
        $this->assertNull( $mock->getResource() );
    }
    
}

?>