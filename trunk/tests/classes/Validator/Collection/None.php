<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_validator_collection_none extends PHPUnit_Framework_TestCase
{
    
    public function testNoValidators ()
    {
        $all = new ::cPHP::Validator::Collection::None;
        
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
        
        $none = new ::cPHP::Validator::Collection::None( $valid );
        $this->assertEquals( array($valid), $none->getValidators()->get() );
        
        try {
            $none->validate("example value");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( ::cPHP::Exception::Data $err ) {}
    }
    
    public function testValid ()
    {

        $valid1 = $this->getMock("cPHP::iface::Validator", array("validate"));
        $result1 = new ::cPHP::Validator::Result("example value");
        $result1->addError("Spoof Error");
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result1 ) );
        
        $valid2 = $this->getMock("cPHP::iface::Validator", array("validate"));
        $result2 = new ::cPHP::Validator::Result("example value");
        $result2->addError("Spoof Error");
        $valid2->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result2 ) );
        
        
        $none = new ::cPHP::Validator::Collection::None( $valid1, $valid2 );
        
        $result = $none->validate("example value");
        
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertTrue( $result->isValid() );
        
    }
    
    public function testOneInvalid ()
    {
        
        $result1 = new ::cPHP::Validator::Result("example value");
        $valid1 = $this->getMock("cPHP::iface::Validator", array("validate"));
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result1 ) );
        
        
        $none = new ::cPHP::Validator::Collection::None( $valid1 );
        
        $result = $none->validate("example value");
        
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Value is not valid"),
                $result->getErrors()->get()
            );

    }
    
    public function testFirstInvalid ()
    {
        
        $result1 = new ::cPHP::Validator::Result("example value");
        $valid1 = $this->getMock("cPHP::iface::Validator", array("validate"));
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result1 ) );
        
        $valid2 = $this->getMock("cPHP::iface::Validator", array("validate"));
        $valid2->expects( $this->never() )
            ->method( "validate" );
            
        
        $none = new ::cPHP::Validator::Collection::None( $valid1, $valid2 );
        
        $result = $none->validate("example value");
        
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Value is not valid"),
                $result->getErrors()->get()
            );

    }
    
    public function testSecondInvalid ()
    {
        
        $result1 = new ::cPHP::Validator::Result("example value");
        $result1->addError("This is an error");
        $valid1 = $this->getMock("cPHP::iface::Validator", array("validate"));
        $valid1->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result1 ) );
        
        $result2 = new ::cPHP::Validator::Result("example value");
        $valid2 = $this->getMock("cPHP::iface::Validator", array("validate"));
        $valid2->expects( $this->once() )
            ->method( "validate" )
            ->with( $this->equalTo("example value") )
            ->will( $this->returnValue( $result2 ) );
            
        
        $none = new ::cPHP::Validator::Collection::None( $valid1, $valid2 );
        
        $result = $none->validate("example value");
        
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Value is not valid"),
                $result->getErrors()->get()
            );

    }
    
}

?>