<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once 'PHPUnit/Framework.php';
require_once rtrim( dirname( __FILE__ ), "/" ) ."/../../general.php";

/**
 * test suite
 */
class classes_validator_collection
{
    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite('commonPHP Validator Collection Class');
        $suite->addLib();
        $suite->addTestSuite( 'classes_validator_collection_tests' );
        return $suite;
    }
}

/**
 * unit tests
 */
class classes_validator_collection_tests extends PHPUnit_Framework_TestCase
{
    
    public function testAddObject ()
    {
        $collection = $this->getMock("cPHP::Validator::Collection", array("process"));
        
        $valid = $this->getMock("cPHP::iface::Validator", array("validate"));
        
        $this->assertSame( $collection, $collection->add($valid) );
        
        $list = $collection->getValidators();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals(array( $valid ), $list->get());
    }
    
    public function testAddObjectError ()
    {
        $this->setExpectedException("cPHP::Exception::Data::Argument");
        
        $collection = $this->getMock("cPHP::Validator::Collection", array("process"));
        $valid = $this->getMock("stub_random_class");
        
        $collection->add($valid);
    }
    
    public function testAddInterfaceString ()
    {
        $collection = $this->getMock("cPHP::Validator::Collection", array("process"));
        
        $valid = get_class( $this->getMock("cPHP::iface::Validator", array("validate")) );
        
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
        $this->setExpectedException("cPHP::Exception::Data::Argument");
        
        $collection = $this->getMock("cPHP::Validator::Collection", array("process"));
        $valid = get_class( $this->getMock("stub_random_class") );
        
        $collection->add($valid);
    }
    
    public function testAddMany ()
    {
        $collection = $this->getMock( "cPHP::Validator::Collection", array("process") );
        
        $valid = $this->getMock("cPHP::iface::Validator", array("validate"));
        $valid2 = $this->getMock("cPHP::iface::Validator", array("validate"));
        
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
        
        $valid = $this->getMock("cPHP::iface::Validator", array("validate"));
        $valid2 = $this->getMock("cPHP::iface::Validator", array("validate"));
        
        $collection = $this->getMock(
                "cPHP::Validator::Collection",
                array("process"),
                array( $valid, "Not a validator", $valid2 )
            );
        
        
        $list = $collection->getValidators();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals(array( $valid, $valid2 ), $list->get());
        
    }
    
    public function testCreate ()
    {
        
        $valid = $this->getMock("cPHP::iface::Validator", array("validate"));
        $valid2 = $this->getMock("cPHP::iface::Validator", array("validate"));
        
        $class = get_class( $this->getMock("cPHP::Validator::Collection", array("process")) );
        
        $new = $class::create( $valid, $valid2 );
        $this->assertThat( $new, $this->isInstanceOf($class) );
        $this->assertEquals(
                array( $valid, $valid2 ),
                $new->getValidators()->get()
            );
    }
    
}

?>