<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once rtrim( dirname( __FILE__ ), "/" ) ."/../general.php";

/**
 * test suite
 */
class classes_quoter
{
    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite('commonPHP Quoter Class');
        $suite->addLib();
        $suite->addTestSuite( 'classes_quoter_tests' );
        
        return $suite;
    }
}

/**
 * Unit Tests
 */
class classes_quoter_tests extends PHPUnit_Framework_TestCase
{
    
    public function testInitial ()
    {
        $quoter = new ::cPHP::Quoter;
        
        $this->assertThat( $quoter->getQuotes(), $this->isInstanceOf("cPHP::Ary") );
        
        $this->assertSame(
                array( "'" => array( "'" ), '"' => array( '"' ) ),
                $quoter->getQuotes()->get()
            );
        
        $this->assertSame( '\\', $quoter->getEscape() );
    }
    
    public function testClearQuotes ()
    {
        $quoter = new ::cPHP::Quoter;
        
        $this->assertSame(
                array( "'" => array( "'" ), '"' => array( '"' ) ),
                $quoter->getQuotes()->get()
            );
        
        $this->assertSame( $quoter, $quoter->clearQuotes() );
        
        $this->assertSame(
                array(),
                $quoter->getQuotes()->get()
            );
    }
    
    public function testSetQuote ()
    {
        $quoter = new ::cPHP::Quoter;
        $quoter->clearQuotes();
        
        $this->assertSame(
                array(),
                $quoter->getQuotes()->get()
            );
        
        $this->assertSame( $quoter, $quoter->setQuote( "`" ) );
        
        $this->assertSame(
                array( "`" => array("`") ),
                $quoter->getQuotes()->get()
            );
        
        
        $this->assertSame( $quoter, $quoter->setQuote( "(", ")" ) );
        
        $this->assertSame(
                array( "`" => array("`"), "(" => array( ")" ) ),
                $quoter->getQuotes()->get()
            );
    }
    
    public function testGetAllQuotes ()
    {
        $quoter = new ::cPHP::Quoter;
        $quoter->setQuote( "'", array( "'", '"', '`' ) );
        $quoter->setQuote( "!", array( '@', '#', '*' ) );
        
        $quotes = $quoter->getAllQuotes();
        $this->assertThat( $quotes, $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals(
                array( "'", '"', '`', '@', '#', '*', "!" ),
                $quotes->get()
            );
    }
    
    public function testGetOpenQuotes ()
    {
        $quoter = new ::cPHP::Quoter;
        $quoter->setQuote( "'", array( "'", '"', '`' ) );
        $quoter->setQuote( "!", array( '@', '#', '*' ) );
        $quoter->setQuote( "(", ")" );
        $quoter->setQuote( "`" );
        
        $quotes = $quoter->getOpenQuotes();
        $this->assertThat( $quotes, $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals(
                array( "'", '"', '!', '(', '`' ),
                $quotes->get()
            );
    }
    
    public function testIsOpenQuote ()
    {
        $quoter = new ::cPHP::Quoter;
        $quoter->setQuote( "(", ")" );
        $quoter->setQuote( "`" );
        
        $this->assertTrue( $quoter->isOpenQuote("(") );
        $this->assertTrue( $quoter->isOpenQuote("`") );
        $this->assertFalse( $quoter->isOpenQuote(")") );
    }
    
    public function testGetCloseQuotesFor ()
    {
        $quoter = new ::cPHP::Quoter;
        $quoter->setQuote( "(", ")" );
        $quoter->setQuote( "`" );
        $quoter->setQuote( "!", array( '@', '#', '*' ) );
        
        $quotes = $quoter->getCloseQuotesFor( "(" );
        $this->assertThat( $quotes, $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals( array( ')' ), $quotes->get() );
        
        $quotes = $quoter->getCloseQuotesFor( "!" );
        $this->assertThat( $quotes, $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals( array( '@', '#', '*' ), $quotes->get() );
    }
    
    public function testSetEscape ()
    {
        $quoter = new ::cPHP::Quoter;
        $this->assertSame( $quoter, $quoter->setEscape( "new" ) );
        $this->assertSame( "new", $quoter->getEscape() );
        
        try {
            $quoter->setEscape("");
            $this->fail("An expected exception was not thrown");
        }
        catch ( cPHP::Exception::Argument $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }
    }
    
    public function testClearEscape ()
    {
        $quoter = new ::cPHP::Quoter;
        $quoter->setEscape( '\\' );
        
        $this->assertSame( $quoter, $quoter->clearEscape() );
        $this->assertNull( $quoter->getEscape() );
    }
    
    public function testEscapeExists ()
    {
        $quoter = new ::cPHP::Quoter;
        $quoter->setEscape( '\\' );
        
        $this->assertTrue( $quoter->escapeExists() );
        
        $quoter->clearEscape();
        
        $this->assertFalse( $quoter->escapeExists() );
    }
    
    public function testIsEscaped ()
    {
        $this->assertFalse( ::cPHP::Quoter::isEscaped("isn\\'t it escaped?", 90) );
        
        $this->assertFalse( ::cPHP::Quoter::isEscaped("isn\\'t it escaped?", 0) );
        $this->assertFalse( ::cPHP::Quoter::isEscaped("isn\\'t it escaped?", 8) );
        
        $this->assertTrue( ::cPHP::Quoter::isEscaped("isn\\'t it escaped?", 4) );
        $this->assertFalse( ::cPHP::Quoter::isEscaped("isn\\\\'t it escaped?", 5) );
        $this->assertTrue( ::cPHP::Quoter::isEscaped("isn\\\\\\'t it escaped?", 6) );
        $this->assertFalse( ::cPHP::Quoter::isEscaped("isn\\\\\\\\'t it escaped?", 7) );
        $this->assertTrue( ::cPHP::Quoter::isEscaped("isn\\\\\\\\\\'t it escaped?", 8) );
        $this->assertFalse( ::cPHP::Quoter::isEscaped("isn\\\\\\\\\\'t it escaped?", 9) );
        $this->assertTrue( ::cPHP::Quoter::isEscaped("isn\\\\\\\\\\\\\\'t it escaped?", 10) );
        
        $this->assertFalse( ::cPHP::Quoter::isEscaped("\\\\isn't it escaped?", 2) );
        
        
        $this->assertFalse( ::cPHP::Quoter::isEscaped("isn't it escaped?", 2, '/esc/') );
        $this->assertTrue( ::cPHP::Quoter::isEscaped("isn/esc/'t it escaped?", 8, '/esc/') );
        $this->assertTrue( ::cPHP::Quoter::isEscaped("isn/EsC/'t it escaped?", 8, '/esc/') );
        $this->assertFalse( ::cPHP::Quoter::isEscaped("isn/Esc//EsC/'t it escaped?", 13, '/esc/') );
        $this->assertTrue( ::cPHP::Quoter::isEscaped("isn/EsC//Esc//EsC/'t it escaped?", 18, '/esc/') );
        
        $this->assertTrue( ::cPHP::Quoter::isEscaped("/esc/isn't it escaped?", 5, '/esc/') );
        
        $this->assertFalse( ::cPHP::Quoter::isEscaped("isn\\'t it escaped?", 4, null) );
        $this->assertFalse( ::cPHP::Quoter::isEscaped("isn\\'t it escaped?", 8, null) );
    }
    
    public function testFindNext ()
    {
        try {
            ::cPHP::Quoter::findNext( "string", array ('"', "") );
            $this->fail("An expected exception was not thrown");
        }
        catch ( cPHP::Exception::Data $err ) {
            $this->assertSame( "Needle must not be empty", $err->getMessage() );
        }
        
        $this->assertSame(
                array(false, false),
                ::cPHP::Quoter::findNext( "string", array() )
            );
        
        $this->assertSame(
                array(8, "'"),
                ::cPHP::Quoter::findNext(
                        "It\\'s a 'quoted' string",
                        array ('"', "'"),
                        '\\'
                    )
            );
        
        $this->assertSame(
                array(false, false),
                ::cPHP::Quoter::findNext(
                        "It\\'s a \\'quoted\\'  string",
                        array ('"', "'"),
                        '\\'
                    )
            );
        
        $this->assertSame(
                array(0, '"'),
                ::cPHP::Quoter::findNext(
                        '"Its a \\"quoted\\"  string',
                        array ('"', "'"),
                        '\\'
                    )
            );
        
        $this->assertSame(
                array(false, false),
                ::cPHP::Quoter::findNext(
                        'String without quotes',
                        array ('"', "'"),
                        '\\'
                    )
            );
        
        $this->assertSame(
                array(false, false),
                ::cPHP::Quoter::findNext(
                        'String \"without\" quotes',
                        array ('"', "'"),
                        '\\'
                    )
            );
        
        $this->assertSame(
                array(6, "'"),
                ::cPHP::Quoter::findNext(
                        'String\' "with" \'quotes',
                        array ('"', "'"),
                        '\\'
                    )
            );
        
        $this->assertSame(
                array(6, '"'),
                ::cPHP::Quoter::findNext(
                        "String\" 'with' \"quotes",
                        array ('"', "'"),
                        '\\'
                    )
            );
        
        $this->assertSame(
                array(19, "'"),
                ::cPHP::Quoter::findNext(
                        "String with a quote'",
                        array ('"', "'"),
                        '\\'
                    )
            );
        
        $this->assertSame(
                array(12, "QT"),
                ::cPHP::Quoter::findNext(
                        "String with QTa quote",
                        array ('QT'),
                        '\\'
                    )
            );
        
        $this->assertSame(
                array(22, "QT"),
                ::cPHP::Quoter::findNext(
                        "String with \\QTa quoteQT",
                        array ('QT'),
                        '\\'
                    )
            );
        
        $this->assertSame(
                array(12, "QT"),
                ::cPHP::Quoter::findNext(
                        "String with qtQT2 a quote",
                        array ('QT', 'QT2'),
                        '\\'
                    )
            );
        
        $this->assertSame(
                array(14, "QT"),
                ::cPHP::Quoter::findNext(
                        "String q with qt a quote",
                        array ('QT', 'QT2'),
                        '\\'
                    )
            );
        
        $this->assertSame(
                array(12, "QT"),
                ::cPHP::Quoter::findNext(
                        "String with QT2a quote",
                        array ('QT', 'QT2'),
                        '\\'
                    )
            );
        
        $this->assertSame(
                array(7, "with"),
                ::cPHP::Quoter::findNext(
                        "String with a quote",
                        array ('with', 'it'),
                        '\\'
                    )
            );
        
    }
    
    public function testParse ()
    {
        $quoter = new ::cPHP::Quoter;
        
        $quoter->parse("string 'with' quotes");
    }
    
}

?>