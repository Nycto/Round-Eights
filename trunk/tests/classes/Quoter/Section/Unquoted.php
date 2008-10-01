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
class classes_quoter_section_unquoted
{
    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite('commonPHP Unquoted Section Class');
        $suite->addLib();
        $suite->addTestSuite( 'classes_quoter_section_unquoted_tests' );
        return $suite;
    }
}

/**
 * unit tests
 */
class classes_quoter_section_unquoted_tests extends PHPUnit_Framework_TestCase
{
    
    public function testIsQuoted ()
    {
        $section = new ::cPHP::Quoter::Section::Unquoted(0, null);
        $this->assertFalse( $section->isQuoted() );
    }
    
    public function testToString ()
    {
        $section = new ::cPHP::Quoter::Section::Unquoted(0, "snip");
        
        $this->assertSame( "snip", $section->__toString() );
        $this->assertSame( "snip", "$section" );
    }
    
}

?>