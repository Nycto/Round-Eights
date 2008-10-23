<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/general.php";
require_once rtrim( __DIR__, "/" ) ."/functions/allTests.php";
require_once rtrim( __DIR__, "/" ) ."/classes/allTests.php";

/**
 * Unit test for running all the tests
 */
class AllTests
{
    
    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite('All commonPHP Tests');
        $suite->addTestSuite( 'functions_allTests' );
        $suite->addTestSuite( 'classes_allTests' );
        return $suite;
    }

}

?>