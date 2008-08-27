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
        
        
        // test whether setFault and issetFault work
        $this->assertFalse( $err->issetFault() );
        $this->assertSame( $err, $err->setFault(-1) );
        $this->assertTrue( $err->issetFault() );
        
        
        // Ensure that getFaultOffset returns an integer
        $fault = $err->getFaultOffset();
        $this->assertThat( $fault, $this->isType("int") );
        $this->assertThat( $fault, $this->greaterThan( 0 ) );
        
        
        // Test that shiftFault works
        $this->assertSame( $err, $err->shiftFault( -1 ) );
        $this->assertEquals( $fault - 1, $err->getFaultOffset() );
        
        
        // Now reset the fault and test shiftFault without any arguments
        $this->assertSame( $err, $err->setFault( $fault ) );
        $this->assertSame( $err, $err->shiftFault() );
        $this->assertEquals( $fault - 1, $err->getFaultOffset() );
        
        
        // Make sure getFault returns an array
        $this->assertThat( $err->getFault(), $this->isInstanceOf("cPHP::Ary") );
        
        
        // test unsetFault
        $this->assertSame( $err, $err->unsetFault() );
        $this->assertFalse( $err->issetFault() );
        
        
        // Test shift Fault when no fault is currently set
        $err->shiftFault();
        $this->assertEquals(
                count( $err->getTrace() ) - 2,
                $err->getFaultOffset()
            );
        
    }
    
    public function testGetTraceOffsetString ()
    {
        $err = new cPHP::Exception();
        
        
    }
    
}

?>