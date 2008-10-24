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
class classes_validator_notempty extends PHPUnit_Framework_TestCase
{
    
    public function testInvalid_noFlags ()
    {
        
        $validator = new ::cPHP::Validator::NotEmpty;
        
        $result = $validator->validate("");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be empty"),
                $result->getErrors()->get()
            );
        
        $result = $validator->validate("    ");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be empty"),
                $result->getErrors()->get()
            );
        
        $result = $validator->validate(NULL);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be empty"),
                $result->getErrors()->get()
            );
        
        $result = $validator->validate(0);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be empty"),
                $result->getErrors()->get()
            );
        
        $result = $validator->validate(FALSE);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be empty"),
                $result->getErrors()->get()
            );
        
        $result = $validator->validate(array());
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be empty"),
                $result->getErrors()->get()
            );
        
    }
    
    public function testInvalid_flags ()
    {
        
        $validator = new ::cPHP::Validator::NotEmpty( ALLOW_BLANK );
        $this->assertTrue( $validator->isValid("") );
        
        $result = $validator->validate("    ");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be empty"),
                $result->getErrors()->get()
            );
        
        
        $validator = new ::cPHP::Validator::NotEmpty( ALLOW_NULL );
        $this->assertTrue( $validator->isValid(NULL) );
        
        $result = $validator->validate(0);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be empty"),
                $result->getErrors()->get()
            );
        
        
        $validator = new ::cPHP::Validator::NotEmpty( ALLOW_FALSE );
        $this->assertTrue( $validator->isValid(FALSE) );
        
        $result = $validator->validate(array());
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be empty"),
                $result->getErrors()->get()
            );
        
    }
    
    public function testValid ()
    {
        $validator = new ::cPHP::Validator::NotEmpty;
        
        $this->assertTrue( $validator->isValid("0") );
        $this->assertTrue( $validator->isValid("this is not empty") );
        $this->assertTrue( $validator->isValid( $this->getMock("NotEmpty") ) );
        $this->assertTrue( $validator->isValid( TRUE ) );
        $this->assertTrue( $validator->isValid( 20 ) );
    }
    
}

?>