<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once 'PHPUnit/Framework.php';
require_once rtrim( dirname( __FILE__ ), "/" ) ."/../../../general.php";

/**
 * test suite
 */
class classes_validator_collection_any
{
    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite('commonPHP Validator Any Class');
        $suite->addLib();
        $suite->addTestSuite( 'classes_validator_collection_any_tests' );
        return $suite;
    }
}

/**
 * unit tests
 */
class classes_validator_collection_any_tests extends PHPUnit_Framework_TestCase
{
    
    public function testNoValidators ()
    {
        $any = new ::cPHP::Validator::Collection::Any;
        
        $result = $any->validate("example value");
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertTrue( $result->isValid() );
        
    }
    
    public function testInvalidResult ()
    {
        $valid = $this->getMock("cPHP::iface::Validator", array("validate"));
        $valid->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue("This is an invalid result") );
        
        $any = new ::cPHP::Validator::Collection::Any( $valid );
        $this->assertEquals( array($valid), $any->getValidators()->get() );
        
        try {
            $any->validate("example value");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( ::cPHP::Exception::Data $err ) {}
    }
    
    public function testFirstValid ()
    {

        $valid1 = $this->getMock("cPHP::iface::Validator", array("validate"));
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( new ::cPHP::Validator::Result("example value") ) );
        
        // This should never be called because the first validator should short circuit things
        $valid2 = $this->getMock("cPHP::iface::Validator", array("validate"));
        $valid2->expects( $this->never() )
            ->method( "validate" );
        
        
        $any = new ::cPHP::Validator::Collection::Any( $valid1, $valid2 );
        
        $result = $any->validate("example value");
        
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertTrue( $result->isValid() );
        
    }
    
    public function testSecondValid ()
    {
        $result1 = new ::cPHP::Validator::Result("example value");
        $result1->addError("This is an Error");
        
        $valid1 = $this->getMock("cPHP::iface::Validator", array("validate"));
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result1 ) );
        
        $valid2 = $this->getMock("cPHP::iface::Validator", array("validate"));
        $valid2->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( new ::cPHP::Validator::Result("example value") ) );
        
        
        $any = new ::cPHP::Validator::Collection::Any( $valid1, $valid2 );
        
        $result = $any->validate("example value");
        
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertTrue( $result->isValid() );
        
    }
    
    public function testOneInvalid ()
    {
        
        $result1 = new ::cPHP::Validator::Result("example value");
        $result1->addError("This is an Error");
        
        $valid1 = $this->getMock("cPHP::iface::Validator", array("validate"));
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result1 ) );
        
        
        $any = new ::cPHP::Validator::Collection::Any( $valid1 );
        
        $result = $any->validate("example value");
        
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("This is an Error"),
                $result->getErrors()->get()
            );

    }
    
    public function testMultipleInvalid ()
    {
        
        $result1 = new ::cPHP::Validator::Result("example value");
        $result1->addError("This is an Error");
        
        $valid1 = $this->getMock("cPHP::iface::Validator", array("validate"));
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result1 ) );
            
        
        $result2 = new ::cPHP::Validator::Result("example value");
        $result2->addError("This is another Error");
        
        $valid2 = $this->getMock("cPHP::iface::Validator", array("validate"));
        $valid2->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result2 ) );
        
        
        $any = new ::cPHP::Validator::Collection::Any( $valid1, $valid2 );
        
        $result = $any->validate("example value");
        
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("This is an Error", "This is another Error"),
                $result->getErrors()->get()
            );

    }
    
    public function testDuplicateErrors ()
    {
        
        $result1 = new ::cPHP::Validator::Result("example value");
        $result1->addError("This is an Error");
        
        $valid1 = $this->getMock("cPHP::iface::Validator", array("validate"));
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result1 ) );
            
        
        $result2 = new ::cPHP::Validator::Result("example value");
        $result2->addError("This is an Error");
        
        $valid2 = $this->getMock("cPHP::iface::Validator", array("validate"));
        $valid2->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result2 ) );
        
        
        $any = new ::cPHP::Validator::Collection::Any( $valid1, $valid2 );
        
        $result = $any->validate("example value");
        
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("This is an Error"),
                $result->getErrors()->get()
            );

    }
    
}

?>