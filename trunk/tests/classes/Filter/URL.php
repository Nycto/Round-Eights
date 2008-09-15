<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once 'PHPUnit/Framework.php';
require_once rtrim( dirname( __FILE__ ), "/" ) ."/../../general.php";

/**
 * test suite
 */
class classes_filter_url
{
    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite('commonPHP URL Filter Class');
        $suite->addLib();
        $suite->addTestSuite( 'classes_filter_url_tests' );
        return $suite;
    }
}

/**
 * unit tests
 */
class classes_filter_url_tests extends PHPUnit_Framework_TestCase
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