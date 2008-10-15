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
class classes_db_result_read
{
    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite('commonPHP Database Read Result Class');
        $suite->addLib();
        $suite->addTestSuite( 'classes_db_result_read_tests' );
        return $suite;
    }
}

/**
 * unit tests
 */
class classes_db_result_read_tests extends PHPUnit_Framework_TestCase
{
    
    public function testGetNumRows_valid ()
    {
        $write = $this->getMock(
                "cPHP::DB::Result::Read",
                array("rawNumRows", "rawFetch", "rawSeek", "rawFields", "rawNumFields", "rawFree"),
                array(null, "SELECT * FROM table")
            );
        
        $write->expects( $this->once() )
            ->method("rawNumRows")
            ->will( $this->returnValue(20) );
        
        $this->assertSame( 20, $write->getNumRows() );
        $this->assertSame( 20, $write->getNumRows() );
        $this->assertSame( 20, $write->getNumRows() );
    }
    
    public function testGetNumRows_invalid ()
    {
        $write = $this->getMock(
                "cPHP::DB::Result::Read",
                array("rawNumRows", "rawFetch", "rawSeek", "rawFields", "rawNumFields", "rawFree"),
                array(null, "SELECT * FROM table")
            );
        
        $write->expects( $this->once() )
            ->method("rawNumRows")
            ->will( $this->returnValue(null) );
        
        $this->assertFalse( $write->getNumRows() );
        $this->assertFalse( $write->getNumRows() );
        $this->assertFalse( $write->getNumRows() );
    }
    
    public function testGetFields_valid ()
    {
        $write = $this->getMock(
                "cPHP::DB::Result::Read",
                array("rawNumRows", "rawFetch", "rawSeek", "rawFields", "rawNumFields", "rawFree"),
                array(null, "SELECT * FROM table")
            );
        
        $write->expects( $this->once() )
            ->method("rawFields")
            ->will( $this->returnValue(20) );
        
        $this->assertSame( 20, $write->getFields() );
        $this->assertSame( 20, $write->getFields() );
        $this->assertSame( 20, $write->getFields() );
    }
    
    public function testGetFields_invalid ()
    {
        $write = $this->getMock(
                "cPHP::DB::Result::Read",
                array("rawFields", "rawFetch", "rawSeek", "rawFields", "rawNumFields", "rawFree"),
                array(null, "SELECT * FROM table")
            );
        
        $write->expects( $this->once() )
            ->method("rawFields")
            ->will( $this->returnValue(null) );
        
        $this->assertFalse( $write->getFields() );
        $this->assertFalse( $write->getFields() );
        $this->assertFalse( $write->getFields() );
    }
    
}

?>