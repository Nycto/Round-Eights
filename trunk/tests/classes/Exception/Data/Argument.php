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
class classes_exception_data_argument
{
    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite('commonPHP Argument Exception Class');
        $suite->addLib();
        $suite->addTestSuite( 'classes_exception_data_argument_tests' );
        return $suite;
    }
}

/**
 * unit tests
 */
class classes_exception_data_argument_tests extends PHPUnit_Framework_TestCase
{
    
    // Returns an thrown exception
    public function getThrown ()
    {
        
        $throw = function ( $arg1, $arg2 ) {
            throw new ::cPHP::Exception::Data::Argument(0, "test", "From our sponsors", 505, 0);
        };
        
        try {
            $throw("arg value", "other arg");
        }
        catch ( ::cPHP::Exception::Data::Argument $err ) {
            return $err;
        }
        
    }
    
    public function testConstruct ()
    {
        $err = $this->getThrown();
        
        $this->assertEquals( 0, $err->getArgOffset() );
        $this->assertEquals( "From our sponsors", $err->getMessage() );
        $this->assertEquals( 505, $err->getCode() );
        
        $this->assertThat( $err->getData(), $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals( array("Arg Label" => "test"), $err->getData()->get() );
        
        $this->assertEquals( 0, $err->getFaultOffset() );
        
    }
    
}