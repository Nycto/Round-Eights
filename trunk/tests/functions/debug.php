<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once rtrim( dirname( __FILE__ ), "/" ) ."/../general.php";

/**
 * test suite
 */
class functions_debug
{
    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite('commonPHP Debug Functions');
        $suite->addLib();
        $suite->addTestSuite( 'functions_debug_tests' );
        return $suite;
    }
}

/**
 * unit tests
 */
class functions_debug_tests extends PHPUnit_Framework_TestCase
{
    
    public function testGetDump ()
    {
        $this->assertEquals( "bool(TRUE)", cPHP::getDump( TRUE ) );
        $this->assertEquals( "bool(FALSE)", cPHP::getDump( FALSE ) );
        
        $this->assertEquals( "null()", cPHP::getDump( null ) );
        
        $this->assertEquals( "int(1)", cPHP::getDump( 1 ) );
        
        $this->assertEquals( "float(10.5)", cPHP::getDump( 10.5 ) );
        
        $this->assertEquals( "string('some string')", cPHP::getDump( "some string" ) );
        $this->assertEquals(
                "string('some string that is goi'...'after fifty characters')",
                cPHP::getDump( "some string that is going to be trimmed after fifty characters" )
            );
        $this->assertEquals( "string('some\\nstring\\twith\\rbreaks')", cPHP::getDump( "some\nstring\twith\rbreaks" ) );
        
        $this->assertEquals( "array(0)", cPHP::getDump( array() ) );
        $this->assertEquals( "array(1)(int(0) => int(5))", cPHP::getDump( array( 5 ) ) );
        $this->assertEquals(
                "array(2)(int(0) => string('string'), int(20) => float(1.5))",
                cPHP::getDump( array( "string", 20 => 1.5 ) )
            );
        $this->assertEquals(
                "array(5)(int(0) => int(1), int(1) => int(2),...)",
                cPHP::getDump( array( 1, 2, 3, 4, 20 ) )
            );
        $this->assertEquals(
                "array(1)(int(0) => array(2))",
                cPHP::getDump( array( array( 5, 6 ) ) )
            );
        
        $this->assertEquals( "object(Exception)", cPHP::getDump( new Exception ) );
        
        $this->assertEquals( "resource(stream)", cPHP::getDump( fopen( __FILE__, "r" ) ) );
    }
    
}

?>