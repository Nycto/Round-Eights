<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once rtrim( dirname( __FILE__ ), "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_db_linkwrap extends PHPUnit_Framework_TestCase
{
    
    public function testGetLink ()
    {
        
        $link = $this->getMock(
                "cPHP::iface::DB::Link",
                array("query", "quote", "escape")
            );
        
        $mock = $this->getMock(
                "cPHP::DB::LinkWrap",
                array(),
                array( $link )
            );
        
        $this->assertSame( $link, $mock->getLink() );
    }
    
    public function testQuery ()
    {
        $link = $this->getMock(
                "cPHP::iface::DB::Link",
                array("query", "quote", "escape")
            );
        
        $mock = $this->getMock(
                "cPHP::DB::LinkWrap",
                array(),
                array( $link )
            );
        
        $link->expects( $this->once() )
            ->method( "query" )
            ->with( "SELECT * FROM table" )
            ->will( $this->returnValue("result") );
        
        $this->assertSame( "result", $mock->query("SELECT * FROM table") );
    }
    
    public function testQuote ()
    {
        $link = $this->getMock(
                "cPHP::iface::DB::Link",
                array("query", "quote", "escape")
            );
        
        $mock = $this->getMock(
                "cPHP::DB::LinkWrap",
                array(),
                array( $link )
            );
        
        $link->expects( $this->at(0) )
            ->method( "quote" )
            ->with( $this->equalTo("raw value"), $this->equalTo(TRUE) )
            ->will( $this->returnValue("quoted") );
        
        $this->assertSame( "quoted", $mock->quote("raw value") );
        
        $link->expects( $this->at(0) )
            ->method( "quote" )
            ->with( $this->equalTo("raw value"), $this->equalTo(FALSE) )
            ->will( $this->returnValue("quoted") );
        
        $this->assertSame( "quoted", $mock->quote("raw value", FALSE) );
    }
    
    public function testEscape()
    {
        $link = $this->getMock(
                "cPHP::iface::DB::Link",
                array("query", "quote", "escape")
            );
        
        $mock = $this->getMock(
                "cPHP::DB::LinkWrap",
                array(),
                array( $link )
            );
        
        $link->expects( $this->at(0) )
            ->method( "escape" )
            ->with( $this->equalTo("raw value"), $this->equalTo(TRUE) )
            ->will( $this->returnValue("escaped") );
        
        $this->assertSame( "escaped", $mock->escape("raw value") );
        
        $link->expects( $this->at(0) )
            ->method( "escape" )
            ->with( $this->equalTo("raw value"), $this->equalTo(FALSE) )
            ->will( $this->returnValue("escaped") );
        
        $this->assertSame( "escaped", $mock->escape("raw value", FALSE) );
    }
    
}

?>