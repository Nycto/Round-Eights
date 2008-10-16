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
        $read = $this->getMock(
                "cPHP::DB::Result::Read",
                array("rawNumRows", "rawFetch", "rawSeek", "rawFields", "rawFree"),
                array(null, "SELECT * FROM table")
            );
        
        $read->expects( $this->once() )
            ->method("rawNumRows")
            ->will( $this->returnValue(20) );
        
        $this->assertSame( 20, $read->getNumRows() );
        $this->assertSame( 20, $read->getNumRows() );
        $this->assertSame( 20, $read->getNumRows() );
    }
    
    public function testGetNumRows_invalid ()
    {
        $read = $this->getMock(
                "cPHP::DB::Result::Read",
                array("rawNumRows", "rawFetch", "rawSeek", "rawFields", "rawFree"),
                array(null, "SELECT * FROM table")
            );
        
        $read->expects( $this->once() )
            ->method("rawNumRows")
            ->will( $this->returnValue(null) );
        
        $this->assertFalse( $read->getNumRows() );
        $this->assertFalse( $read->getNumRows() );
        $this->assertFalse( $read->getNumRows() );
    }
    
    public function testGetFields_valid ()
    {
        $read = $this->getMock(
                "cPHP::DB::Result::Read",
                array("rawNumRows", "rawFetch", "rawSeek", "rawFields", "rawFree"),
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
                array("rawNumRows", "rawFetch", "rawSeek", "rawFields", "rawFree"),
                array(null, "SELECT * FROM table")
            );
        
        $read->expects( $this->once() )
            ->method("rawFields")
            ->will( $this->returnValue(null) );
        
        $this->assertSame( array(), $read->getFields() );
        $this->assertSame( array(), $read->getFields() );
        $this->assertSame( array(), $read->getFields() );
    }
    
}

?>