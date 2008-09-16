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
class classes_validator
{
    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite('commonPHP Validator Class');
        $suite->addLib();
        $suite->addTestSuite( 'classes_validator_tests' );
        return $suite;
    }
}

/**
 * unit tests
 */
class classes_validator_tests extends PHPUnit_Framework_TestCase
{
    
    public function getMockValidator ( $return )
    {
        $mock = $this->getMock("cPHP::Validator", array("process"));
        $mock->expects( $this->once() )
            ->method( "process" )
            ->with( $this->equalTo("To Validate") )
            ->will( $this->returnValue( $return ) );
            
        return $mock;
    }
    
    public function testNoErrors ()
    {
        $mock = $this->getMockValidator ( NULL );
        
        $result = $mock->validate("To Validate");
        
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertTrue( $result->isValid() );
    }
    
    public function testStringError ()
    {
        $mock = $this->getMockValidator ("This is an Error");
        
        $result = $mock->validate("To Validate");
        
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals( array("This is an Error"), $result->getErrors()->get() );
    }
    
    public function testArrayError ()
    {
        $mock = $this->getMockValidator( array("First Error", "Second Error") );
        
        $result = $mock->validate("To Validate");
        
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals( array("First Error", "Second Error"), $result->getErrors()->get() );
        
        
        
        $mock = $this->getMockValidator( array( array("First Error"), "", "Second Error") );
        
        $result = $mock->validate("To Validate");
        
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals( array("First Error", "Second Error"), $result->getErrors()->get() );
    }
    
    public function testEmptyArrayError ()
    {
        $mock = $this->getMockValidator( array() );
        
        $result = $mock->validate("To Validate");
        
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertTrue( $result->isValid() );
        
        
        
        $mock = $this->getMockValidator( array( "", FALSE, "  " ) );
        
        $result = $mock->validate("To Validate");
        
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertTrue( $result->isValid() );
    }
    
    public function testResultError ()
    {
        
        $return = new ::cPHP::Validator::Result("To Validate");
        $return->addErrors("First Error", "Second Error");
        $mock = $this->getMockValidator( $return );
        
        $result = $mock->validate("To Validate");
        
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals( array("First Error", "Second Error"), $result->getErrors()->get() );
        
    }
    
    public function testEmptyResultError ()
    {
        
        $return = new ::cPHP::Validator::Result("To Validate");
        $mock = $this->getMockValidator( $return );
        
        $result = $mock->validate("To Validate");
        
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertTrue( $result->isValid() );
        
    }
    
    public function testCustomErrors ()
    {
    
        $mock = $this->getMockValidator( "Default Error" );
        $mock->addError("Custom Error Message");
        
        $result = $mock->validate("To Validate");
        
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals( array("Custom Error Message"), $result->getErrors()->get() );
        
    }
    
    public function testIsValid ()
    {
        $passes = $this->getMockValidator( NULL );
        $this->assertTrue( $passes->isValid("To Validate") );
        
        $fails = $this->getMockValidator( "Default Error" );
        $this->assertFalse( $fails->isValid("To Validate") );
    }
}

?>