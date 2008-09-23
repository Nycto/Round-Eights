<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once 'PHPUnit/Framework.php';
require_once rtrim( dirname( __FILE__ ), "/" ) ."/../general.php";

/**
 * test suite
 */
class classes_tag
{
    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite('commonPHP Tag Class');
        $suite->addLib();
        $suite->addTestSuite( 'classes_tag_tests' );
        return $suite;
    }
}

/**
 * unit tests
 */
class classes_tag_tests extends PHPUnit_Framework_TestCase
{
    
    public function testConstruct ()
    {
        $tag = new cPHP::Tag("A");
        
        $this->assertSame( "a", $tag->getTag() );
        $this->assertNull($tag->getContent());
        
        try {
            $tag = new cPHP::Tag("  ");
            $this->fail("An expected exception has not been thrown");
        }
        catch ( cPHP::Exception::Data::Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }
        
        
        $tag = new cPHP::Tag("a", "a snip of content");
        $this->assertSame("a", $tag->getTag());
        $this->assertSame("a snip of content", $tag->getContent());
    }
    
    public function testSetTag ()
    {
        $tag = new cPHP::Tag("a");
        
        $this->assertSame( $tag, $tag->setTag("div") );
        $this->assertSame( "div", $tag->getTag() );
        
        $this->assertSame( $tag, $tag->setTag("  Sp !@#$ a n") );
        $this->assertSame( "span", $tag->getTag() );
        
        try {
            $tag->setTag("");
            $this->fail("An expected exception has not been thrown");
        }
        catch ( cPHP::Exception::Data::Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }
    }
    
    public function testSetContent ()
    {
        $tag = new cPHP::Tag("a");
        
        $this->assertNull( $tag->getContent() );
        
        $this->assertSame( $tag, $tag->setContent("This is a string") );
        $this->assertEquals( "This is a string", $tag->getContent() );
        
        $this->assertSame( $tag, $tag->setContent("0") );
        $this->assertEquals( "0", $tag->getContent() );
        
        $this->assertSame( $tag, $tag->setContent("") );
        $this->assertNull( $tag->getContent() );
        
        $this->assertSame( $tag, $tag->setContent(FALSE) );
        $this->assertNull( $tag->getContent() );
        
        $this->assertSame( $tag, $tag->setContent(TRUE) );
        $this->assertSame( "1", $tag->getContent() );
        
        $this->assertSame( $tag, $tag->setContent(1) );
        $this->assertSame( "1", $tag->getContent() );
        
        $mock = $this->getMock("stub_getContent", array("__toString"));
        $mock->expects( $this->once() )
            ->method("__toString")
            ->will( $this->returnValue("stringified"));
        
        $this->assertSame( $tag, $tag->setContent($mock) );
        $this->assertSame( "stringified", $tag->getContent() );
    }
    
}

?>