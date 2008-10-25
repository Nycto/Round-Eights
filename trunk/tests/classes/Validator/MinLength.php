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
class classes_validator_minlength extends PHPUnit_Framework_TestCase
{
    
    public function testTrue()
    {
        $validator = new ::cPHP::Validator::MinLength(0);
        $this->assertTrue( $validator->isValid(TRUE) );
        
        $validator = new ::cPHP::Validator::MinLength(1);
        $this->assertTrue( $validator->isValid(TRUE) );
        
        $validator = new ::cPHP::Validator::MinLength(2);
        $result = $validator->validate(TRUE);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be shorter than 2 characters"),
                $result->getErrors()->get()
            );
    }
    
    public function testFalse()
    {
        $validator = new ::cPHP::Validator::MinLength(0);
        $this->assertTrue( $validator->isValid(FALSE) );
        
        // When converted to a string, FALSE becomes ""
        $validator = new ::cPHP::Validator::MinLength(1);
        $result = $validator->validate(FALSE);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be shorter than 1 character"),
                $result->getErrors()->get()
            );
    }
    
    public function testInteger()
    {
        $validator = new ::cPHP::Validator::MinLength(1);
        $this->assertTrue( $validator->isValid(50) );
        
        $validator = new ::cPHP::Validator::MinLength(2);
        $this->assertTrue( $validator->isValid(50) );
        
        $validator = new ::cPHP::Validator::MinLength(3);
        $result = $validator->validate(50);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be shorter than 3 characters"),
                $result->getErrors()->get()
            );
    }
    
    public function testZero()
    {
        $validator = new ::cPHP::Validator::MinLength(0);
        $this->assertTrue( $validator->isValid(0) );
        
        $validator = new ::cPHP::Validator::MinLength(1);
        $this->assertTrue( $validator->isValid(0) );
        
        $validator = new ::cPHP::Validator::MinLength(2);
        $result = $validator->validate(0);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be shorter than 2 characters"),
                $result->getErrors()->get()
            );
    }
    
    public function testNull()
    {
        $validator = new ::cPHP::Validator::MinLength(0);
        $this->assertTrue( $validator->isValid(NULL) );
        
        $validator = new ::cPHP::Validator::MinLength(1);
        $result = $validator->validate(NULL);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be shorter than 1 character"),
                $result->getErrors()->get()
            );
    }
    
    public function testFloat()
    {
        $validator = new ::cPHP::Validator::MinLength(2);
        $this->assertTrue( $validator->isValid(1.1) );
        
        $validator = new ::cPHP::Validator::MinLength(3);
        $this->assertTrue( $validator->isValid(1.1) );
        
        $validator = new ::cPHP::Validator::MinLength(4);
        $result = $validator->validate(1.1);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be shorter than 4 characters"),
                $result->getErrors()->get()
            );
    }
    
    public function testString()
    {
        $validator = new ::cPHP::Validator::MinLength(7);
        $this->assertTrue( $validator->isValid("longer than limit") );
        
        $this->assertTrue( $validator->isValid("just at") );
        
        $result = $validator->validate("short");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must not be shorter than 7 characters"),
                $result->getErrors()->get()
            );
    }
    
    public function testInvalidValues()
    {
        $validator = new ::cPHP::Validator::MinLength(10);
        
        $result = $validator->validate($this->getMock("NoSpaces"));
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must be a string"),
                $result->getErrors()->get()
            );
    }
    
}

?>