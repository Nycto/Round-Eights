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
    
    public function testInitialQuotes ()
    {
        $quoter = new ::cPHP::Quoter;
        
        $this->assertSame(
                array( "'" => array( "'" ), '"' => array( '"' ) ),
                $quoter->getQuotes()
            );
    }
    
    public function testClearQuotes ()
    {
        $quoter = new ::cPHP::Quoter;
        
        $this->assertSame(
                array( "'" => array( "'" ), '"' => array( '"' ) ),
                $quoter->getQuotes()
            );
        
        $this->assertSame( $quoter, $quoter->clearQuotes() );
        
        $this->assertSame(
                array(),
                $quoter->getQuotes()
            );
    }
    
    public function testSetQuote ()
    {
        $quoter = new ::cPHP::Quoter;
        $quoter->clearQuotes();
        
        $this->assertSame(
                array(),
                $quoter->getQuotes()
            );
        
        $this->assertSame( $quoter, $quoter->setQuote( "`" ) );
        
        $this->assertSame(
                array( "`" => array("`") ),
                $quoter->getQuotes()
            );
        
        
        $this->assertSame( $quoter, $quoter->setQuote( "(", ")" ) );
        
        $this->assertSame(
                array( "`" => array("`"), "(" => array( ")" ) ),
                $quoter->getQuotes()
            );
    }
    
}

?>