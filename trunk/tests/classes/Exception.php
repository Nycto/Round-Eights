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
        $suite = new cPHP_Base_TestSuite('commonPHP Exception Class');
        $suite->addLib();
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
        $this->assertSame( $err, $err->setFault(0) );
        $this->assertTrue( $err->issetFault() );
        
        $this->assertEquals( 0, $err->getFaultOffset() );
        
        // Now reset the fault and test shiftFault without any arguments
        $this->assertSame( $err, $err->shiftFault() );
        $this->assertEquals( 1, $err->getFaultOffset() );
        
        // Make sure getFault returns an array
        $this->assertThat( $err->getFault(), $this->isInstanceOf("cPHP::Ary") );
        
        // test unsetFault
        $this->assertSame( $err, $err->unsetFault() );
        $this->assertFalse( $err->issetFault() );
        
        
        // Test shift Fault when no fault is currently set
        $err->shiftFault();
        $this->assertEquals(0, $err->getFaultOffset());
        
    }
    
    public function testData ()
    {
        $err = new cPHP::Exception;
        
        $this->assertSame( $err, $err->addData("Data Label", 20) );
        $this->assertThat( $err->getData(), $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals( array("Data Label" => 20), $err->getData()->get() );
        $this->assertEquals( 20, $err->getDataValue("Data Label") );
        
    }
    
    public function testThrowing ()
    {
        $this->setExpectedException('cPHP::Exception');
        throw new cPHP::Exception;
    }
    
}

?>