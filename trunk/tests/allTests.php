<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once 'PHPUnit/Framework.php';
require_once rtrim( dirname( __FILE__ ), "/" ) ."/general.php";
require_once rtrim( dirname( __FILE__ ), "/" ) ."/functions/allTests.php";
require_once rtrim( dirname( __FILE__ ), "/" ) ."/classes/allTests.php";

/**
 * Unit test for running all the tests
 */
class AllTests
{
    
    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite('All commonPHP Tests');
        $suite->addLib();
        $suite->addTestSuite( 'functions_allTests' );
        $suite->addTestSuite( 'classes_allTests' );
        return $suite;
    }

}

?>