<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once 'PHPUnit/Framework.php';
require_once rtrim( dirname( __FILE__ ), "/" ) ."/../general.php";

/**
 * Runs all the tests in the classes folder
 */
class classes_allTests
{
    
    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite('commonPHP Classes');
        
        $suite->addTestSuite( 'general' );

        $suite->addFromFiles( "classes_", __DIR__, basename(__FILE__) );
        
        return $suite;
    }

}
?>