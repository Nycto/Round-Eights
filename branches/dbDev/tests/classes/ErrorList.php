<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * unit tests
 */
class classes_errorlist extends PHPUnit_Framework_TestCase
{
    
    public function testAddError ()
    {
        $result = new cPHP::ErrorList;
        
        $this->assertSame( $result, $result->addError("This is an error message") );
        
        
        $errors = $result->getErrors();
        
        $this->assertThat( $errors, $this->isInstanceOf("cPHP::Ary") );
        
        $this->assertEquals(
                array("This is an error message"),
                $result->getErrors()->get()
            );
        
        $this->assertSame( $result, $result->addError("This is another error message") );
        
        $this->assertEquals(
                array("This is an error message", "This is another error message"),
                $result->getErrors()->get()
            );
        
        
        try {
            $result->addError("");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( ::cPHP::Exception::Argument $err ) {}
    }
    
    public function testAddErrors ()
    {
        $result = new cPHP::ErrorList;
        
        $this->assertSame( $result, $result->addErrors("Error Message") );
        $this->assertEquals(
                array("Error Message"),
                $result->getErrors()->get()
            );
        
        $result->clearErrors();
        
        
        $this->assertSame(
                $result,
                $result->addErrors( array(("Error Message"), "more"), "Another", "", array("more", "then some") )
            );
        $this->assertEquals(
                array("Error Message", "more", "Another", "then some"),
                $result->getErrors()->get()
            );
    }
    
    public function testAddDuplicateError ()
    {
        $result = new cPHP::ErrorList;
        
        $this->assertSame( $result, $result->addError("This is an error message") );
        $this->assertSame( $result, $result->addError("This is an error message") );
        
        $this->assertEquals(
                array("This is an error message"),
                $result->getErrors()->get()
            );
    }
    
    public function testClearErrors ()
    {
        $result = new cPHP::ErrorList;
        
        $result->addError("This is an error message");
        
        $this->assertEquals(
                array("This is an error message"),
                $result->getErrors()->get()
            );
        
        $this->assertSame( $result, $result->clearErrors() );
        
        $this->assertEquals( array(), $result->getErrors()->get() );
    }
    
    public function testSetErrors ()
    {
        $result = new cPHP::ErrorList;
        
        $result->addError("This is an error message");
        
        $this->assertEquals(
                array("This is an error message"),
                $result->getErrors()->get()
            );
        
        $this->assertSame( $result, $result->setError("This is a new error") );
        
        $this->assertEquals(
                array("This is a new error"),
                $result->getErrors()->get()
            );
    }
    
    public function testHasErrors ()
    {
        $result = new cPHP::ErrorList;
        
        $this->assertFalse( $result->hasErrors() );
        
        $result->addError("Test Error");
        
        $this->assertTrue( $result->hasErrors() );
        
        $result->clearErrors();
        
        $this->assertFalse( $result->hasErrors() );
    }
    
    public function testGetFirstError ()
    {
        $result = new cPHP::ErrorList;
        
        $this->assertNull( $result->getFirstError() );
        
        $result->addError("Test Error");
        
        $this->assertEquals("Test Error", $result->getFirstError());
        
        $result->addError("Another Error");
        
        $this->assertEquals("Test Error", $result->getFirstError());
    }
    
}

?>