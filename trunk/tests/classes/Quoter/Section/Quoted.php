<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once rtrim( dirname( __FILE__ ), "/" ) ."/../../../general.php";

/**
 * test suite
 */
class classes_quoter_section_quoted
{
    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite('commonPHP quoted Section Class');
        $suite->addLib();
        $suite->addTestSuite( 'classes_quoter_section_quoted_tests' );
        return $suite;
    }
}

/**
 * unit tests
 */
class classes_quoter_section_quoted_tests extends PHPUnit_Framework_TestCase
{
    public function testConstruct ()
    {
        $section = new ::cPHP::Quoter::Section::Quoted(15, "snip", '"', "'");
        $this->assertSame( 15, $section->getOffset() );
        $this->assertSame( "snip", $section->getContent() );
        $this->assertSame( '"', $section->getOpenQuote() );
        $this->assertSame( "'", $section->getCloseQuote() );
    }
    
    public function testIsQuoted ()
    {
        $section = new ::cPHP::Quoter::Section::Quoted(0, null, '"', "'");
        
        $this->assertTrue( $section->isQuoted() );
    }
    
    public function testSetOpenQuote ()
    {
        $section = new ::cPHP::Quoter::Section::Quoted(0, null, '"', "'");
        
        $this->assertEquals( '"', $section->getOpenQuote() );
        
        $this->assertSame( $section, $section->setOpenQuote("newQuote") );
        
        $this->assertEquals( 'newQuote', $section->getOpenQuote() );
        
        $this->assertSame( $section, $section->setOpenQuote(null) );
        
        $this->assertNull( $section->getOpenQuote() );
    }
    
    public function testClearOpenQuote ()
    {
        $section = new ::cPHP::Quoter::Section::Quoted(0, null, '"', "'");
        
        $this->assertEquals( '"', $section->getOpenQuote() );
        
        $this->assertSame( $section, $section->clearOpenQuote() );
        
        $this->assertNull( $section->getOpenQuote() );
    }
    
    public function testOpenQuoteExists ()
    {
        $section = new ::cPHP::Quoter::Section::Quoted(0, null, '"', "'");
        
        $this->assertTrue( $section->openQuoteExists() );
        
        $section->clearOpenQuote();
        
        $this->assertFalse( $section->openQuoteExists() );
        
        $section->setOpenQuote("newQuote");
        
        $this->assertTrue( $section->openQuoteExists() );
    }
    
    public function testSetCloseQuote ()
    {
        $section = new ::cPHP::Quoter::Section::Quoted(0, null, '"', "'");
        
        $this->assertEquals( "'", $section->getCloseQuote() );
        
        $this->assertSame( $section, $section->setCloseQuote("newQuote") );
        
        $this->assertEquals( 'newQuote', $section->getCloseQuote() );
        
        $this->assertSame( $section, $section->setCloseQuote(null) );
        
        $this->assertNull( $section->getCloseQuote() );
    }
    
    public function testClearCloseQuote ()
    {
        $section = new ::cPHP::Quoter::Section::Quoted(0, null, '"', "'");
        
        $this->assertEquals( "'", $section->getCloseQuote() );
        
        $this->assertSame( $section, $section->clearCloseQuote() );
        
        $this->assertNull( $section->getCloseQuote() );
    }
    
    public function testCloseQuoteExists ()
    {
        $section = new ::cPHP::Quoter::Section::Quoted(0, null, '"', "'");
        
        $this->assertTrue( $section->closeQuoteExists() );
        
        $section->clearCloseQuote();
        
        $this->assertFalse( $section->closeQuoteExists() );
        
        $section->setCloseQuote("newQuote");
        
        $this->assertTrue( $section->closeQuoteExists() );
    }
    
    public function testToString ()
    {
        $section = new ::cPHP::Quoter::Section::Quoted(0, "snip", "(", ")");
        
        $this->assertSame( "(snip)", $section->__toString() );
        $this->assertSame( "(snip)", "$section" );
    }
    
}

?>