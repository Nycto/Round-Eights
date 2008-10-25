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
class classes_validator_regex extends PHPUnit_Framework_TestCase
{
    
    public function testConstruct ()
    {
        try {
            new ::cPHP::Validator::RegEx("");
            $this->fail("An expected exception was not thrown");
        }
        catch ( cPHP::Exception::Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }
        
        try {
            new ::cPHP::Validator::RegEx("   ");
            $this->fail("An expected exception was not thrown");
        }
        catch ( cPHP::Exception::Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }
    }
    
    public function testInvalidRegex ()
    {
        $regex = new ::cPHP::Validator::RegEx("1234");
        
        try {
            $regex->validate( "test" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( PHPUnit_Framework_Error $err ) {
            $this->assertSame(
                    "preg_match(): Delimiter must not be alphanumeric or backslash",
                    $err->getMessage()
                );
        }
        
    }
    
    public function testInvalidNonStrings()
    {
        $validator = new ::cPHP::Validator::RegEx("/[a-z]/");
        
        $result = $validator->validate($this->getMock("NoSpaces"));
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must be a string"),
                $result->getErrors()->get()
            );
    }
    
    public function testTrue()
    {
        $validator = new ::cPHP::Validator::RegEx('/^1$/');
        $this->assertTrue( $validator->isValid(TRUE) );
        
        $validator = new ::cPHP::Validator::RegEx('/[a-z]/');
        $result = $validator->validate(TRUE);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must match the following regular expression: /[a-z]/"),
                $result->getErrors()->get()
            );
    }
    
    public function testFalse()
    {
        $validator = new ::cPHP::Validator::RegEx('/^$/');
        $this->assertTrue( $validator->isValid(FALSE) );
        
        $validator = new ::cPHP::Validator::RegEx('/[a-z]/');
        $result = $validator->validate(FALSE);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must match the following regular expression: /[a-z]/"),
                $result->getErrors()->get()
            );
    }
    
    public function testInteger()
    {
        $validator = new ::cPHP::Validator::RegEx('/^50$/');
        $this->assertTrue( $validator->isValid(50) );
        
        $validator = new ::cPHP::Validator::RegEx('/[a-z]/');
        $result = $validator->validate(50);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must match the following regular expression: /[a-z]/"),
                $result->getErrors()->get()
            );
    }
    
    public function testZero()
    {
        $validator = new ::cPHP::Validator::RegEx('/^0$/');
        $this->assertTrue( $validator->isValid(0) );
        
        $validator = new ::cPHP::Validator::RegEx('/[a-z]/');
        $result = $validator->validate(0);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must match the following regular expression: /[a-z]/"),
                $result->getErrors()->get()
            );
    }
    
    public function testNull()
    {
        $validator = new ::cPHP::Validator::RegEx('/^$/');
        $this->assertTrue( $validator->isValid(NULL) );
        
        $validator = new ::cPHP::Validator::RegEx('/[a-z]/');
        $result = $validator->validate(NULL);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must match the following regular expression: /[a-z]/"),
                $result->getErrors()->get()
            );
    }
    
    public function testFloat()
    {
        $validator = new ::cPHP::Validator::RegEx('/^1\.1$/');
        $this->assertTrue( $validator->isValid(1.1) );
        
        $validator = new ::cPHP::Validator::RegEx('/[a-z]/');
        $result = $validator->validate(1.1);
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must match the following regular expression: /[a-z]/"),
                $result->getErrors()->get()
            );
    }
    
    public function _testString()
    {
        $validator = new ::cPHP::Validator::RegEx('/\.php$/');
        $this->assertTrue( $validator->isValid("file.php") );
        
        $validator = new ::cPHP::Validator::RegEx('/[0-9]/');
        $result = $validator->validate("This is a string");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Must match the following regular expression: /[0-9]/"),
                $result->getErrors()->get()
            );
    }
    
}

?>