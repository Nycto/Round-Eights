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
        
        $this->assertSame( $tag, $tag->setContent("   ") );
        $this->assertEquals( "   ", $tag->getContent() );
        
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
    
    public function testAppendContent ()
    {
        $tag = new cPHP::Tag("a");
        
        $this->assertNull( $tag->getContent() );
        
        $this->assertSame( $tag, $tag->appendContent("string") );
        $this->assertEquals( "string", $tag->getContent() );
        
        $this->assertSame( $tag, $tag->appendContent("0") );
        $this->assertEquals( "string0", $tag->getContent() );
        
        $this->assertSame( $tag, $tag->appendContent("") );
        $this->assertEquals( "string0", $tag->getContent() );
        
        $this->assertSame( $tag, $tag->appendContent("  ") );
        $this->assertEquals( "string0  ", $tag->getContent() );
        
        $this->assertSame( $tag, $tag->appendContent(FALSE) );
        $this->assertEquals( "string0  ", $tag->getContent() );
        
        $this->assertSame( $tag, $tag->appendContent(TRUE) );
        $this->assertEquals( "string0  1", $tag->getContent() );
        
        $this->assertSame( $tag, $tag->appendContent(1) );
        $this->assertEquals( "string0  11", $tag->getContent() );
        
        $mock = $this->getMock("stub_getContent", array("__toString"));
        $mock->expects( $this->once() )
            ->method("__toString")
            ->will( $this->returnValue("stringified"));
        
        $this->assertSame( $tag, $tag->appendContent($mock) );
        $this->assertEquals( "string0  11stringified", $tag->getContent() );
    }
    
    public function testClearContent ()
    {
        $tag = new cPHP::Tag("a");
        
        $this->assertNull( $tag->getContent() );
        
        $tag->setContent("This is a string") ;
        $this->assertEquals( "This is a string", $tag->getContent() );
        
        $this->assertSame( $tag, $tag->clearContent() );
        
        $this->assertNull( $tag->getContent() );
    }
    
    public function testHasContent ()
    {
        $tag = new cPHP::Tag("a");
        
        $this->assertFalse( $tag->hasContent() );
        
        $tag->setContent("This is a string") ;
        
        $this->assertTrue( $tag->hasContent() );
        
        $tag->clearContent();
        
        $this->assertFalse( $tag->hasContent() );
    }
    
    public function testIsEmpty ()
    {
        $tag = new cPHP::Tag("hr");
        $this->assertTrue( $tag->isEmpty() );
        
        $tag->setContent("Random");
        $this->assertFalse( $tag->isEmpty() );
        
        $tag->clearContent();
        $this->assertTrue( $tag->isEmpty() );

        $tag->setTag('a');
        $this->assertFalse( $tag->isEmpty() );
    }
    
    public function testSetEmpty ()
    {
        $tag = new cPHP::Tag("hr");
        $this->assertTrue( $tag->isEmpty() );
        
        $this->assertSame( $tag, $tag->setEmpty(FALSE) );
        $this->assertFalse( $tag->isEmpty() );
        
        $this->assertSame( $tag, $tag->setEmpty(TRUE) );
        $this->assertTrue( $tag->isEmpty() );
        
        
        $tag = new cPHP::Tag("a");
        $this->assertFalse( $tag->isEmpty() );
        
        $this->assertSame( $tag, $tag->setEmpty(TRUE) );
        $this->assertTrue( $tag->isEmpty() );
        
        $this->assertSame( $tag, $tag->setEmpty(FALSE) );
        $this->assertFalse( $tag->isEmpty() );
    }
    
    public function testClearEmpty ()
    {
        $tag = new cPHP::Tag("hr");
        $this->assertTrue( $tag->isEmpty() );
        
        $this->assertSame( $tag, $tag->setEmpty(FALSE) );
        $this->assertFalse( $tag->isEmpty() );
        
        $this->assertSame( $tag, $tag->clearEmpty() );
        $this->assertTrue( $tag->isEmpty() );
        
        
        $tag = new cPHP::Tag("a");
        $this->assertFalse( $tag->isEmpty() );
        
        $this->assertSame( $tag, $tag->setEmpty(TRUE) );
        $this->assertTrue( $tag->isEmpty() );
        
        $this->assertSame( $tag, $tag->clearEmpty() );
        $this->assertFalse( $tag->isEmpty() );
    }
    
    public function testNormalizeAttrName ()
    {
        $this->assertSame( "attr", ::cPHP::Tag::normalizeAttrName("Attr") );
        $this->assertSame( "attr", ::cPHP::Tag::normalizeAttrName(" a!@#t tr ") );
        
        try {
            ::cPHP::Tag::normalizeAttrName("  ");
            $this->fail("An expected exception has not been thrown");
        }
        catch ( cPHP::Exception::Data::Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }
    }
    
    public function testSetAttr ()
    {
        $tag = new cPHP::Tag("hr");
        
        $this->assertSame( $tag, $tag->setAttr("Rel", "nofollow") );
        $this->assertSame( array("rel" => "nofollow"), $tag->getAttrs() );
        
        $this->assertSame( $tag, $tag->setAttr("class", "title") );
        $this->assertSame( array("rel" => "nofollow", "class" => "title"), $tag->getAttrs() );
        
        $this->assertSame( $tag, $tag->setAttr("class", "link") );
        $this->assertSame( array("rel" => "nofollow", "class" => "link"), $tag->getAttrs() );
        
        try {
            $tag->setAttr("  ", "empty");
            $this->fail("An expected exception has not been thrown");
        }
        catch ( cPHP::Exception::Data::Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }
    }
    
    public function testIsAttrSet ()
    {
        $tag = new cPHP::Tag("hr");
        
        $this->assertFalse( $tag->isAttrSet("class") );
        
        $tag->setAttr("class", "title");
        
        $this->assertTrue( $tag->isAttrSet("class") );
        
        try {
            $tag->isAttrSet("  ", "empty");
            $this->fail("An expected exception has not been thrown");
        }
        catch ( cPHP::Exception::Data::Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }
    }
    
    public function testClearAttr ()
    {
        $tag = new cPHP::Tag("hr");
        
        $this->assertSame( $tag, $tag->clearAttr("rel") );
        
        $tag->setAttr("rel", "nofollow")
            ->setAttr("class", "title");
        
        $this->assertSame( array("rel" => "nofollow", "class" => "title"), $tag->getAttrs() );
        
        $this->assertSame( $tag, $tag->clearAttr("rel") );
        
        $this->assertSame( array("class" => "title"), $tag->getAttrs() );
        
        try {
            $tag->clearAttr("  ");
            $this->fail("An expected exception has not been thrown");
        }
        catch ( cPHP::Exception::Data::Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }
    }
    
    public function testGetAttr ()
    {
        $tag = new cPHP::Tag("hr");
        
        $this->assertNull( $tag->getAttr("rel") );
        
        $tag->setAttr("rel", "nofollow");
        
        $this->assertSame( "nofollow", $tag->getAttr("rel") );
        
        try {
            $tag->getAttr("  ");
            $this->fail("An expected exception has not been thrown");
        }
        catch ( cPHP::Exception::Data::Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }
    }
    
    public function testImportAttrs ()
    {
        $tag = new cPHP::Tag("hr");
        
        $this->assertSame( array(), $tag->getAttrs() );
        
        $this->assertSame(
                $tag,
                $tag->importAttrs( array( "rel" => "nofollow", "class" => "link" ) )
            );
        
        $this->assertSame( array( "rel" => "nofollow", "class" => "link" ), $tag->getAttrs() );
        
        try {
            $tag->importAttrs( array( "rel" => "nofollow", "  " => "link" ) );
            $this->fail("An expected exception has not been thrown");
        }
        catch ( cPHP::Exception::Data::Argument $err ) {
            $this->assertEquals( "Must not be empty", $err->getMessage() );
        }
    }
    
}

?>