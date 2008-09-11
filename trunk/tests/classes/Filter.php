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
class classes_filter
{
    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite('commonPHP Filter Class');
        $suite->addLib();
        $suite->addTestSuite( 'classes_filter_tests' );
        
        return $suite;
    }
}

/**
 * unit tests
 */
class classes_filter_tests extends PHPUnit_Framework_TestCase
{
   
    public function testAdd ()
    {
        $mock = $this->getMock("cPHP::iFace::Filter") instanceof cPHP::iFace::Filter;
    }
   
}

?>