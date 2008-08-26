<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once 'PHPUnit/Framework.php';
require_once rtrim( dirname( __FILE__ ), "/" ) ."/../general.php";

/**
 * test suite
 */
class classes_exception
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('commonPHP Exception Class');
        $suite->addTestSuite( 'general' );
        $suite->addTestSuite( 'classes_exception_tests' );
        return $suite;
    }
}

/**
 * unit tests
 */
class classes_exception_tests extends PHPUnit_Framework_TestCase
{
    
    public function testMessage ()
    {
        $err = new cPHP::Exception();
        $this->assertFalse( $err->issetMessage() );
        
        $err = new cPHP::Exception("This is a message");
        $this->assertTrue( $err->issetMessage() );
        $this->assertEquals( "This is a message", $err->getMessage() );
    }
    
    public function testCode ()
    {
        $err = new cPHP::Exception();
        $this->assertFalse( $err->issetCode() );
        
        $err = new cPHP::Exception("This is a message", 543);
        $this->assertTrue( $err->issetCode() );
        $this->assertEquals( 543, $err->getCode() );
    }
    
    public function testGetTraceByOffset ()
    {
        $err = new cPHP::Exception();
        
        $this->assertThat( $err->getTraceByOffset(0), $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals(
                __FUNCTION__,
                $err->getTraceByOffset(0)->offsetGet("function")
            );
    }
    
    public function testGetTraceCount ()
    {
        $err = new cPHP::Exception();
        
        $this->assertThat( $err->getTraceCount(0), $this->isType("int") );
        $this->assertThat( $err->getTraceCount(0), $this->greaterThan(0) );
    }
    
    public function testFault ()
    {
        $err = new cPHP::Exception();
        
        $this->assertFalse( $err->issetFault() );
        
        $this->assertSame( $err, $err->setFault(-1) );
        
        $this->assertTrue( $err->issetFault() );
        
        
        $this->assertThat( $err->getFaultOffset(), $this->isType("int") );
        
        $this->assertThat( $err->getFaultOffset(), $this->greaterThan( 0 ) );
        
        
        $this->assertSame( $err, $err->unsetFault() );
        
        $this->assertFalse( $err->issetFault() );
        
    }
    
}

?>