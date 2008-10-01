<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once rtrim( dirname( __FILE__ ), "/" ) ."/../../general.php";

/**
 * test suite
 */
class classes_quoter_parsed
{
    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite('commonPHP Parsed Quoter Class');
        $suite->addLib();
        $suite->addTestSuite( 'classes_quoter_parsed_tests' );
        return $suite;
    }
}

/**
 * unit tests
 */
class classes_quoter_parsed_tests extends PHPUnit_Framework_TestCase
{
    
    public function testAddSection ()
    {
        $mock = $this->getMock("cPHP::Quoter::Section", array("isQuoted", "__toString"), array(0, null));
        
        $list = new cPHP::Quoter::Parsed;
        
        $this->assertSame( $list, $list->addSection($mock) );
        
        $sections = $list->getSections();
        
        $this->assertThat( $sections, $this->isInstanceOf("cPHP::Ary") );
        $this->assertSame( array( $mock ), $sections->get() );
    }
    
    public function testToString ()
    {
        $list = new cPHP::Quoter::Parsed;
        
        $list->addSection( new cPHP::Quoter::Section::Unquoted(0, "snippet") );
        $list->addSection( new cPHP::Quoter::Section::Quoted(8, "inQuotes", '(', ')') );
        
        $this->assertSame( "snippet(inQuotes)", $list->__toString() );
        $this->assertSame( "snippet(inQuotes)", "$list" );
    }
    
}

?>