<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */


require_once rtrim( dirname( __FILE__ ), "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_validator_result extends PHPUnit_Framework_TestCase
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