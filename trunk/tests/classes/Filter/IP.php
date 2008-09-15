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
class classes_filter_ip
{
    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite('commonPHP IP Filter Class');
        $suite->addLib();
        $suite->addTestSuite( 'classes_filter_ip_tests' );
        
        return $suite;
    }
}

/**
 * unit tests
 */
class classes_filter_ip_tests extends PHPUnit_Framework_TestCase
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