<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */


require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_validator_collection extends PHPUnit_Framework_TestCase
{
    
    public function testAddObject ()
    {
        $collection = $this->getMock("cPHP::Validator::Collection", array("process"));
        
        $valid = $this->getMock("cPHP::iface::Validator", array("validate", "isValid"));
        
        $this->assertSame( $collection, $collection->add($valid) );
        
        $list = $collection->getValidators();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals(array( $valid ), $list->get());
    }
    
    public function testAddObjectError ()
    {
        $this->setExpectedException("cPHP::Exception::Argument");
        
        $collection = $this->getMock("cPHP::Validator::Collection", array("process"));
        $valid = $this->getMock("stub_random_class");
        
        $collection->add($valid);
    }
    
    public function testAddInterfaceString ()
    {
        $collection = $this->getMock("cPHP::Validator::Collection", array("process"));
        
        $valid = get_class( $this->getMock("cPHP::iface::Validator", array("validate", "isValid")) );
        
        $this->assertSame( $collection, $collection->add($valid) );
        
        $list = $collection->getValidators();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $this->assertThat( $list->offsetGet(0), $this->isInstanceOf( $valid ) );
    }
    
    public function testAddClassString ()
    {
        $collection = $this->getMock("cPHP::Validator::Collection", array("process"));
        
        $valid = get_class( $collection );
        
        $this->assertSame( $collection, $collection->add($valid) );
        
        $list = $collection->getValidators();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $this->assertThat( $list->offsetGet(0), $this->isInstanceOf( $valid ) );
    }
    
    public function testAddStringError ()
    {
        $this->setExpectedException("cPHP::Exception::Argument");
        
        $collection = $this->getMock("cPHP::Validator::Collection", array("process"));
        $valid = get_class( $this->getMock("stub_random_class") );
        
        $collection->add($valid);
    }
    
    public function testAddMany ()
    {
        $collection = $this->getMock( "cPHP::Validator::Collection", array("process") );
        
        $valid = $this->getMock("cPHP::iface::Validator", array("validate", "isValid"));
        $valid2 = $this->getMock("cPHP::iface::Validator", array("validate", "isValid"));
        
        $this->assertSame(
                $collection,
                $collection->addMany( array( $valid, "Non validator" ), array(), $valid2 )
            );
        
        $list = $collection->getValidators();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals(array( $valid, $valid2 ), $list->get());
        
    }
    
    public function testConstruct ()
    {
        
        $valid = $this->getMock("cPHP::iface::Validator", array("validate", "isValid"));
        $valid2 = $this->getMock("cPHP::iface::Validator", array("validate", "isValid"));
        
        $collection = $this->getMock(
                "cPHP::Validator::Collection",
                array("process"),
                array( $valid, "Not a validator", $valid2 )
            );
        
        
        $list = $collection->getValidators();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals(array( $valid, $valid2 ), $list->get());
        
    }
    
    public function testCallStatic ()
    {
        $valid = $this->getMock("cPHP::iface::Validator", array("validate", "isValid"));
        $valid2 = $this->getMock("cPHP::iface::Validator", array("validate", "isValid"));
        
        $validator = cPHP::Validator::Collection::All();
        $this->assertThat( $validator, $this->isInstanceOf("cPHP::Validator::Collection::All") );
        
        
        $validator = cPHP::Validator::Collection::All( $valid );
        $this->assertThat( $validator, $this->isInstanceOf("cPHP::Validator::Collection::All") );
        
        $list = $validator->getValidators();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals(array( $valid ), $list->get());
        
        
        $validator = cPHP::Validator::Collection::Any( $valid, $valid2 );
        $this->assertThat( $validator, $this->isInstanceOf("cPHP::Validator::Collection::Any") );
        
        $list = $validator->getValidators();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals(array( $valid, $valid2 ), $list->get());
    }
    
}

?>