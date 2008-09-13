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
class classes_validator_result
{
    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite('commonPHP Validator Results Class');
        $suite->addLib();
        $suite->addTestSuite( 'classes_validator_result_tests' );
        return $suite;
    }
}

/**
 * unit tests
 */
class classes_validator_result_tests extends PHPUnit_Framework_TestCase
{
    
    public function testConstruct ()
    {
        $result = new cPHP::Validator::Result("Wakka");
        
        $this->assertEquals("Wakka", $result->getValue());
    }

    public function testIsValid ()
    {
        $result = new cPHP::Validator::Result("Wakka");
        
        $this->assertTrue( $result->isValid() );
        
        $result->addError("Test Error");
        
        $this->assertFalse( $result->isValid() );
        
        $result->clearErrors();
        
        $this->assertTrue( $result->isValid() );
    }
    
}

?>