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
class classes_curry_call extends PHPUnit_Framework_TestCase
{

    // This method exists simply to test the calling of static methods
    static public function staticMethod ()
    {
        return "called";
    }

    public function testCallInternal ()
    {
        $callback = new cPHP::Curry::Call("trim");
        
        $this->assertEquals( "trimmed", $callback("  trimmed  ") );
        
    }
    
    public function testCallClosure ()
    {
        $callback = new cPHP::Curry::Call(function ( $value ) {
            return trim($value);
        });
        
        $this->assertEquals( "trimmed", $callback("  trimmed  ") );
    }
    
    public function testCallMethod ()
    {
        $hasMethod = $this->getMock('testCall', array('toCall'));
        
        $hasMethod
            ->expects( $this->once() )
            ->method('toCall')
            ->with( $this->equalTo('argument') );
        
        $callback = new cPHP::Curry::Call( array($hasMethod, "toCall") );
        
        $callback("argument");
    }
    
    public function testCallInvokable ()
    {
        $invokable = $this->getMock('Invokable', array('__invoke'));
        
        $invokable
            ->expects( $this->once() )
            ->method('__invoke')
            ->with( $this->equalTo('argument') );
            
        $callback = new cPHP::Curry::Call($invokable);
        
        $callback("argument");
        
    }
    
    public function testCallStatic ()
    {
        $callback = new cPHP::Curry::Call( array(__CLASS__, "staticMethod") );
        
        $this->assertEquals( "called", $callback("argument") );
    }
    
    public function testInstantiateException ()
    {
        $this->setExpectedException('::cPHP::Exception::Argument');
        $callback = new cPHP::Curry::Call( "ThisIsUnUncallableValue" );
    }
    
}

?>