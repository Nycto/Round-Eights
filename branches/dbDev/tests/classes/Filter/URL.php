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
class classes_filter_url extends PHPUnit_Framework_TestCase
{
    
    public function testValidChars ()
    {
        $filter = new cPHP::Filter::URL;
        
        $this->assertEquals(
                "abcdefghijklmnopqrstuvwxyz",
                $filter->filter("abcdefghijklmnopqrstuvwxyz")
            );
        
        $this->assertEquals(
                "ABCDEFGHIJKLMNOPQRSTUVWXYZ",
                $filter->filter("ABCDEFGHIJKLMNOPQRSTUVWXYZ")
            );
        
        $this->assertEquals(
                "1234567890",
                $filter->filter("1234567890")
            );
        
        $this->assertEquals(
                "$-_.+!*'(),{}|\\^~[]`<>#%\";/?:@&=",
                $filter->filter("$-_.+!*'(),{}|\\^~[]`<>#%\";/?:@&=")
            );
    }
    
    public function testInvalidChars ()
    {
        $filter = new cPHP::Filter::URL;
        
        $this->assertEquals("", $filter->filter(''));
        $this->assertEquals("", $filter->filter(''));
        $this->assertEquals("", $filter->filter(' ¡¢£¤¥¦§¨©ª«¬­®¯°±²³´µ¶·¸¹º»'));
        $this->assertEquals("", $filter->filter('¼½¾¿ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÚÛÜÝÞßàáâ'));
        $this->assertEquals("", $filter->filter('ãäåæçèéêëìíîïðñòóôõö÷øùúûüýþÿ'));
    }
    
    public function testMixedChars ()
    {
        $filter = new cPHP::Filter::URL;
        
        $this->assertEquals('ab12!@#$asd%<>?D{}', $filter->filter('ab12!@#$asd%<>?D{}'));
    }
    
}

?>