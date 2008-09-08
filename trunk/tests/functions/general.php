<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once 'PHPUnit/Framework.php';
require_once rtrim( dirname( __FILE__ ), "/" ) ."/../general.php";

/**
 * general function test suite
 */
class functions_general
{
    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite('commonPHP general Functions');
        $suite->addLib();
        $suite->addTestSuite( 'functions_general_tests' );
        return $suite;
    }
}

/**
 * general function unit tests
 */
class functions_general_tests extends PHPUnit_Framework_TestCase
{
    
    public function testSwap ()
    {
        
        $var1 = "test";
        $var2 = "other";
        cPHP::swap($var1, $var2);

        $this->assertEquals("test", $var2);
        $this->assertEquals("other", $var1);
    }
    
    public function testReduce ()
    {
        $this->assertFalse( cPHP::reduce( FALSE ) );
        $this->assertTrue( cPHP::reduce( TRUE ) );
        $this->assertNull( cPHP::reduce( NULL ) );
        $this->assertEquals( 270, cPHP::reduce( 270 ) );
        $this->assertEquals( 151.12, cPHP::reduce( 151.12 ) );
        $this->assertEquals( 151.12, cPHP::reduce( array(151.12, 150) ) );
        $this->assertEquals( 151.12, cPHP::reduce( array( array(151.12, 150) ) ) );
    }
    
    public function testDefineIf ()
    {
        $this->assertFalse( defined("testDefineIf_example") );
        
        $this->assertTrue( cPHP::defineIf("testDefineIf_example", "value") );
        
        $this->assertTrue( defined("testDefineIf_example") );
        $this->assertEquals( "value", testDefineIf_example );
        
        $this->assertTrue( cPHP::defineIf("testDefineIf_example", "new value") );
        
        $this->assertEquals( "value", testDefineIf_example );
        
    }

    public function testIsEmpty ()
    {
        $this->assertTrue( cPHP::is_empty("") );
        $this->assertTrue( cPHP::is_empty(0) );
        $this->assertTrue( cPHP::is_empty(NULL) );
        $this->assertTrue( cPHP::is_empty(FALSE) );
        $this->assertTrue( cPHP::is_empty( array() ) );
        $this->assertTrue( cPHP::is_empty( "  " ) );

        $this->assertFalse( cPHP::is_empty("string") );
        $this->assertFalse( cPHP::is_empty(1) );
        $this->assertFalse( cPHP::is_empty("0") );
        $this->assertFalse( cPHP::is_empty("1") );
        $this->assertFalse( cPHP::is_empty(TRUE) );
        $this->assertFalse( cPHP::is_empty( array(1) ) );

        $this->assertFalse( cPHP::is_empty("", cPHP::ALLOW_BLANK) );
        $this->assertFalse( cPHP::is_empty(0, cPHP::ALLOW_ZERO) );
        $this->assertFalse( cPHP::is_empty(NULL, cPHP::ALLOW_NULL) );
        $this->assertFalse( cPHP::is_empty(FALSE, cPHP::ALLOW_FALSE) );
        $this->assertFalse( cPHP::is_empty( array(), cPHP::ALLOW_EMPTY_ARRAYS ) );
        $this->assertFalse( cPHP::is_empty( "  ", cPHP::ALLOW_SPACES ) );
    }

    public function testIsVague ()
    {
        $this->assertTrue( cPHP::is_vague(FALSE) );
        $this->assertTrue( cPHP::is_vague(TRUE) );
        $this->assertTrue( cPHP::is_vague("") );
        $this->assertTrue( cPHP::is_vague(0) );
        $this->assertTrue( cPHP::is_vague(NULL) );
        $this->assertTrue( cPHP::is_vague( array() ) );
        $this->assertTrue( cPHP::is_vague( "  " ) );

        $this->assertFalse( cPHP::is_vague("string") );
        $this->assertFalse( cPHP::is_vague(1) );
        $this->assertFalse( cPHP::is_vague("0") );
        $this->assertFalse( cPHP::is_vague("1") );
        $this->assertFalse( cPHP::is_vague( array(1) ) );
    }
    
    public function testArrayVal ()
    {
        $this->assertEquals( array(1, 2, 3), cPHP::arrayVal(array(1, 2, 3)) );
        $this->assertEquals( array(1), cPHP::arrayVal(1) );
    }
    
    public function testNumVal ()
    {
        $this->assertEquals( 1, cPHP::numVal(1) );
        $this->assertEquals( 1.5, cPHP::numVal(1.5) );
        $this->assertEquals( 1, cPHP::numVal("1") );
        $this->assertEquals( 1.5, cPHP::numVal("1.5") );
    }
    
    public function testBoolVal ()
    {
        $this->assertEquals( TRUE, cPHP::boolVal(TRUE) );
        $this->assertEquals( FALSE, cPHP::boolVal(FALSE) );
        $this->assertEquals( TRUE, cPHP::boolVal(1) );
        $this->assertEquals( FALSE, cPHP::boolVal(0) );
    }
    
    public function testStrVal ()
    {
        $this->assertEquals( "string", cPHP::strVal("string") );
        $this->assertEquals( "5", cPHP::strVal(5) );
    }

}

?>