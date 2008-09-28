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
class classes_validator_collection_all
{
    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite('commonPHP Validator All Class');
        $suite->addLib();
        $suite->addTestSuite( 'classes_validator_collection_all_tests' );
        return $suite;
    }
}

/**
 * unit tests
 */
class classes_validator_collection_all_tests extends PHPUnit_Framework_TestCase
{
    
    public function testNoValidators ()
    {
        $all = new ::cPHP::Validator::Collection::All;
        
        $result = $all->validate("example value");
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
        
        $all = new ::cPHP::Validator::Collection::All( $valid );
        $this->assertEquals( array($valid), $all->getValidators()->get() );
        
        try {
            $all->validate("example value");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( ::cPHP::Exception::Data $err ) {}
    }
    
    public function testValid ()
    {

        $valid1 = $this->getMock("cPHP::iface::Validator", array("validate"));
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( new ::cPHP::Validator::Result("example value") ) );
        
        $valid2 = $this->getMock("cPHP::iface::Validator", array("validate"));
        $valid2->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( new ::cPHP::Validator::Result("example value") ) );
        
        
        $all = new ::cPHP::Validator::Collection::All( $valid1, $valid2 );
        
        $result = $all->validate("example value");
        
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
        
        
        $all = new ::cPHP::Validator::Collection::All( $valid1 );
        
        $result = $all->validate("example value");
        
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
        
        
        $all = new ::cPHP::Validator::Collection::All( $valid1, $valid2 );
        
        $result = $all->validate("example value");
        
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
        
        
        $all = new ::cPHP::Validator::Collection::All( $valid1, $valid2 );
        
        $result = $all->validate("example value");
        
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("This is an Error"),
                $result->getErrors()->get()
            );

    }
    
}

?>