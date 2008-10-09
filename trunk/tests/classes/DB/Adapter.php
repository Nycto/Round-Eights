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
class classes_db_adapter
{
    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite('commonPHP Database Adapter Class');
        $suite->addLib();
        $suite->addTestSuite( 'classes_db_adapter_tests' );
        return $suite;
    }
}

/**
 * unit tests
 */
class classes_db_adapter_tests extends PHPUnit_Framework_TestCase
{
    
    public function testGetConnection ()
    {
        
        $connection = $this->getMock(
                "cPHP::iface::DB::Connection",
                array("query", "quote", "escape")
            );
        
        $mock = $this->getMock(
                "cPHP::DB::Adapter",
                array(),
                array( $connection )
            );
        
        $this->assertSame( $connection, $mock->getConnection() );
    }
    
    public function testQuery ()
    {
        $connection = $this->getMock(
                "cPHP::iface::DB::Connection",
                array("query", "quote", "escape")
            );
        
        $mock = $this->getMock(
                "cPHP::DB::Adapter",
                array(),
                array( $connection )
            );
        
        $connection->expects( $this->once() )
            ->method( "query" )
            ->with( "SELECT * FROM table" )
            ->will( $this->returnValue("result") );
        
        $this->assertSame( "result", $mock->query("SELECT * FROM table") );
    }
    
    public function testQuote ()
    {
        $connection = $this->getMock(
                "cPHP::iface::DB::Connection",
                array("query", "quote", "escape")
            );
        
        $mock = $this->getMock(
                "cPHP::DB::Adapter",
                array(),
                array( $connection )
            );
        
        $connection->expects( $this->at(0) )
            ->method( "quote" )
            ->with( $this->equalTo("raw value"), $this->equalTo(TRUE) )
            ->will( $this->returnValue("quoted") );
        
        $this->assertSame( "quoted", $mock->quote("raw value") );
        
        $connection->expects( $this->at(0) )
            ->method( "quote" )
            ->with( $this->equalTo("raw value"), $this->equalTo(FALSE) )
            ->will( $this->returnValue("quoted") );
        
        $this->assertSame( "quoted", $mock->quote("raw value", FALSE) );
    }
    
    public function testEscape()
    {
        $connection = $this->getMock(
                "cPHP::iface::DB::Connection",
                array("query", "quote", "escape")
            );
        
        $mock = $this->getMock(
                "cPHP::DB::Adapter",
                array(),
                array( $connection )
            );
        
        $connection->expects( $this->at(0) )
            ->method( "escape" )
            ->with( $this->equalTo("raw value"), $this->equalTo(TRUE) )
            ->will( $this->returnValue("escaped") );
        
        $this->assertSame( "escaped", $mock->escape("raw value") );
        
        $connection->expects( $this->at(0) )
            ->method( "escape" )
            ->with( $this->equalTo("raw value"), $this->equalTo(FALSE) )
            ->will( $this->returnValue("escaped") );
        
        $this->assertSame( "escaped", $mock->escape("raw value", FALSE) );
    }
    
}

?>