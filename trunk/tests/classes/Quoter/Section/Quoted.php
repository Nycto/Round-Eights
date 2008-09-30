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
    
    public function testIsQuoted ()
    {
        $section = new ::cPHP::Quoter::Section::Quoted(0, null, '"', '"');
        $this->assertTrue( $section->isQuoted() );
    }
    
}

?>