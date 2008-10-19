<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once rtrim( dirname( __FILE__ ), "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_quoter_section_unquoted extends PHPUnit_Framework_TestCase
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