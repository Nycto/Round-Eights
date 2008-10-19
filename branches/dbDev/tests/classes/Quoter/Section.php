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
class classes_quoter_section extends PHPUnit_Framework_TestCase
{
    
    public function testSetContent ()
    {
        $section = $this->getMock("cPHP::Quoter::Section", array("isQuoted", "__toString"), array(0, null));
        
        $this->assertNull( $section->getContent() );
        
        $this->assertSame( $section, $section->setContent("new string") );
        
        $this->assertEquals( "new string", $section->getContent() );
    }
    
    public function testClearContent ()
    {
        $section = $this->getMock("cPHP::Quoter::Section", array("isQuoted", "__toString"), array(0, null));
        
        $this->assertNull( $section->getContent() );
        
        $section->setContent("new string");
        
        $this->assertEquals( "new string", $section->getContent() );
        
        $this->assertSame( $section, $section->clearContent() );
        
        $this->assertNull( $section->getContent() );
    }
    
    public function testContentExists ()
    {
        $section = $this->getMock("cPHP::Quoter::Section", array("isQuoted", "__toString"), array(0, null));
        
        $this->assertFalse( $section->contentExists() );
        
        $section->setContent("new string");
        
        $this->assertTrue( $section->contentExists() );
        
        $section->clearContent();
        
        $this->assertFalse( $section->contentExists() );
        
        $section->setContent("");
        
        $this->assertTrue( $section->contentExists() );
    }
    
    public function testIsEmpty ()
    {
        $section = $this->getMock("cPHP::Quoter::Section", array("isQuoted", "__toString"), array(0, null));
        
        $this->assertTrue( $section->isEmpty() );
        
        $section->setContent("");
        $this->assertTrue( $section->isEmpty() );
        
        $section->setContent("  ");
        $this->assertTrue( $section->isEmpty() );
        $this->assertFalse( $section->isEmpty( ALLOW_SPACES ) );
        
        $section->setContent("Some piece of content");
        $this->assertFalse( $section->isEmpty() );
        
        $section->clearContent();
        $this->assertTrue( $section->isEmpty() );
    }
    
    public function testConstruct ()
    {
        $section = $this->getMock("cPHP::Quoter::Section", array("isQuoted", "__toString"), array(10, "data"));
        $this->assertSame( 10, $section->getOffset() );
        $this->assertSame( "data", $section->getContent() );
        
        try {
            $section = $this->getMock("cPHP::Quoter::Section", array("isQuoted", "__toString"), array(-5, "data"));
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::Argument $err ) {
            $this->assertSame( "Must not be less than zero", $err->getMessage() );
        }
    }
    
}

?>