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
class classes_validator_maxlength extends PHPUnit_Framework_TestCase
{
    
    public function testTrue()
    {
        $validator = new ::cPHP::Validator::MaxLength(10);
        $this->assertTrue( $validator->isValid(TRUE) );
        
        $validator = new ::cPHP::Validator::MaxLength(1);
        $this->assertTrue( $validator->isValid(TRUE) );
        
        $validator = new ::cPHP::Validator::MaxLength(0);
        $result = $validator->validate(TRUE);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be longer than 0 characters"),
                $result->getErrors()->get()
            );
    }
    
    public function testFalse()
    {
        $validator = new ::cPHP::Validator::MaxLength(10);
        $this->assertTrue( $validator->isValid(FALSE) );
        
        $validator = new ::cPHP::Validator::MaxLength(1);
        $this->assertTrue( $validator->isValid(FALSE) );
        
        // When converted to a string, FALSE becomes ""
        $validator = new ::cPHP::Validator::MaxLength(0);
        $this->assertTrue( $validator->isValid(FALSE) );
    }
    
    public function testInteger()
    {
        $validator = new ::cPHP::Validator::MaxLength(10);
        $this->assertTrue( $validator->isValid(50) );
        
        $validator = new ::cPHP::Validator::MaxLength(2);
        $this->assertTrue( $validator->isValid(50) );
        
        $validator = new ::cPHP::Validator::MaxLength(1);
        $result = $validator->validate(50);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be longer than 1 character"),
                $result->getErrors()->get()
            );
    }
    
    public function testZero()
    {
        $validator = new ::cPHP::Validator::MaxLength(10);
        $this->assertTrue( $validator->isValid(0) );
        
        $validator = new ::cPHP::Validator::MaxLength(1);
        $this->assertTrue( $validator->isValid(0) );
        
        $validator = new ::cPHP::Validator::MaxLength(0);
        $result = $validator->validate(0);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be longer than 0 characters"),
                $result->getErrors()->get()
            );
    }
    
    public function testNull()
    {
        $validator = new ::cPHP::Validator::MaxLength(10);
        $this->assertTrue( $validator->isValid(NULL) );
        
        $validator = new ::cPHP::Validator::MaxLength(0);
        $this->assertTrue( $validator->isValid(NULL) );
    }
    
    public function testFloat()
    {
        $validator = new ::cPHP::Validator::MaxLength(10);
        $this->assertTrue( $validator->isValid(1.1) );
        
        $validator = new ::cPHP::Validator::MaxLength(3);
        $this->assertTrue( $validator->isValid(1.1) );
        
        $validator = new ::cPHP::Validator::MaxLength(2);
        $result = $validator->validate(1.1);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be longer than 2 characters"),
                $result->getErrors()->get()
            );
    }
    
    public function testString()
    {
        $validator = new ::cPHP::Validator::MaxLength(7);
        $this->assertTrue( $validator->isValid("under") );
        
        $this->assertTrue( $validator->isValid("just at") );
        
        $result = $validator->validate("too long for it");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be longer than 7 characters"),
                $result->getErrors()->get()
            );
    }
    
    public function testInvalidValues()
    {
        $validator = new ::cPHP::Validator::MaxLength(10);
        
        $result = $validator->validate($this->getMock("NoSpaces"));
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must be a string"),
                $result->getErrors()->get()
            );
    }
    
}

?>