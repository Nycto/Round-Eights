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
class classes_filter_ip extends PHPUnit_Framework_TestCase
{
    
    public function testValidChars ()
    {
        $filter = new cPHP::Filter::IP;
        
        $this->assertEquals(
                "1234567890.",
                $filter->filter("1234567890.")
            );
        
    }
    
    public function testInvalidChars ()
    {
        $filter = new cPHP::Filter::IP;
            
        $this->assertEquals("", $filter->filter('!"#$%&\'()*+,-/:;<=>?@'));
        $this->assertEquals("", $filter->filter('ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`'));
        $this->assertEquals("", $filter->filter('abcdefghijklmnopqrstuvwxyz{|}~'));
        $this->assertEquals("", $filter->filter(''));
        $this->assertEquals("", $filter->filter(''));
        $this->assertEquals("", $filter->filter(' ¡¢£¤¥¦§¨©ª«¬­®¯°±²³´µ'));
        $this->assertEquals("", $filter->filter('¶·¸¹º»¼½¾¿ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ'));
        $this->assertEquals("", $filter->filter('×ØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõö÷'));
        $this->assertEquals("", $filter->filter('øùúûüýþÿ'));
        
    }
    
}

?>